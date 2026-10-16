<?php

use App\Http\Controllers\AvaliacaoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/avaliacao', [
        AvaliacaoController::class,
        'index',
    ])->name('avaliacao.index');

    Route::post('/avaliacao/{triagem}', [
        AvaliacaoController::class,
        'store',
    ])->name('avaliacao.store');
});

