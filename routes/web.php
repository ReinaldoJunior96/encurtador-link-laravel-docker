<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

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

Route::get('/chat', function () {
    return view('chat');
})->name('chat');

Route::post('/chat/message', [ChatController::class, 'sendMessage']);
Route::post('/chat/typing', [ChatController::class, 'typing']);
