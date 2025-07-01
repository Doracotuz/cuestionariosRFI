<div class="section-block bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
    {{-- Hidden input for existing section ID --}}
    @if (isset($section->id))
        <input type="hidden" name="sections[{{ $sIndex }}][id]" value="{{ $section->id }}">
    @endif

    <div class="flex justify-between items-center mb-4">
        <h4 class="font-semibold text-gray-800">Sección #{{ $sIndex + 1 }}</h4>
        <button type="button" class="remove-section-btn text-red-500 hover:text-red-700 text-sm">Eliminar Sección</button>
    </div>

    <div class="mb-4">
        <x-input-label for="sections[{{ $sIndex }}][title]" :value="__('Título de la Sección')" />
        <x-text-input id="sections[{{ $sIndex }}][title]" class="block mt-1 w-full" type="text" name="sections[{{ $sIndex }}][title]" value="{{ old('sections.' . $sIndex . '.title', $section->title ?? $sData['title'] ?? '') }}" required />
        @error('sections.' . $sIndex . '.title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <x-input-label for="sections[{{ $sIndex }}][description]" :value="__('Descripción de la Sección (Opcional)')" />
        <x-textarea id="sections[{{ $sIndex }}][description]" class="block mt-1 w-full" name="sections[{{ $sIndex }}][description]">{{ old('sections.' . $sIndex . '.description', $section->description ?? $sData['description'] ?? '') }}</x-textarea>
        @error('sections.' . $sIndex . '.description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <h5 class="text-lg font-semibold mb-3 mt-6">Preguntas de esta Sección</h5>
    <div id="questions-container-{{ $sIndex }}" class="questions-container">
        <!-- Las preguntas se añadirán aquí dinámicamente -->
        @if (isset($section) && $section->questions->isNotEmpty())
            @foreach ($section->questions as $qIndex => $question)
                @include('admin.questionnaires.partials.question-template', ['sIndex' => $sIndex, 'qIndex' => $qIndex, 'question' => $question])
            @endforeach
        @elseif (isset($sData['questions'])) {{-- For old() data on failed validation --}}
            @foreach ($sData['questions'] as $qIndex => $qData)
                @include('admin.questionnaires.partials.question-template', ['sIndex' => $sIndex, 'qIndex' => $qIndex, 'qData' => $qData])
            @endforeach
        @endif
    </div>

    <button type="button" class="add-question-to-section-btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">
        {{ __('Añadir Pregunta a esta Sección') }}
    </button>
</div>
