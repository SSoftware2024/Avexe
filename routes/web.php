<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardPageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [CustomerController::class, 'index'])->name('index');

Route::prefix('customer')->name('customer.')->group(function () {
    //rotas
    Route::get('/appointmentsView', [CustomerController::class, 'appointmentsView'])->name('appointmentsView');
    Route::get('/profileView', [CustomerController::class, 'profileView'])->name('profileView');

    //login google
    Route::get('/loginGoogle', [CustomerController::class, 'loginGoogle'])->name('loginGoogle');
    Route::get('/loginGoogleCallback', [CustomerController::class, 'loginGoogleCallback'])->name('loginGoogleCallback');
});
Route::get('/home', [DashboardPageController::class, 'index'])->name('home');
