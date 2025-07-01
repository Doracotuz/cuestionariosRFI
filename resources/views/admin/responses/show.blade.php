<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Título de la página --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Detalles de la Respuesta del Cuestionario') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $response->questionnaire->title }}</h3>
                        <p class="text-gray-600 mt-2">{{ $response->questionnaire->description }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Respondido por: <span class="font-medium">{{ $response->user->name }} ({{ $response->user->email }})</span>
                            el {{ $response->submitted_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    @forelse ($response->questionnaire->sections as $section)
                        <div class="section-block bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
                            <h4 class="font-semibold text-xl text-gray-800 mb-3">Sección {{ $loop->iteration }}: {{ $section->title }}</h4>
                            @if ($section->description)
                                <p class="text-gray-600 text-sm mb-4">{{ $section->description }}</p>
                            @endif

                            @forelse ($section->questions as $question)
                                @php
                                    // Encontrar la respuesta específica para esta pregunta dentro de la colección de respuestas
                                    $userAnswer = $response->answers->where('question_id', $question->id)->first();
                                @endphp

                                <div class="question-block bg-white p-4 rounded-md shadow-sm mb-4 border border-gray-100">
                                    <p class="font-medium text-gray-700 mb-3"><strong>Q{{ $loop->iteration }}:</strong> {{ $question->text }}</p>

                                    <div class="mb-3">
                                        <p class="block font-medium text-sm text-gray-700">Tu Respuesta:</p>
                                        @if ($question->type === 'text')
                                            <p class="text-gray-800 mt-1 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $userAnswer->answer_text ?? 'No respondido' }}</p>
                                        @elseif ($question->type === 'multiple_choice')
                                            <ul class="list-disc list-inside ml-4 text-sm text-gray-800 mt-1">
                                                @foreach ($question->options as $option)
                                                    <li class="{{ ($userAnswer && $userAnswer->selected_option_id == $option->id) ? 'font-bold text-indigo-700' : 'text-gray-700' }}">
                                                        {{ $option->option_text }}
                                                        @if ($userAnswer && $userAnswer->selected_option_id == $option->id)
                                                            <i class="fas fa-check-circle text-indigo-600 ml-2"></i>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @if (!$userAnswer)
                                                <p class="text-gray-500 mt-1">No respondido</p>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Campo de Observaciones -->
                                    @if ($question->observations_enabled)
                                        <div class="mb-3">
                                            <p class="block font-medium text-sm text-gray-700">Observaciones:</p>
                                            <p class="text-gray-800 mt-1 p-2 border border-gray-200 rounded-md bg-gray-50">{{ $userAnswer->observations ?? 'Ninguna' }}</p>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Esta sección no tenía preguntas.</p>
                            @endforelse
                        </div>
                    @empty
                        <p class="text-gray-500">Este cuestionario no tenía secciones ni preguntas.</p>
                    @endforelse

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.responses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                            {{ __('Volver a la Lista de Respuestas') }}
                        </a>
                        <a href="{{ route('admin.responses.export.pdf', $response) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Exportar a PDF') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>