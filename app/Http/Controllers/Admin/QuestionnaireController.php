<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\Section; // Importar el modelo Section
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of the questionnaires.
     */
    public function index(): View
    {
        $questionnaires = Questionnaire::with('creator')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.questionnaires.index', compact('questionnaires'));
    }

    /**
     * Show the form for creating a new questionnaire.
     */
    public function create(): View
    {
        return view('admin.questionnaires.create');
    }

    /**
     * Store a newly created questionnaire in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'sections' => ['required', 'array', 'min:1'], // Ahora validamos secciones
            'sections.*.title' => ['required', 'string', 'max:255'],
            'sections.*.description' => ['nullable', 'string'],
            'sections.*.questions' => ['required', 'array', 'min:1'], // Preguntas dentro de secciones
            'sections.*.questions.*.text' => ['required', 'string', 'max:500'],
            'sections.*.questions.*.type' => ['required', Rule::in(['multiple_choice', 'text'])],
            'sections.*.questions.*.options' => ['nullable', 'array'], // Opciones solo para multiple_choice
            'sections.*.questions.*.options.*.option_text' => ['required_if:sections.*.questions.*.type,multiple_choice', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request) {
            $questionnaire = Questionnaire::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->sections as $sIndex => $sectionData) {
                $section = $questionnaire->sections()->create([
                    'title' => $sectionData['title'],
                    'description' => $sectionData['description'],
                    'order' => $sIndex,
                ]);

                foreach ($sectionData['questions'] as $qIndex => $questionData) {
                    $question = $section->questions()->create([ // Asociar pregunta a sección
                        'text' => $questionData['text'],
                        'type' => $questionData['type'],
                        'order' => $qIndex,
                        'observations_enabled' => true,
                    ]);

                    if ($questionData['type'] === 'multiple_choice' && isset($questionData['options'])) {
                        foreach ($questionData['options'] as $optionIndex => $optionData) {
                            $question->options()->create([
                                'option_text' => $optionData['option_text'],
                                'order' => $optionIndex,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('admin.questionnaires.index')->with('success', 'Cuestionario creado exitosamente.');
    }

    /**
     * Display the specified questionnaire.
     */
    public function show(Questionnaire $questionnaire): View
    {
        // Carga las secciones, sus preguntas y las opciones de las preguntas
        $questionnaire->load('sections.questions.options');
        return view('admin.questionnaires.show', compact('questionnaire'));
    }

    /**
     * Show the form for editing the specified questionnaire.
     */
    public function edit(Questionnaire $questionnaire): View
    {
        // Carga las secciones, sus preguntas y las opciones de las preguntas para el formulario de edición
        $questionnaire->load('sections.questions.options');
        return view('admin.questionnaires.edit', compact('questionnaire'));
    }

    /**
     * Update the specified questionnaire in storage.
     */
    public function update(Request $request, Questionnaire $questionnaire): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'sections' => ['required', 'array', 'min:1'],
            'sections.*.id' => ['nullable', 'exists:sections,id'], // Para secciones existentes
            'sections.*.title' => ['required', 'string', 'max:255'],
            'sections.*.description' => ['nullable', 'string'],
            'sections.*.questions' => ['required', 'array', 'min:1'],
            'sections.*.questions.*.id' => ['nullable', 'exists:questions,id'], // Para preguntas existentes
            'sections.*.questions.*.text' => ['required', 'string', 'max:500'],
            'sections.*.questions.*.type' => ['required', Rule::in(['multiple_choice', 'text'])],
            'sections.*.questions.*.options' => ['nullable', 'array'],
            'sections.*.questions.*.options.*.id' => ['nullable', 'exists:question_options,id'], // Para opciones existentes
            'sections.*.questions.*.options.*.option_text' => ['required_if:sections.*.questions.*.type,multiple_choice', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $questionnaire) {
            $questionnaire->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Sincronizar secciones
            $existingSectionIds = $questionnaire->sections->pluck('id')->toArray();
            $updatedSectionIds = [];

            foreach ($request->sections as $sIndex => $sectionData) {
                if (isset($sectionData['id'])) {
                    // Actualizar sección existente
                    $section = Section::find($sectionData['id']);
                    $section->update([
                        'title' => $sectionData['title'],
                        'description' => $sectionData['description'],
                        'order' => $sIndex,
                    ]);
                    $updatedSectionIds[] = $section->id;
                } else {
                    // Crear nueva sección
                    $section = $questionnaire->sections()->create([
                        'title' => $sectionData['title'],
                        'description' => $sectionData['description'],
                        'order' => $sIndex,
                    ]);
                }

                // Sincronizar preguntas dentro de la sección
                $existingQuestionIds = $section->questions->pluck('id')->toArray();
                $updatedQuestionIds = [];

                foreach ($sectionData['questions'] as $qIndex => $questionData) {
                    if (isset($questionData['id'])) {
                        // Actualizar pregunta existente
                        $question = Question::find($questionData['id']);
                        $question->update([
                            'text' => $questionData['text'],
                            'type' => $questionData['type'],
                            'order' => $qIndex,
                            'observations_enabled' => true,
                        ]);
                        $updatedQuestionIds[] = $question->id;
                    } else {
                        // Crear nueva pregunta
                        $question = $section->questions()->create([
                            'text' => $questionData['text'],
                            'type' => $questionData['type'],
                            'order' => $qIndex,
                            'observations_enabled' => true,
                        ]);
                    }

                    // Manejar opciones de respuesta
                    if ($questionData['type'] === 'multiple_choice') {
                        $existingOptionIds = $question->options->pluck('id')->toArray();
                        $updatedOptionIds = [];

                        if (isset($questionData['options'])) {
                            foreach ($questionData['options'] as $optionIndex => $optionData) {
                                if (isset($optionData['id'])) {
                                    // Actualizar opción existente
                                    $option = QuestionOption::find($optionData['id']);
                                    $option->update([
                                        'option_text' => $optionData['option_text'],
                                        'order' => $optionIndex,
                                    ]);
                                    $updatedOptionIds[] = $option->id;
                                } else {
                                    // Crear nueva opción
                                    $question->options()->create([
                                        'option_text' => $optionData['option_text'],
                                        'order' => $optionIndex,
                                    ]);
                                }
                            }
                        }
                        // Eliminar opciones que ya no están
                        QuestionOption::where('question_id', $question->id)
                                      ->whereNotIn('id', $updatedOptionIds)
                                      ->delete();
                    } else {
                        // Si el tipo de pregunta cambia a texto, eliminar todas sus opciones
                        $question->options()->delete();
                    }
                }

                // Eliminar preguntas que ya no están en el request para esta sección
                Question::where('section_id', $section->id) // Cambiado de questionnaire_id a section_id
                        ->whereNotIn('id', $updatedQuestionIds)
                        ->delete();
            }

            // Eliminar secciones que ya no están en el request
            Section::where('questionnaire_id', $questionnaire->id)
                   ->whereNotIn('id', $updatedSectionIds)
                   ->delete();
        });

        return redirect()->route('admin.questionnaires.index')->with('success', 'Cuestionario actualizado exitosamente.');
    }

    /**
     * Remove the specified questionnaire from storage.
     */
    public function destroy(Questionnaire $questionnaire): RedirectResponse
    {
        $questionnaire->delete();
        return redirect()->route('admin.questionnaires.index')->with('success', 'Cuestionario eliminado exitosamente.');
    }
}
