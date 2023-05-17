<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;

// tokens
Route::get('/tokens', [TokenController::class, 'listing']);

// categories
Route::get('/categories', [CategoryController::class, 'listing']);

// menu each category
Route::get('/categories/{categoryId}/menus', [MenuController::class, 'listing']);

Route::get('/carts-tokens', [CartController::class, 'cartTokenListing']);
Route::post('/carts', [CartController::class, 'storeAndUpdate']);
Route::put('/carts/{id}', [CartController::class, 'updateQuantity']);
Route::get('/carts/{id}', [CartController::class, 'detail']);

Route::post('/carts/{id}/checkout', [CheckoutController::class, 'checkout']);
