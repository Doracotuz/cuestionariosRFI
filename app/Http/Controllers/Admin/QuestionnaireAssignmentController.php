<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireAssignment;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class QuestionnaireAssignmentController extends Controller
{
    /**
     * Display a listing of the questionnaire assignments.
     */
    public function index(): View
    {
        $assignments = QuestionnaireAssignment::with(['questionnaire', 'user'])
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(10);
        return view('admin.assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(): View
    {
        // Obtener solo cuestionarios publicados para asignar
        $questionnaires = Questionnaire::where('status', 'published')->orderBy('title')->get();
        // Obtener solo usuarios con rol 'user' para asignar
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.assignments.create', compact('questionnaires', 'users'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'questionnaire_id' => [
                'required',
                'exists:questionnaires,id',
                // Asegura que el cuestionario esté publicado antes de asignar
                Rule::exists('questionnaires', 'id')->where(function ($query) {
                    return $query->where('status', 'published');
                }),
            ],
            'user_id' => [
                'required',
                'exists:users,id',
                // Asegura que el usuario tenga rol 'user'
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role', 'user');
                }),
                // Asegura que no haya una asignación duplicada para el mismo cuestionario y usuario
                Rule::unique('questionnaire_assignments')->where(function ($query) use ($request) {
                    return $query->where('questionnaire_id', $request->questionnaire_id);
                }),
            ],
        ], [
            'questionnaire_id.required' => 'El cuestionario es obligatorio.',
            'questionnaire_id.exists' => 'El cuestionario seleccionado no es válido o no está publicado.',
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no es válido o no tiene el rol correcto.',
            'user_id.unique' => 'Este cuestionario ya ha sido asignado a este usuario.',
        ]);

        QuestionnaireAssignment::create([
            'questionnaire_id' => $request->questionnaire_id,
            'user_id' => $request->user_id,
            'status' => 'assigned', // Por defecto 'assigned'
        ]);

        return redirect()->route('admin.assignments.index')->with('success', 'Asignación creada exitosamente.');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(QuestionnaireAssignment $assignment): RedirectResponse
    {
        $assignment->delete();
        return redirect()->route('admin.assignments.index')->with('success', 'Asignación eliminada exitosamente.');
    }
}
