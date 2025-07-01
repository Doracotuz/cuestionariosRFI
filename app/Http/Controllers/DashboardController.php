<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User; // Importar User
use App\Models\Questionnaire; // Importar Questionnaire
use App\Models\QuestionnaireAssignment; // Importar QuestionnaireAssignment
use App\Models\QuestionnaireResponse; // Importar QuestionnaireResponse

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Métricas de Usuarios
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $regularUsers = User::where('role', 'user')->count();

        // Métricas de Cuestionarios
        $totalQuestionnaires = Questionnaire::count();
        $questionnairesByStatus = Questionnaire::select('status', \DB::raw('count(*) as count'))
                                                ->groupBy('status')
                                                ->pluck('count', 'status')
                                                ->toArray();
        $draftQuestionnaires = $questionnairesByStatus['draft'] ?? 0;
        $publishedQuestionnaires = $questionnairesByStatus['published'] ?? 0;
        $archivedQuestionnaires = $questionnairesByStatus['archived'] ?? 0;

        // Métricas de Asignaciones
        $totalAssignments = QuestionnaireAssignment::count();
        $assignmentsByStatus = QuestionnaireAssignment::select('status', \DB::raw('count(*) as count'))
                                                    ->groupBy('status')
                                                    ->pluck('count', 'status')
                                                    ->toArray();
        $assignedAssignments = $assignmentsByStatus['assigned'] ?? 0;
        $completedAssignments = $assignmentsByStatus['completed'] ?? 0;

        // Métricas de Respuestas (solo las completadas/enviadas)
        $totalSubmittedResponses = QuestionnaireResponse::whereNotNull('submitted_at')->count();


        return view('admin.dashboard', compact(
            'totalUsers',
            'adminUsers',
            'regularUsers',
            'totalQuestionnaires',
            'draftQuestionnaires',
            'publishedQuestionnaires',
            'archivedQuestionnaires',
            'totalAssignments',
            'assignedAssignments',
            'completedAssignments',
            'totalSubmittedResponses'
        ));
    }
}
