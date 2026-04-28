<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesPageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/s/{slug}', [SalesPageController::class, 'getBySlug']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::get('/sales-pages', [SalesPageController::class, 'index']);
    Route::post('/sales-pages', [SalesPageController::class, 'store']);
    Route::get('/sales-pages/{salesPage}', [SalesPageController::class, 'show']);
    Route::get('/sales-pages/{salesPage}/status', [SalesPageController::class, 'status']);
    Route::delete('/sales-pages/{salesPage}', [SalesPageController::class, 'destroy']);
});
