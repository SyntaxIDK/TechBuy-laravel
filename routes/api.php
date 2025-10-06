<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\MongoProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes (public)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Public API routes
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

// Additional product routes (must be before resource routes)
Route::get('products/featured', [ProductController::class, 'featured']);
Route::get('products/search', [ProductController::class, 'search']);
Route::get('categories/{category}/products', [ProductController::class, 'byCategory']);

// Product resource routes
Route::apiResource('products', ProductController::class)->only(['index', 'show']);

// MongoDB Enhanced API routes
Route::prefix('mongo')->group(function () {
    Route::get('/products/search', [MongoProductController::class, 'search']);
    Route::get('/products/trending', [MongoProductController::class, 'trending']);
    Route::get('/products/{id}/analytics', [MongoProductController::class, 'analytics']);
    Route::post('/products/{id}/cart-addition', [MongoProductController::class, 'recordCartAddition']);
    Route::post('/products/{id}/purchase', [MongoProductController::class, 'recordPurchase']);
});

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    // User profile routes
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::put('/auth/change-password', [AuthController::class, 'changePassword']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);
    Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken']);
    Route::get('/auth/verify-token', [AuthController::class, 'verifyToken']);
    Route::get('/auth/tokens', [AuthController::class, 'tokens']);
    Route::delete('/auth/tokens/{tokenId}', [AuthController::class, 'revokeToken']);
    Route::delete('/auth/delete-account', [AuthController::class, 'deleteAccount']);

    // Cart routes
    Route::apiResource('cart', CartController::class)->except(['show']);
    Route::post('cart/clear', [CartController::class, 'clear']);
});
