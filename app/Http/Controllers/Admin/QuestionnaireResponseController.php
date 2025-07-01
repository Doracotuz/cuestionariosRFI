<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireResponse;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\RedirectResponse; // Importar para el método destroy

class QuestionnaireResponseController extends Controller
{
    /**
     * Display a listing of all submitted questionnaire responses.
     */
    public function index(): View
    {
        $responses = QuestionnaireResponse::whereNotNull('submitted_at')
                                        ->with(['questionnaire', 'user'])
                                        ->orderBy('submitted_at', 'desc')
                                        ->paginate(10);

        return view('admin.responses.index', compact('responses'));
    }

    /**
     * Display the specified questionnaire response.
     */
    public function show(QuestionnaireResponse $response): View
    {
        $response->load([
            'questionnaire.sections.questions.options',
            'user',
            'answers.question.options',
            'answers.selectedOptions' // Usar selectedOptions
        ]);

        return view('admin.responses.show', compact('response'));
    }

    /**
     * Export all submitted questionnaire responses to Excel (CSV format).
     * One questionnaire response per line.
     */
    public function exportExcel(): StreamedResponse
    {
        $responses = QuestionnaireResponse::whereNotNull('submitted_at')
                                        ->with(['questionnaire.sections.questions', 'user', 'answers.question', 'answers.selectedOptions']) // Usar selectedOptions
                                        ->orderBy('submitted_at', 'asc')
                                        ->get();

        $filename = 'respuestas_cuestionarios_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($responses) {
            $file = fopen('php://output', 'w');

            // Obtener todas las preguntas posibles de todos los cuestionarios para los encabezados dinámicos
            // Esto es crucial para un CSV plano donde cada columna de pregunta es única.
            $allQuestions = Question::select('id', 'text', 'type')
                                    ->with('section.questionnaire')
                                    ->get()
                                    ->keyBy('id');

            // Construir encabezados dinámicamente
            $headerRow = [
                'ID Respuesta',
                'Título Cuestionario',
                'Usuario',
                'Email Usuario',
                'Fecha Envío',
            ];

            foreach ($allQuestions as $question) {
                $headerRow[] = 'P' . $question->id . ': ' . $question->text; // Pregunta
                $headerRow[] = 'Obs. P' . $question->id; // Observaciones de la pregunta
            }

            fputcsv($file, $headerRow);

            // Escribir datos
            foreach ($responses as $response) {
                $dataRow = [
                    $response->id,
                    $response->questionnaire->title ?? 'N/A',
                    $response->user->name ?? 'N/A',
                    $response->user->email ?? 'N/A',
                    $response->submitted_at->format('d/m/Y H:i'),
                ];

                $answersMap = $response->answers->keyBy('question_id');

                foreach ($allQuestions as $question) {
                    $answer = $answersMap->get($question->id);
                    $answerValue = '';
                    $observations = '';

                    if ($answer) {
                        if ($question->type === 'multiple_choice' && $answer->selectedOptions->isNotEmpty()) {
                            $answerValue = $answer->selectedOptions->pluck('option_text')->implode(', '); // Unir opciones con coma
                        } else {
                            $answerValue = $answer->answer_text;
                        }
                        $observations = $answer->observations ?? '';
                    }
                    $dataRow[] = $answerValue;
                    $dataRow[] = $observations;
                }
                fputcsv($file, $dataRow);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export a specific questionnaire response to PDF.
     */
    public function exportPdf(QuestionnaireResponse $questionnaireResponse): StreamedResponse
    {
        // Re-obtener la respuesta con todas las relaciones necesarias
        $questionnaireResponse = QuestionnaireResponse::with([
            'questionnaire.sections.questions.options',
            'user',
            'answers.question.options',
            'answers.selectedOptions' // Usar selectedOptions
        ])->findOrFail($questionnaireResponse->id);

        // Obtener el logo en base64 para incrustarlo en el PDF
        $logoPath = public_path('images/LogoAzul.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Generar el HTML para el PDF
        $html = view('admin.responses.pdf', compact('questionnaireResponse', 'logoBase64'))->render();

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'reporte_cuestionario_' . $questionnaireResponse->id . '_' . now()->format('Ymd_His') . '.pdf';

        // Retornar StreamedResponse para descarga directa
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Remove the specified questionnaire response from storage.
     */
    public function destroy(QuestionnaireResponse $response): RedirectResponse
    {
        $response->delete();
        return redirect()->route('admin.responses.index')->with('success', 'Respuesta eliminada exitosamente.');
    }
}
