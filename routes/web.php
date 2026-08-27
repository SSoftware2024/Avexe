<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'index'])->name('index');

Route::delete('/removeProfile', [CustomerController::class, 'removeProfile'])->name('.removeProfile');
Route::get('/home', [AuthController::class, 'index'])->name('home');

// autenticação
Route::prefix('auth')->name('auth')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/profileView', [AuthController::class, 'profileView'])->name('.profileView');
    Route::delete('/removeProfile', [AuthController::class, 'removeProfile'])->name('.removeProfile');
});

// cliente
Route::prefix('customer')->name('customer')->group(function () {
    Route::get('/appointmentsView', [CustomerController::class, 'appointmentsView'])->name('.appointmentsView');

    // login google
    Route::get('/loginGoogle', [CustomerController::class, 'loginGoogle'])->name('.loginGoogle');
    Route::get('/loginGoogleCallback', [CustomerController::class, 'loginGoogleCallback'])->name('.loginGoogleCallback');
});

// dono
Route::prefix('owner')->name('owner')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index']);
});

// desenvolvedor
Route::prefix('developer')->name('developer')->group(function () {
    Route::get('/dashboard', [DeveloperController::class, 'index']);

    // operações owner
    Route::get('/owner/listView', [DeveloperController::class, 'ownerListView'])->name('.ownerListView');
    Route::get('/owner/createOrUpdate/{user?}', [DeveloperController::class, 'ownerCreateUpdateView'])->name('.ownerCreateUpdateView');
    Route::post('/owner/createOrUpdate', [DeveloperController::class, 'ownerCreateOrUpdate'])->name('.ownerCreateOrUpdate');

});
