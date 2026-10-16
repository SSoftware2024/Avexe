<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyOperatingHoursController;
use App\Http\Controllers\CompanySettingsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentMethodController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'index'])->name('index');

Route::delete('/removeProfile', [CustomerController::class, 'removeProfile'])->name('.removeProfile');
Route::get('/home', [AuthController::class, 'index'])->name('home');

// autenticação
Route::prefix('auth')->name('auth')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/profileView', [AuthController::class, 'profileView'])->name('.profileView');
    Route::delete('/removeProfile', [AuthController::class, 'removeProfile'])->name('.removeProfile');
    Route::post('/confirm-password', [AuthController::class, 'confirmPasswordInertiaXHR'])->name('.confirmPasswordInertiaXHR')->middleware('throttle:6,1');
});

// cliente
Route::prefix('customer')->name('customer')->group(function () {
    Route::get('/appointmentsView', [CustomerController::class, 'appointmentsView'])->name('.appointmentsView');

    // login google
    Route::get('/loginGoogle', [CustomerController::class, 'loginGoogle'])->name('.loginGoogle');
    Route::get('/loginGoogleCallback', [CustomerController::class, 'loginGoogleCallback'])->name('.loginGoogleCallback');
});

// dono
Route::prefix('owner')->middleware(['auth'])->name('owner')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index']);


    // operações owner
    Route::get('/listView', [OwnerController::class, 'listView'])->name('.listView');
    Route::get('/createOrUpdate/{user?}', [OwnerController::class, 'createUpdateView'])->name('.createUpdateView');
    Route::post('/createOrUpdate', [OwnerController::class, 'createOrUpdate'])->name('.createOrUpdate');
    Route::delete('/delete/{id}', [OwnerController::class, 'delete'])->name('.delete');
    Route::patch('/toggleActive/{id}', [OwnerController::class, 'toggleActive'])->name('.toggleActive');
});
Route::prefix('company')->middleware(['auth'])->name('company')->group(function () {

    // operações empresa
    Route::get('/listView', [CompanyController::class, 'listView'])->name('.listView');
    Route::get('/createOrUpdate/{id?}', [CompanyController::class, 'createOrUpdateView'])->name('.createOrUpdateView');
    Route::match(['post', 'patch'], '/createOrUpdate', [CompanyController::class, 'createOrUpdate'])->name('.createOrUpdate');
    Route::patch('/toggleActive', [CompanyController::class, 'toggleActive'])->name('.toggleActive');
    Route::delete('/delete/{id}', [CompanyController::class, 'delete'])->name('.delete');

    //horário funcionamento
    Route::prefix('operatingHours')->name('.operating_hours')->group(function () {
        Route::post('/save', [CompanyOperatingHoursController::class, 'save'])->name('.save');
    });
    //configurações
    Route::prefix('settings')->name('.settings')->group(function () {
        Route::post('/save', [CompanySettingsController::class, 'save'])->name('.save');
    });
});

Route::prefix('payment_method')->middleware(['auth'])->name('payment_method')->group(function () {
    Route::get('/index', [PaymentMethodController::class, 'index']);
    Route::post('/createJson', [PaymentMethodController::class, 'createJson'])->name('.createJson');
});



// desenvolvedor
Route::prefix('developer')->middleware(['auth'])->name('developer')->group(function () {
    Route::get('/dashboard', [DeveloperController::class, 'index']);
    Route::get('/listView', [DeveloperController::class, 'listView'])->name('.listView');
});
