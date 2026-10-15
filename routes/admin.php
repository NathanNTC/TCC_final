<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/admin', [
        AdminController::class,
        'index',
    ])->name('admin.index');

    Route::post('/admin/convites', [
        AdminController::class,
        'storeConvite',
    ])->name('admin.convites.store');
});

