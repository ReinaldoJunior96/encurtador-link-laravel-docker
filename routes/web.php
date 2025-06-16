<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormularioController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->middleware(['auth', 'can:manage-users'])->name('users.edit');

// RH - Gestão de Pessoas
Route::middleware(['auth', 'can:manage-users'])->get('/rh', function () {
    $users = \App\Models\User::all();
    return view('rh.index', compact('users'));
})->name('rh.index');

// RH - Formulários customizáveis (CRUD estrutura)
Route::middleware(['auth', 'can:manage-users'])->prefix('rh/formulario')->group(function () {
    Route::get('/', [FormularioController::class, 'index'])->name('rh.formulario.index');
    Route::get('/create', [FormularioController::class, 'create'])->name('rh.formulario.create');
    Route::post('/', [FormularioController::class, 'store'])->name('rh.formulario.store');
    Route::get('/{hashSlug}/edit', [FormularioController::class, 'edit'])->name('rh.formulario.edit');
    Route::put('/{hashSlug}', [FormularioController::class, 'update'])->name('rh.formulario.update');
    Route::delete('/{hashSlug}', [FormularioController::class, 'destroy'])->name('rh.formulario.destroy');
    Route::get('/{hashSlug}/metricas', [FormularioController::class, 'metricas'])->name('rh.formulario.metricas');
});

// Rotas públicas para exibir o formulário por hashSlug e para salvar a resposta do usuário
Route::get('/formulario/{hashSlug}', [FormularioController::class, 'showPublic'])->name('formulario.public');
Route::post('/formulario/{hashSlug}/responder', [FormularioController::class, 'responder'])->name('formulario.responder');
