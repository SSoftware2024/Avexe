<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
});
Route::get('/pg2', function () {
    return Inertia::render('IndexT');
});
