<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use App\Models\AnswerSubmission;
use App\Models\QuestionnaireAssignment;
use App\Models\User; // Importar el modelo User para obtener administradores
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Importar el facade Rule
use Illuminate\Support\Facades\Mail; // Importar el facade Mail
use Dompdf\Dompdf; // Para PDF
use Dompdf\Options; // Para PDF
use App\Mail\QuestionnaireSubmittedNotification; // Importar el Mailable

class UserQuestionnaireController extends Controller
{
    /**
     * Display a listing of assigned questionnaires for the user.
     */
    public function index(): View
    {
        // Obtener solo las asignaciones para el usuario actual
        $assignments = QuestionnaireAssignment::where('user_id', Auth::id())
                                            ->with('questionnaire') // Cargar el cuestionario relacionado
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(10);

        // Para cada asignación, verificar si el cuestionario ya ha sido respondido
        foreach ($assignments as $assignment) {
            // Un cuestionario se considera respondido si la asignación tiene status 'completed'
            // o si existe una respuesta con submitted_at no nulo.
            // La lógica de asignación actualizará el status a 'completed' al enviar.
            $assignment->is_completed = ($assignment->status === 'completed');
        }

        return view('user.questionnaires.index', compact('assignments'));
    }

    /**
     * Show the form for responding to a specific questionnaire.
     */
    public function show(Questionnaire $questionnaire): View | RedirectResponse
    {
        // Asegurarse de que el cuestionario esté publicado
        if ($questionnaire->status !== 'published') {
            return redirect()->route('user.questionnaires.index')->with('error', 'Este cuestionario no está disponible para responder.');
        }

        // Verificar si el cuestionario está asignado al usuario actual
        $assignment = QuestionnaireAssignment::where('questionnaire_id', $questionnaire->id)
                                            ->where('user_id', Auth::id())
                                            ->first();

        if (!$assignment) {
            return redirect()->route('user.questionnaires.index')->with('error', 'Este cuestionario no te ha sido asignado.');
        }

        // Verificar si la asignación ya ha sido completada
        if ($assignment->status === 'completed') {
            return redirect()->route('user.questionnaires.index')->with('info', 'Ya has completado este cuestionario.');
        }

        // Cargar secciones, preguntas y opciones
        $questionnaire->load('sections.questions.options');

        // Obtener o crear una respuesta en progreso (submitted_at es null)
        $response = QuestionnaireResponse::firstOrCreate(
            ['questionnaire_id' => $questionnaire->id, 'user_id' => Auth::id(), 'submitted_at' => null],
            ['submitted_at' => null]
        );

        // Cargar las opciones seleccionadas para la edición de respuestas múltiples
        $response->load('answers.selectedOptions');

        return view('user.questionnaires.show', compact('questionnaire', 'response', 'assignment'));
    }

