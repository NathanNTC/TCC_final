<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard')->name('home');

Route::get('/dashboard', [
    DashboardController::class,
    'index',
])->middleware(['auth', 'verified'])->name('dashboard');

// Rotas organizadas por módulo.
require __DIR__ . '/perfil.php';
require __DIR__ . '/triagens.php';
require __DIR__ . '/avaliacao.php';
require __DIR__ . '/admin.php';

// Login, cadastro, logout e recuperação de senha.
require __DIR__ . '/auth.php';

