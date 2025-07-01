<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Cuestionario - {{ $questionnaireResponse->questionnaire->title ?? 'Cuestionario Desconocido' }}</title>
    <style>
        /* Definir la fuente Century Gothic */
        body {
            font-family: 'Century Gothic', 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
            font-size: 10pt;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }
        header img {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        header h1 {
            font-size: 20pt;
            color: #2c3e50;
            margin: 0;
        }
        .info-section {
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        .info-section p {
            margin: 5px 0;
            font-size: 10pt;
        }
        .info-section strong {
            color: #555;
        }
        .section-title {
            font-size: 14pt;
            color: #34495e;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .section-description {
            font-size: 9pt;
            color: #777;
            margin-bottom: 15px;
        }
        .question-block {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fdfdfd;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
        }
        .question-text {
            font-size: 11pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .answer-label {
            font-size: 9.5pt;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }
        .answer-content {
            font-size: 10pt;
            color: #333;
            background-color: #f5f5f5;
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #eee;
            word-wrap: break-word;
        }
        .options-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .options-list li {
            font-size: 10pt;
            margin-bottom: 5px;
            color: #555;
        }
        .options-list li.selected {
            font-weight: bold;
            color: #2980b9;
        }
        .observations-content {
            font-size: 10pt;
            color: #333;
            background-color: #f5f5f5;
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #eee;
            margin-top: 5px;
            word-wrap: break-word;
        }
        footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 8pt;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo">
            @else
                <h1>Cuestionarios RFI</h1>
            @endif
            <h1>Reporte de Cuestionario</h1>
        </header>

        <div class="info-section">
            <p><strong>Cuestionario:</strong> {{ $questionnaireResponse->questionnaire->title ?? 'N/A' }}</p>
            <p><strong>Descripción:</strong> {{ $questionnaireResponse->questionnaire->description ?? 'N/A' }}</p>
            <p><strong>Respondido por:</strong> {{ $questionnaireResponse->user->name ?? 'N/A' }} ({{ $questionnaireResponse->user->email ?? 'N/A' }})</p>
            <p><strong>Fecha de Envío:</strong> {{ $questionnaireResponse->submitted_at ? $questionnaireResponse->submitted_at->format('d/m/Y H:i') : 'N/A' }}</p95>
        </div>

        @php
            $answersMap = $questionnaireResponse->answers->keyBy('question_id');
        @endphp

        @forelse ($questionnaireResponse->questionnaire->sections ?? [] as $section)
            <h2 class="section-title">{{ $section->title ?? 'N/A' }}</h2>
            @if ($section->description)
                <p class="section-description">{{ $section->description ?? 'N/A' }}</p>
            @endif

            @forelse ($section->questions ?? [] as $question)
                @php
                    $userAnswer = $answersMap->get($question->id);
                @endphp

                <div class="question-block">
                    <p class="question-text">{{ $question->text ?? 'N/A' }}</p>

                    <div class="answer-section">
                        <span class="answer-label">Respuesta:</span>
                        @if ($question->type === 'text')
                            <p class="answer-content">{{ $userAnswer->answer_text ?? 'No respondido' }}</p>
                        @elseif ($question->type === 'multiple_choice')
                            @if ($userAnswer && $userAnswer->selectedOptions->isNotEmpty())
                                <ul class="options-list">
                                    @foreach ($userAnswer->selectedOptions as $selectedOption)
                                        <li class="selected"> {{ $selectedOption->option_text ?? 'N/A' }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="answer-content">No respondido</p>
                            @endif
                        @endif
                    </div>

                    @if ($question->observations_enabled && ($userAnswer->observations ?? null))
                        <div class="observations-section" style="margin-top: 15px;">
                            <span class="answer-label">Observaciones:</span>
                            <p class="observations-content">{{ $userAnswer->observations }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <p style="font-size: 9pt; color: #777;">Esta sección no tenía preguntas.</p>
            @endforelse
        @empty
            <p style="font-size: 9pt; color: #777;">Este cuestionario no tenía secciones ni preguntas.</p>
        @endforelse

        <footer>
            <p>© {{ date('Y') }} Estrategias y Soluciones Minmer Global. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>