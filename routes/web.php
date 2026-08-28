<?php

use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Perfil de Saúde
    |--------------------------------------------------------------------------
    */

    // Visualizar perfil completo
    Route::get('/perfil', [PerfilController::class, 'index'])
        ->name('perfil.index');

    // Criar/salvar perfil completo
    Route::post('/perfil', [PerfilController::class, 'store'])
        ->name('perfil.store');

    // Abrir edição de uma seção
    Route::get('/perfil/editar/{secao}', [PerfilController::class, 'editar'])
        ->name('perfil.editar');

    // Atualizar uma seção
    Route::put('/perfil/editar/{secao}', [PerfilController::class, 'atualizarSecao'])
        ->name('perfil.atualizar');


    /*
    |--------------------------------------------------------------------------
    | Perfil da conta
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__.'/auth.php';