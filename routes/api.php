<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LinkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rota para encurtar link
Route::post('/shorten', [LinkController::class, 'shorten']);
// Rota para redirecionar
Route::get('/s/{code}', [LinkController::class, 'redirect']);
