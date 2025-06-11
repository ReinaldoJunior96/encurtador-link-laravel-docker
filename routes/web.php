<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/success', [\App\Http\Controllers\StripeController::class, 'success']);

Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');

Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/register', [UserController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [UserController::class, 'register'])->name('register.post');

Route::get('/chat', [ChatController::class, 'publicChat'])->name('chat')->middleware(['auth', 'can:chat-public']);

Route::post('/chat/message', [ChatController::class, 'sendMessage'])->middleware('auth');
Route::post('/chat/typing', [ChatController::class, 'typing'])->middleware('auth');
Route::get('/users', [UserController::class, 'listUsers'])->middleware(['auth', 'can:manage-users'])->name('users');
Route::get('/chat/{user}', [ChatController::class, 'privateChat'])->middleware('auth')->name('chat.private');
Route::post('/chat/private/message', [ChatController::class, 'sendPrivateMessage'])->middleware('auth')->name('chat.private.message');
Route::post('/users/{id}/role', [UserController::class, 'updateRole'])->middleware(['auth', 'can:manage-users'])->name('users.updateRole');
