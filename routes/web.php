<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth; // Asegúrate de que esta línea esté presente

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
        // Si el usuario ya está autenticado, redirige a su dashboard correspondiente
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
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
    // La ruta /dashboard ahora es más general o un fallback
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Rutas solo para administradores
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Rutas para la gestión de usuarios
    Route::prefix('admin/users')->name('admin.users.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
    });

    // Rutas para la gestión de Cuestionarios
    Route::prefix('admin/questionnaires')->name('admin.questionnaires.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\QuestionnaireController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\QuestionnaireController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\QuestionnaireController::class, 'store'])->name('store');
        Route::get('/{questionnaire}', [\App\Http\Controllers\Admin\QuestionnaireController::class, 'show'])->name('show');
        Route::get('/{questionnaire}/edit', [\App\Http\Controllers\Admin\QuestionnaireController::class, 'edit'])->name('edit');
        Route::patch('/{questionnaire}', [\App\Http\Controllers\Admin\QuestionnaireController::class, 'update'])->name('update');
        Route::delete('/{questionnaire}', [\App\Http\Controllers\Admin\QuestionnaireController::class, 'destroy'])->name('destroy');
    });
});

// Rutas para usuarios (incluye administradores por el UserMiddleware)
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    // Agrega más rutas de usuario aquí
});


require __DIR__.'/auth.php';