    /**
     * Store the user's answers for a questionnaire.
     */
    public function submit(Request $request, Questionnaire $questionnaire): RedirectResponse
    {
        // Verificar si el cuestionario está publicado
        if ($questionnaire->status !== 'published') {
            return redirect()->route('user.questionnaires.index')->with('error', 'Este cuestionario no está disponible para responder.');
        }

        // Verificar si el cuestionario está asignado al usuario actual
        $assignment = QuestionnaireAssignment::where('questionnaire_id', $questionnaire->id)
                                            ->where('user_id', Auth::id())
                                            ->first();

        if (!$assignment) {
            return redirect()->route('user.questionnaires.index')->with('error', 'Este cuestionario no te ha sido asignado.');
        }

        // Verificar si la asignación ya ha sido completada
        if ($assignment->status === 'completed') {
            return redirect()->route('user.questionnaires.index')->with('info', 'Ya has completado este cuestionario.');
        }

        // Cargar las preguntas para la validación dinámica
        $questionnaire->load('sections.questions.options');

        $rules = [];
        $messages = [];

        foreach ($questionnaire->sections as $section) {
            foreach ($section->questions as $question) {
                if ($question->type === 'multiple_choice') {
                    // Validar que sea un array y que cada ID exista y pertenezca a la pregunta
                    $rules["answers.{$question->id}.value"] = ['required', 'array', 'min:1']; // Debe ser un array con al menos 1 elemento
                    $rules["answers.{$question->id}.value.*"] = [
                        'integer',
                        Rule::exists('question_options', 'id')->where(function ($query) use ($question) {
                            return $query->where('question_id', $question->id);
                        }),
                    ];
                    $messages["answers.{$question->id}.value.required"] = "Debes seleccionar al menos una opción para la pregunta '{$question->text}'.";
                    $messages["answers.{$question->id}.value.array"] = "La respuesta para la pregunta '{$question->text}' debe ser una selección.";
                    $messages["answers.{$question->id}.value.min"] = "Debes seleccionar al menos una opción para la pregunta '{$question->text}'.";
                    $messages["answers.{$question->id}.value.*.integer"] = "La opción seleccionada para la pregunta '{$question->text}' no es válida.";
                    $messages["answers.{$question->id}.value.*.exists"] = "Una de las opciones seleccionadas para la pregunta '{$question->text}' no es válida.";
                } else {
                    $rules["answers.{$question->id}.value"] = ['required', 'string'];
                    $messages["answers.{$question->id}.value.required"] = "La pregunta '{$question->text}' es obligatoria.";
                }

                if ($question->observations_enabled) {
                    $rules["answers.{$question->id}.observations"] = ['nullable', 'string', 'max:1000'];
                }
            }
        }

        $request->validate($rules, $messages);

        DB::transaction(function () use ($request, $questionnaire, $assignment) {
            $questionnaireResponse = QuestionnaireResponse::firstOrCreate(
                ['questionnaire_id' => $questionnaire->id, 'user_id' => Auth::id(), 'submitted_at' => null]
            );

            // Eliminar respuestas anteriores para esta sesión de respuesta (si es un borrador que se está actualizando)
            // Esto incluye las entradas en answer_submission_options a través de la relación
            $questionnaireResponse->answers()->delete();

            foreach ($questionnaire->sections as $section) {
                foreach ($section->questions as $question) {
                    $answerData = $request->input("answers.{$question->id}");

                    // Crear la entrada principal de AnswerSubmission
                    $answerSubmission = AnswerSubmission::create([
                        'questionnaire_response_id' => $questionnaireResponse->id,
                        'question_id' => $question->id,
                        'answer_text' => ($question->type === 'text') ? $answerData['value'] : null, // answer_text solo para preguntas de texto
                        'observations' => $answerData['observations'] ?? null,
                    ]);

                    // Si es selección múltiple, adjuntar las opciones seleccionadas
                    if ($question->type === 'multiple_choice' && isset($answerData['value']) && is_array($answerData['value'])) {
                        $answerSubmission->selectedOptions()->sync($answerData['value']);
                    }
                }
            }

            $questionnaireResponse->update(['submitted_at' => now()]);

            $assignment->update([
                'status' => 'completed',
                'questionnaire_response_id' => $questionnaireResponse->id,
            ]);

            // --- Lógica para generar PDF y enviar correo ---
            $questionnaireResponse->load([
                'questionnaire.sections.questions.options',
                'user',
                'answers.question.options',
                'answers.selectedOptions' // Cargar las opciones seleccionadas para el PDF
            ]);

            $logoPath = public_path('images/logoBlanco.png');
            $logoBase64 = null;
            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

            $html = view('admin.responses.pdf', compact('questionnaireResponse', 'logoBase64'))->render();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfContent = $dompdf->output();

            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();

            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new QuestionnaireSubmittedNotification($questionnaireResponse, $pdfContent));
            }
            // --- Fin lógica para generar PDF y enviar correo ---
        });

        return redirect()->route('user.questionnaires.index')->with('success', '¡Cuestionario enviado exitosamente!');
    }
}
