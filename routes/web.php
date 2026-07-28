<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
})->name('index');
Route::get('/appoint', function () {
    return Inertia::render('user/AppointmentsCustomer');
})->name('appoint');
