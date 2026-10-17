
<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminEficienciaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    /*
     * PAINEL ADMINISTRATIVO PRINCIPAL.
     */
    Route::get('/admin', [
        AdminController::class,
        'index',
    ])->name('admin.index');

    /*
     * INDICADORES DE EFICIÊNCIA DA IA.
     */
    Route::get('/admin/eficiencia', [
        AdminEficienciaController::class,
        'index',
    ])->name('admin.eficiencia');

    /*
     * GERENCIAMENTO DE CONVITES.
     */
    Route::post('/admin/convites', [
        AdminController::class,
        'storeConvite',
    ])->name('admin.convites.store');
});
