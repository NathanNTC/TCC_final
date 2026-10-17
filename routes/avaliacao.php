
<?php

use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AvaliacaoEficienciaIAController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Avaliação geral da eficiência da IA.
   
    Route::get('/avaliacao/eficiencia', [
        AvaliacaoEficienciaIAController::class,
        'index',
    ])->name('avaliacao.eficiencia');

    Route::post('/avaliacao/eficiencia', [
        AvaliacaoEficienciaIAController::class,
        'store',
    ])->name('avaliacao.eficiencia.store');

    // Avaliação individual das triagens.
    Route::get('/avaliacao', [
        AvaliacaoController::class,
        'index',
    ])->name('avaliacao.index');

    Route::post('/avaliacao/{triagem}', [
        AvaliacaoController::class,
        'store',
    ])->name('avaliacao.store');
});
