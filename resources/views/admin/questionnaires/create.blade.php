<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Título de la página --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Crear Nuevo Cuestionario') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.questionnaires.store') }}">
                        @csrf

                        <!-- Título del Cuestionario -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Título del Cuestionario')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Descripción del Cuestionario -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Descripción')" />
                            <x-textarea id="description" class="block mt-1 w-full" name="description">{{ old('description') }}</x-textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Estado del Cuestionario -->
                        <div class="mb-6">
                            <x-input-label for="status" :value="__('Estado')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publicado</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archivado</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <h3 class="text-xl font-semibold mb-4">Secciones</h3>
                        <div id="sections-container">
                            <!-- Las secciones se añadirán aquí dinámicamente -->
                            @if (old('sections'))
                                @foreach (old('sections') as $sIndex => $sData)
                                    @include('admin.questionnaires.partials.section-template', ['sIndex' => $sIndex, 'sData' => $sData])
                                @endforeach
                            @endif
                        </div>

                        <button type="button" id="add-section-btn" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 focus:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-4">
                            {{ __('Añadir Sección') }}
                        </button>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.questionnaires.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Guardar Cuestionario') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sectionsContainer = document.getElementById('sections-container');
            const addSectionBtn = document.getElementById('add-section-btn');
            let sectionIndex = sectionsContainer.children.length; // Start index for new sections

            // Helper to get a fresh section template
            function getSectionTemplate(sIndex, sData = {}) {
                const questionsHtml = sData.questions ? sData.questions.map((qData, qIndex) => getQuestionTemplate(sIndex, qIndex, qData)).join('') : '';

                // IMPORTANT: No Blade directives (including comments) inside JS template strings.
                // Use standard HTML elements and JS for dynamic values.
                return `
                    <div class="section-block bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-semibold text-gray-800">Sección #${sIndex + 1}</h4>
                            <button type="button" class="remove-section-btn text-red-500 hover:text-red-700 text-sm">Eliminar Sección</button>
                        </div>

                        <div class="mb-4">
                            <label for="sections[${sIndex}][title]" class="block font-medium text-sm text-gray-700">Título de la Sección</label>
                            <input id="sections[${sIndex}][title]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" type="text" name="sections[${sIndex}][title]" value="${sData.title || ''}" required />
                        </div>

                        <div class="mb-4">
                            <label for="sections[${sIndex}][description]" class="block font-medium text-sm text-gray-700">Descripción de la Sección (Opcional)</label>
                            <textarea id="sections[${sIndex}][description]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" name="sections[${sIndex}][description]">${sData.description || ''}</textarea>
                        </div>

                        <h5 class="text-lg font-semibold mb-3 mt-6">Preguntas de esta Sección</h5>
                        <div id="questions-container-${sIndex}" class="questions-container">
                            ${questionsHtml}
                        </div>

                        <button type="button" class="add-question-to-section-btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">
                            Añadir Pregunta a esta Sección
                        </button>
                    </div>
                `;
            }

            // Helper to get a fresh question template (now takes sIndex)
            function getQuestionTemplate(sIndex, qIndex, qData = {}) {
                const optionsHtml = qData.options ? qData.options.map((opt, optIndex) => getOptionTemplate(sIndex, qIndex, optIndex, opt)).join('') : '';

                return `
                    <div class="question-block bg-gray-50 p-4 rounded-md shadow-sm mb-4 border border-gray-200">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-medium text-gray-700">Pregunta #${qIndex + 1}</h4>
                            <button type="button" class="remove-question-btn text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                        </div>

                        <div class="mb-3">
                            <label for="sections[${sIndex}][questions][${qIndex}][text]" class="block font-medium text-sm text-gray-700">Texto de la Pregunta</label>
                            <textarea id="sections[${sIndex}][questions][${qIndex}][text]" name="sections[${sIndex}][questions][${qIndex}][text]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>${qData.text || ''}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="sections[${sIndex}][questions][${qIndex}][type]" class="block font-medium text-sm text-gray-700">Tipo de Respuesta</label>
                            <select id="sections[${sIndex}][questions][${qIndex}][type]" name="sections[${sIndex}][questions][${qIndex}][type]" class="question-type-select border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="text" ${qData.type === 'text' ? 'selected' : ''}>Texto</option>
                                <option value="multiple_choice" ${qData.type === 'multiple_choice' ? 'selected' : ''}>Selección Múltiple</option>
                            </select>
                        </div>

                        <div class="options-container ${qData.type === 'multiple_choice' ? '' : 'hidden'}">
                            <h5 class="font-medium text-gray-700 mt-4 mb-2">Opciones de Selección Múltiple</h5>
                            <div class="options-list">
                                ${optionsHtml}
                            </div>
                            <button type="button" class="add-option-btn inline-flex items-center px-3 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-400 focus:bg-blue-400 active:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">
                                Añadir Opción
                            </button>
                        </div>
                    </div>
                `;
            }

            // Helper to get a fresh option template (now takes sIndex and qIndex)
            function getOptionTemplate(sIndex, qIndex, optIndex, optData = {}) {
                return `
                    <div class="option-item flex items-center mb-2">
                        <input type="text" name="sections[${sIndex}][questions][${qIndex}][options][${optIndex}][option_text]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" placeholder="Texto de la opción" value="${optData.option_text || ''}" required>
                        <button type="button" class="remove-option-btn text-red-500 hover:text-red-700 ml-2 text-sm">X</button>
                    </div>
                `;
            }

            // Function to re-index all elements (sections, questions, options)
            function reIndexAllElements() {
                Array.from(sectionsContainer.children).forEach((sectionBlock, sIndex) => {
                    // Update section block elements
                    sectionBlock.querySelector('h4').textContent = `Sección #${sIndex + 1}`;
                    sectionBlock.querySelectorAll('[name^="sections["]').forEach(input => {
                        input.name = input.name.replace(/sections\[\d+\]/, `sections[${sIndex}]`);
                        input.id = input.id.replace(/sections\[\d+\]/, `sections[${sIndex}]`);
                    });

                    // Update questions within sections
                    const questionsContainer = sectionBlock.querySelector('.questions-container');
                    Array.from(questionsContainer.children).forEach((questionBlock, qIndex) => {
                        questionBlock.querySelector('h4').textContent = `Pregunta #${qIndex + 1}`;
                        questionBlock.querySelectorAll('[name^="sections["][name*="[questions]"]').forEach(input => {
                            input.name = input.name.replace(/sections\[\d+\]\[questions\]\[\d+\]/, `sections[${sIndex}][questions][${qIndex}]`);
                            input.id = input.id.replace(/sections\[\d+\]\[questions\]\[\d+\]/, `sections[${sIndex}][questions][${qIndex}]`);
                        });

                        // Update options within multiple choice questions
                        const optionsList = questionBlock.querySelector('.options-list');
                        if (optionsList) {
                            Array.from(optionsList.children).forEach((optionItem, optIndex) => {
                                const optionInput = optionItem.querySelector('input[type="text"]');
                                const optionIdInput = optionItem.querySelector('input[type="hidden"]');
                                if (optionInput) {
                                    optionInput.name = optionInput.name.replace(/options\[\d+\]/, `options[${optIndex}]`);
                                }
                                if (optionIdInput) {
                                    optionIdInput.name = optionIdInput.name.replace(/options\[\d+\]/, `options[${optIndex}]`);
                                }
                            });
                        }
                    });
                });
                sectionIndex = sectionsContainer.children.length; // Update global section index
            }

            // Add section event listener
            addSectionBtn.addEventListener('click', function () {
                const newSectionDiv = document.createElement('div');
                newSectionDiv.innerHTML = getSectionTemplate(sectionIndex);
                sectionsContainer.appendChild(newSectionDiv.firstElementChild);
                sectionIndex++;
                reIndexAllElements(); // Re-index all elements after adding
            });

            // Delegate event listeners for dynamically added elements
            sectionsContainer.addEventListener('click', function (event) {
                // Remove section button
                if (event.target.classList.contains('remove-section-btn')) {
                    event.target.closest('.section-block').remove();
                    reIndexAllElements(); // Re-index all elements after removal
                }

                // Add question to section button
                if (event.target.classList.contains('add-question-to-section-btn')) {
                    const sectionBlock = event.target.closest('.section-block');
                    const questionsContainerInSection = sectionBlock.querySelector('.questions-container');
                    const sIndex = Array.from(sectionsContainer.children).indexOf(sectionBlock);
                    let questionCount = questionsContainerInSection.children.length;

                    const newQuestionDiv = document.createElement('div');
                    newQuestionDiv.innerHTML = getQuestionTemplate(sIndex, questionCount);
                    questionsContainerInSection.appendChild(newQuestionDiv.firstElementChild);
                    reIndexAllElements(); // Re-index after adding a question
                }

                // Remove question button
                if (event.target.classList.contains('remove-question-btn')) {
                    event.target.closest('.question-block').remove();
                    reIndexAllElements(); // Re-index all elements after removal
                }

                // Add option button
                if (event.target.classList.contains('add-option-btn')) {
                    const questionBlock = event.target.closest('.question-block');
                    const optionsList = questionBlock.querySelector('.options-list');
                    const sectionBlock = questionBlock.closest('.section-block');
                    const sIndex = Array.from(sectionsContainer.children).indexOf(sectionBlock);
                    const qIndex = Array.from(sectionBlock.querySelector('.questions-container').children).indexOf(questionBlock);
                    let optionCount = optionsList.children.length;

                    const newOptionDiv = document.createElement('div');
                    newOptionDiv.innerHTML = getOptionTemplate(sIndex, qIndex, optionCount);
                    optionsList.appendChild(newOptionDiv.firstElementChild);
                    reIndexAllElements(); // Re-index options after adding
                }

                // Remove option button
                if (event.target.classList.contains('remove-option-btn')) {
                    event.target.closest('.option-item').remove();
                    reIndexAllElements(); // Re-index options after removal
                }
            });

            // Handle question type change (show/hide options)
            sectionsContainer.addEventListener('change', function (event) {
                if (event.target.classList.contains('question-type-select')) {
                    const questionBlock = event.target.closest('.question-block');
                    const optionsContainer = questionBlock.querySelector('.options-container');
                    if (event.target.value === 'multiple_choice') {
                        optionsContainer.classList.remove('hidden');
                        // Ensure options are required if type is multiple_choice
                        optionsContainer.querySelectorAll('input[type="text"]').forEach(input => input.setAttribute('required', 'required'));
                    } else {
                        optionsContainer.classList.add('hidden');
                        // Remove required from options if type is text
                        optionsContainer.querySelectorAll('input[type="text"]').forEach(input => input.removeAttribute('required'));
                    }
                }
            });

            // Initial re-indexing for existing old('sections') data
            reIndexAllElements();
        });
    </script>
    @endpush
</x-app-layout>