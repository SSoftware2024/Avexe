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
Route::prefix('owner')->middleware(['auth'])->name('owner')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index']);


    // operações owner
    Route::get('/listView', [OwnerController::class, 'listView'])->name('.listView');
    Route::get('/createOrUpdate/{user?}', [OwnerController::class, 'createUpdateView'])->name('.createUpdateView');
    Route::post('/createOrUpdate', [OwnerController::class, 'createOrUpdate'])->name('.createOrUpdate');
    Route::delete('/delete/{id}', [OwnerController::class, 'delete'])->name('.delete');
    Route::patch('/toggleActive/{id}', [OwnerController::class, 'toggleActive'])->name('.toggleActive');
});

// desenvolvedor
Route::prefix('developer')->middleware(['auth'])->name('developer')->group(function () {
    Route::get('/dashboard', [DeveloperController::class, 'index']);

    // operações owner
    // Route::get('/owner/listView', [DeveloperController::class, 'ownerListView'])->name('.ownerListView');
    // Route::get('/owner/createOrUpdate/{user?}', [DeveloperController::class, 'ownerCreateUpdateView'])->name('.ownerCreateUpdateView');
    // Route::post('/owner/createOrUpdate', [DeveloperController::class, 'ownerCreateOrUpdate'])->name('.ownerCreateOrUpdate');
    // Route::delete('/owner/delete/{id}', [DeveloperController::class, 'ownerDelete'])->name('.ownerDelete');
    // Route::patch('/owner/toggleActive/{id}', [DeveloperController::class, 'ownerToggleActive'])->name('.ownerToggleActive');

    //empresa
    Route::get('/company/listView', [DeveloperController::class, 'companyListView'])->name('.companyListView');
    Route::get('/company/createOrUpdate/{id?}', [DeveloperController::class, 'companyCreateOrUpdateView'])->name('.companyCreateOrUpdateView');
    Route::match(['post','patch'],'/company/createOrUpdate', [DeveloperController::class, 'companyCreateOrUpdate'])->name('.companyCreateOrUpdate');
    Route::patch('/company/toggleActive', [DeveloperController::class, 'companyToggleActive'])->name('.companyToggleActive');
    Route::delete('/company/delete/{id}', [DeveloperController::class, 'companyDelete'])->name('.companyDelete');

});
// // desenvolvedor
// Route::prefix('owner')->middleware(['auth'])->name('owner')->group(function () {




//     //empresa
//     Route::get('/company/listView', [DeveloperController::class, 'companyListView'])->name('.companyListView');
//     Route::get('/company/createOrUpdate/{id?}', [DeveloperController::class, 'companyCreateOrUpdateView'])->name('.companyCreateOrUpdateView');
//     Route::match(['post','patch'],'/company/createOrUpdate', [DeveloperController::class, 'companyCreateOrUpdate'])->name('.companyCreateOrUpdate');
//     Route::patch('/company/toggleActive', [DeveloperController::class, 'companyToggleActive'])->name('.companyToggleActive');
//     Route::delete('/company/delete/{id}', [DeveloperController::class, 'companyDelete'])->name('.companyDelete');

// });
