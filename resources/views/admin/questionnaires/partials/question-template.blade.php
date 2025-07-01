<div class="question-block bg-gray-50 p-4 rounded-md shadow-sm mb-4 border border-gray-200">
    {{-- Hidden input for existing question ID --}}
    @if (isset($question->id))
        <input type="hidden" name="sections[{{ $sIndex }}][questions][{{ $qIndex }}][id]" value="{{ $question->id }}">
    @endif

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-medium text-gray-700">Pregunta #{{ $qIndex + 1 }}</h4>
        <button type="button" class="remove-question-btn text-red-500 hover:text-red-700 text-sm">Eliminar</button>
    </div>

    <div class="mb-3">
        <x-input-label for="sections[{{ $sIndex }}][questions][{{ $qIndex }}][text]" :value="__('Texto de la Pregunta')" />
        <textarea id="sections[{{ $sIndex }}][questions][{{ $qIndex }}][text]" name="sections[{{ $sIndex }}][questions][{{ $qIndex }}][text]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('sections.' . $sIndex . '.questions.' . $qIndex . '.text', $question->text ?? $qData['text'] ?? '') }}</textarea>
        @error('sections.' . $sIndex . '.questions.' . $qIndex . '.text') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <x-input-label for="sections[{{ $sIndex }}][questions][{{ $qIndex }}][type]" :value="__('Tipo de Respuesta')" />
        <select id="sections[{{ $sIndex }}][questions][{{ $qIndex }}][type]" name="sections[{{ $sIndex }}][questions][{{ $qIndex }}][type]" class="question-type-select border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
            <option value="text" {{ old('sections.' . $sIndex . '.questions.' . $qIndex . '.type', $question->type ?? $qData['type'] ?? '') == 'text' ? 'selected' : '' }}>Texto</option>
            <option value="multiple_choice" {{ old('sections.' . $sIndex . '.questions.' . $qIndex . '.type', $question->type ?? $qData['type'] ?? '') == 'multiple_choice' ? 'selected' : '' }}>Selección Múltiple</option>
        </select>
        @error('sections.' . $sIndex . '.questions.' . $qIndex . '.type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="options-container {{ old('sections.' . $sIndex . '.questions.' . $qIndex . '.type', $question->type ?? $qData['type'] ?? '') == 'multiple_choice' ? '' : 'hidden' }}">
        <h5 class="font-medium text-gray-700 mt-4 mb-2">Opciones de Selección Múltiple</h5>
        <div class="options-list">
            @if (isset($question) && $question->type === 'multiple_choice')
                @foreach ($question->options as $optIndex => $option)
                    <div class="option-item flex items-center mb-2">
                        <input type="hidden" name="sections[{{ $sIndex }}][questions][{{ $qIndex }}][options][{{ $optIndex }}][id]" value="{{ $option->id }}">
                        <x-text-input type="text" name="sections[{{ $sIndex }}][questions][{{ $qIndex }}][options][{{ $optIndex }}][option_text]" class="block w-full" placeholder="Texto de la opción" value="{{ old('sections.' . $sIndex . '.questions.' . $qIndex . '.options.' . $optIndex . '.option_text', $option->option_text) }}" required />
                        <button type="button" class="remove-option-btn text-red-500 hover:text-red-700 ml-2 text-sm">X</button>
                    </div>
                @endforeach
            @elseif (isset($sData['questions'][$qIndex]['options'])) {{-- For old() data on failed validation --}}
                @foreach ($sData['questions'][$qIndex]['options'] as $optIndex => $optionData)
                    <div class="option-item flex items-center mb-2">
                        @if (isset($optionData['id']))
                            <input type="hidden" name="sections[{{ $sIndex }}][questions][{{ $qIndex }}][options][{{ $optIndex }}][id]" value="{{ $optionData['id'] }}">
                        @endif
                        <x-text-input type="text" name="sections[{{ $sIndex }}][questions][{{ $qIndex }}][options][{{ $optIndex }}][option_text]" class="block w-full" placeholder="Texto de la opción" value="{{ old('sections.' . $sIndex . '.questions.' . $qIndex . '.options.' . $optIndex . '.option_text', $optionData['option_text']) }}" required />
                        <button type="button" class="remove-option-btn text-red-500 hover:text-red-700 ml-2 text-sm">X</button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="add-option-btn inline-flex items-center px-3 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-400 focus:bg-blue-400 active:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">
            Añadir Opción
        </button>
    </div>
</div>