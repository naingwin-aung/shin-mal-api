<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TokenController;

Route::get('/tokens', [TokenController::class, 'listing']);
Route::get('/categories', [CategoryController::class, 'listing']);
Route::get('/categories/{categoryId}/menus', [MenuController::class, 'listing']);
