<?php

use App\Http\Controllers\Perfil\AlergiasController;
use App\Http\Controllers\Perfil\CirurgiasController;
use App\Http\Controllers\Perfil\DadosPessoaisController;
use App\Http\Controllers\Perfil\DeficienciasController;
use App\Http\Controllers\Perfil\HabitosController;
use App\Http\Controllers\Perfil\HistoricoFamiliarController;
use App\Http\Controllers\Perfil\MedicamentosController;
use App\Http\Controllers\Perfil\PerfilController;
use App\Http\Controllers\Perfil\ProblemasSaudeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [
    DashboardController::class,
    'index'
])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Perfil de Saúde
    |--------------------------------------------------------------------------
    */

    // Visualizar perfil completo
    Route::get('/perfil', [PerfilController::class, 'index'])
        ->name('perfil.index');

    // Salvar perfil inicialmente
    Route::post('/perfil', [PerfilController::class, 'store'])
        ->name('perfil.store');

    Route::patch('/perfil/tipo-sanguineo', [
    PerfilController::class,
    'atualizarTipoSanguineo'
    ])->name('perfil.atualizar.tipo-sanguineo');
    /*
    |--------------------------------------------------------------------------
    | Edição das seções
    |--------------------------------------------------------------------------
    */

    Route::get('/perfil/editar/dados-pessoais', [
        DadosPessoaisController::class,
        'edit'
    ])->name('perfil.editar.dados-pessoais');

    Route::put('/perfil/editar/dados-pessoais', [
        DadosPessoaisController::class,
        'update'
    ])->name('perfil.atualizar.dados-pessoais');


    Route::get('/perfil/editar/deficiencias', [
        DeficienciasController::class,
        'edit'
    ])->name('perfil.editar.deficiencias');

    Route::put('/perfil/editar/deficiencias', [
        DeficienciasController::class,
        'update'
    ])->name('perfil.atualizar.deficiencias');


    Route::get('/perfil/editar/problemas-saude', [
        ProblemasSaudeController::class,
        'edit'
    ])->name('perfil.editar.problemas-saude');

    Route::put('/perfil/editar/problemas-saude', [
        ProblemasSaudeController::class,
        'update'
    ])->name('perfil.atualizar.problemas-saude');


    Route::get('/perfil/editar/historico-familiar', [
        HistoricoFamiliarController::class,
        'edit'
    ])->name('perfil.editar.historico-familiar');

    Route::put('/perfil/editar/historico-familiar', [
        HistoricoFamiliarController::class,
        'update'
    ])->name('perfil.atualizar.historico-familiar');


    Route::get('/perfil/editar/alergias', [
        AlergiasController::class,
        'edit'
    ])->name('perfil.editar.alergias');

    Route::put('/perfil/editar/alergias', [
        AlergiasController::class,
        'update'
    ])->name('perfil.atualizar.alergias');


    Route::get('/perfil/editar/medicamentos', [
        MedicamentosController::class,
        'edit'
    ])->name('perfil.editar.medicamentos');

    Route::put('/perfil/editar/medicamentos', [
        MedicamentosController::class,
        'update'
    ])->name('perfil.atualizar.medicamentos');


    Route::get('/perfil/editar/cirurgias', [
        CirurgiasController::class,
        'edit'
    ])->name('perfil.editar.cirurgias');

    Route::put('/perfil/editar/cirurgias', [
        CirurgiasController::class,
        'update'
    ])->name('perfil.atualizar.cirurgias');


    Route::get('/perfil/editar/habitos', [
        HabitosController::class,
        'edit'
    ])->name('perfil.editar.habitos');

    Route::put('/perfil/editar/habitos', [
        HabitosController::class,
        'update'
    ])->name('perfil.atualizar.habitos');


    /*
    |--------------------------------------------------------------------------
    | Perfil da conta
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');
});

require __DIR__.'/auth.php';