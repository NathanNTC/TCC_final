<?php

use App\Http\Controllers\TriagemController;
use App\Http\Controllers\TriagemMensagemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;

Route::middleware('auth')->group(function () {

    Route::get('/triagens', [
        TriagemController::class,
        'index',
    ])->name('triagens.index');

    Route::get('/triagens/nova', [
        TriagemController::class,
        'create',
    ])->name('triagens.create');

    Route::post('/triagens', [
        TriagemController::class,
        'store',
    ])->name('triagens.store');

    Route::post('/triagens/{triagem}/mensagens', [
        TriagemMensagemController::class,
        'store',
    ])->name('triagens.mensagens.store');

    Route::get('/triagens/{triagem}', [
        TriagemController::class,
        'show',
    ])->name('triagens.show');

    Route::get(
    '/triagens/{triagem}/feedback',
    [FeedbackController::class, 'create']
    )->middleware('role:Paciente')
    ->name('feedback.create');

    Route::post(
        '/triagens/{triagem}/feedback',
        [FeedbackController::class, 'store']
    )->middleware('role:Paciente')
    ->name('feedback.store');
});

