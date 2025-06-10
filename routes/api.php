<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LinkController;
use App\Http\Controllers\StripeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rota para encurtar link
Route::post('/shorten', [LinkController::class, 'shorten']);
// Rota para redirecionar
Route::get('/s/{code}', [LinkController::class, 'redirect']);

// Rota para checkout do Stripe
Route::get('/checkout', [StripeController::class, 'checkout']);
// Rota de sucesso do Stripe
Route::get('/success', [StripeController::class, 'success']);
// Rota para webhook do Stripe
Route::post('/stripe/webhook', [StripeController::class, 'webhook']);
