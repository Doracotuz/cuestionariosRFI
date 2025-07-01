<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuestionnaireController;
use App\Http\Controllers\Admin\QuestionnaireAssignmentController;
use App\Http\Controllers\Admin\QuestionnaireResponseController; // Asegúrate de importar este
use App\Http\Controllers\User\UserQuestionnaireController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirige la ruta raíz al login si no está autenticado
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.questionnaires.index');
        }
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas por el middleware 'auth' (cualquier usuario autenticado)
Route::middleware(['auth'])->group(function () {
    // La ruta /dashboard ahora es principalmente para administradores o un fallback general.
    // Los usuarios regulares serán redirigidos a cuestionarios directamente desde el login.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Rutas solo para administradores
Route::middleware(['auth', 'admin'])->group(function () {
    // La ruta /admin/dashboard ahora apunta al controlador de Dashboard para administradores
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Rutas para la gestión de usuarios
    Route::prefix('admin/users')->name('admin.users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Rutas para la gestión de Cuestionarios
    Route::prefix('admin/questionnaires')->name('admin.questionnaires.')->group(function () {
        Route::get('/', [QuestionnaireController::class, 'index'])->name('index');
        Route::get('/create', [QuestionnaireController::class, 'create'])->name('create');
        Route::post('/', [QuestionnaireController::class, 'store'])->name('store');
        Route::get('/{questionnaire}', [QuestionnaireController::class, 'show'])->name('show');
        Route::get('/{questionnaire}/edit', [QuestionnaireController::class, 'edit'])->name('edit');
        Route::patch('/{questionnaire}', [QuestionnaireController::class, 'update'])->name('update');
        Route::delete('/{questionnaire}', [QuestionnaireController::class, 'destroy'])->name('destroy');
    });

    // Rutas para la gestión de Asignaciones de Cuestionarios
    Route::prefix('admin/assignments')->name('admin.assignments.')->group(function () {
        Route::get('/', [QuestionnaireAssignmentController::class, 'index'])->name('index');
        Route::get('/create', [QuestionnaireAssignmentController::class, 'create'])->name('create');
        Route::post('/', [QuestionnaireAssignmentController::class, 'store'])->name('store');
        Route::delete('/{assignment}', [QuestionnaireAssignmentController::class, 'destroy'])->name('destroy');
    });

    // Rutas para la visualización y exportación de Respuestas de Cuestionarios
    // ¡IMPORTANTE: Coloca las rutas más específicas (con más segmentos) primero!
    Route::prefix('admin/responses')->name('admin.responses.')->group(function () {
        // Ruta para exportar PDF de una respuesta específica (más específica)
        Route::get('/{questionnaireResponse}/export/pdf', [QuestionnaireResponseController::class, 'exportPdf'])->name('export.pdf');
        // Ruta para exportar todo a Excel (también específica)
        Route::get('/export/excel', [QuestionnaireResponseController::class, 'exportExcel'])->name('export.excel');
        // Ruta para mostrar los detalles de una respuesta (más general, va al final de este grupo)
        Route::get('/{response}', [QuestionnaireResponseController::class, 'show'])->name('show');
        // Ruta para eliminar una respuesta (más específica que el index, pero menos que show/export)
        Route::delete('/{response}', [QuestionnaireResponseController::class, 'destroy'])->name('destroy'); // ¡NUEVO!
        // Ruta para listar todas las respuestas (la más general de todas, va al final)
        Route::get('/', [QuestionnaireResponseController::class, 'index'])->name('index');
    });
});

// Rutas para usuarios (incluye administradores por el UserMiddleware)
Route::middleware(['auth', 'user'])->group(function () {
    // Eliminado el dashboard de usuario si no es necesario
    // Route::get('/user/dashboard', function () {
    //     return view('user.dashboard');
    // })->name('user.dashboard');

    // Rutas para que los usuarios respondan cuestionarios
    Route::prefix('user/questionnaires')->name('user.questionnaires.')->group(function () {
        Route::get('/', [UserQuestionnaireController::class, 'index'])->name('index');
        Route::get('/{questionnaire}', [UserQuestionnaireController::class, 'show'])->name('show');
        Route::post('/{questionnaire}/submit', [UserQuestionnaireController::class, 'submit'])->name('submit');
    });
});

require __DIR__.'/auth.php';
