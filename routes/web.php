<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $user = $request->user();

    if ($user->hasRole('Administrador')) {
        return redirect('/admin');
    }

    if ($user->hasAnyRole(['Medico', 'Estudante', 'Residente'])) {
        return redirect('/avaliacao');
    }

    if ($user->hasRole('Paciente')) {
        return redirect('/dashboard');
    }

    auth()->logout();

    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', [
    DashboardController::class,
    'index',
])->middleware(['auth', 'verified', 'role:Paciente'])
  ->name('dashboard');

// Rotas organizadas por módulo.
require __DIR__ . '/perfil.php';
require __DIR__ . '/triagens.php';
require __DIR__ . '/avaliacao.php';
require __DIR__ . '/admin.php';

// Login, cadastro, logout e recuperação de senha.
require __DIR__ . '/auth.php';
