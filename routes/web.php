<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PrintRegisterController;
use App\Http\Controllers\LinkController;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/admin/minister-public-registers/print', [PrintRegisterController::class, 'print'])
    ->name('print.minister_public_registers');

// Links resource
Route::get('/links', [LinkController::class, 'index'])->name('links.index');
Route::get('/links/list', [LinkController::class, 'list'])->name('links.list');
Route::post('/links', [LinkController::class, 'store'])->name('links.store');
Route::put('/links/{link}', [LinkController::class, 'update'])->name('links.update');
Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
