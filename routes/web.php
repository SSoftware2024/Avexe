<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardPageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [CustomerController::class, 'index'])->name('index');

Route::prefix('customer')->name('customer.')->group(function () {
    //rotas
    Route::get('/appoint', [CustomerController::class, 'appointmentsView'])->name('appoint');
});
Route::get('/home', [DashboardPageController::class, 'index'])->name('home');
