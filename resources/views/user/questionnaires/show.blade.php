<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Título de la página --}}
            <!-- <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Responder Cuestionario') }}
                    </h2>
                </div>
            </div> -->

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $questionnaire->title }}</h3>
                        <p class="text-gray-600 mt-2">{{ $questionnaire->description }}</p>
                        <!-- <p class="text-sm text-gray-500 mt-2">Creado por: {{ $questionnaire->creator->name ?? 'N/A' }}</p> -->
                    </div>

                    <form method="POST" action="{{ route('user.questionnaires.submit', $questionnaire) }}">
                        @csrf

                        @forelse ($questionnaire->sections as $section)
                            <div class="section-block bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
                                <h4 class="font-semibold text-xl text-gray-800 mb-3"> {{ $section->title }}</h4>
                                @if ($section->description)
                                    <p class="text-gray-600 text-sm mb-4">{{ $section->description }}</p>
                                @endif

                                @forelse ($section->questions as $question)
                                    @php
                                        // Obtener la respuesta individual para esta pregunta (si existe un borrador)
                                        $answerSubmission = $response->answers->where('question_id', $question->id)->first();

                                        // Obtener las opciones seleccionadas previamente para esta pregunta de forma segura
                                        $selectedOptionIds = old('answers.' . $question->id . '.value', $answerSubmission?->selectedOptions->pluck('id')->toArray() ?? []);
                                    @endphp

                                    <div class="question-block bg-white p-4 rounded-md shadow-sm mb-4 border border-gray-100">
                                        <p class="font-medium text-gray-700 mb-3"> {{ $question->text }}</p>

                                        <!-- Campo de Respuesta Principal -->
                                        <div class="mb-3">
                                            <x-input-label for="answer_{{ $question->id }}_value" :value="__('Tu Respuesta')" />
                                            @if ($question->type === 'text')
                                                <textarea id="answer_{{ $question->id }}_value" name="answers[{{ $question->id }}][value]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3" required>{{ old('answers.' . $question->id . '.value', $answerSubmission->answer_text ?? '') }}</textarea>
                                            @elseif ($question->type === 'multiple_choice')
                                                {{-- Cambiado a checkboxes para selección múltiple --}}
                                                @foreach ($question->options as $option)
                                                    <div class="flex items-center mt-2">
                                                        <input type="checkbox" id="option_{{ $option->id }}" name="answers[{{ $question->id }}][value][]" value="{{ $option->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                                            {{ in_array($option->id, $selectedOptionIds) ? 'checked' : '' }}>
                                                        <label for="option_{{ $option->id }}" class="ml-2 block text-sm text-gray-900">{{ $option->option_text }}</label>
                                                    </div>
                                                @endforeach
                                                {{-- Mensaje de error para la validación del array completo --}}
                                                @error('answers.' . $question->id . '.value')
                                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                @enderror
                                            @endif
                                            {{-- Mensaje de error para preguntas de texto --}}
                                            @if ($question->type === 'text')
                                                <x-input-error :messages="$errors->get('answers.' . $question->id . '.value')" class="mt-2" />
                                            @endif
                                        </div>

                                        <!-- Campo de Observaciones (siempre habilitado) -->
                                        <div class="mb-3">
                                            <x-input-label for="answer_{{ $question->id }}_observations" :value="__('Observaciones (Opcional)')" />
                                            <textarea id="answer_{{ $question->id }}_observations" name="answers[{{ $question->id }}][observations]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="2">{{ old('answers.' . $question->id . '.observations', $answerSubmission->observations ?? '') }}</textarea>
                                            <x-input-error :messages="$errors->get('answers.' . $question->id . '.observations')" class="mt-2" />
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm">Esta sección no tiene preguntas.</p>
                                @endforelse
                            </div>
                        @empty
                            <p class="text-gray-500">Este cuestionario no tiene secciones ni preguntas.</p>
                        @endforelse

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('user.questionnaires.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Enviar Cuestionario') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>