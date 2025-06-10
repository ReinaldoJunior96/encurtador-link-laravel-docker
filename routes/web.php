<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/success', [\App\Http\Controllers\StripeController::class, 'success']);

Route::get('/login', function () {
    return view('login');
})->name('login');
