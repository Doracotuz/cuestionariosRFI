<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Título de la página --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Detalles del Cuestionario') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $questionnaire->title }}</h3>
                        <p class="text-gray-600">{{ $questionnaire->description }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Estado: <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($questionnaire->status === 'published') bg-green-100 text-green-800
                                @elseif($questionnaire->status === 'draft') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($questionnaire->status) }}
                            </span>
                            <br>
                            Creado por: {{ $questionnaire->creator->name ?? 'N/A' }} el {{ $questionnaire->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <h4 class="text-lg font-semibold mt-6 mb-4">Secciones:</h4>
                    @forelse ($questionnaire->sections as $section)
                        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">
                            <h5 class="font-semibold text-gray-800 mb-2">Sección {{ $loop->iteration }}: {{ $section->title }}</h5>
                            @if ($section->description)
                                <p class="text-gray-600 text-sm mb-4">{{ $section->description }}</p>
                            @endif

                            <h6 class="text-md font-semibold mt-4 mb-3">Preguntas:</h6>
                            @forelse ($section->questions as $question)
                                <div class="bg-gray-50 p-4 rounded-md shadow-sm mb-3 border border-gray-200">
                                    <p class="font-medium text-gray-700 mb-2"><strong>Q{{ $loop->iteration }}:</strong> {{ $question->text }}</p>
                                    <p class="text-sm text-gray-600 mb-2">Tipo: {{ ucfirst(str_replace('_', ' ', $question->type)) }}</p>

                                    @if ($question->type === 'multiple_choice' && $question->options->isNotEmpty())
                                        <p class="text-sm text-gray-600 font-semibold">Opciones:</p>
                                        <ul class="list-disc list-inside ml-4 text-sm text-gray-600">
                                            @foreach ($question->options as $option)
                                                <li>{{ $option->option_text }}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <p class="text-sm text-gray-600 mt-2">Campo de Observaciones: {{ $question->observations_enabled ? 'Sí' : 'No' }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Esta sección no tiene preguntas.</p>
                            @endforelse
                        </div>
                    @empty
                        <p class="text-gray-500">Este cuestionario no tiene secciones ni preguntas.</p>
                    @endforelse

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.questionnaires.edit', $questionnaire) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                            {{ __('Editar Cuestionario') }}
                        </a>
                        <a href="{{ route('admin.questionnaires.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Volver a la Lista') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>