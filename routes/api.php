<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AnalystController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\OrderController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public
Route::get('/analysts', [AnalystController::class, 'index']); // List all analysts
Route::get('/analysts/{id}', [AnalystController::class, 'show']); // Get one analyst
Route::get('/services', [ServiceController::class, 'index']); // List all services

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        // Load correct profile based on role
        if ($request->user()->role === 'ANALYST') {
            return $request->user()->load('analystProfile.services');
        }
        if ($request->user()->role === 'CLIENT') {
            return $request->user()->load('clientProfile.orders');
        }
        return $request->user();
    });

    // Analyst-specific service management
    Route::apiResource('/my-services', ServiceController::class)->except(['index', 'show']);
    
    // Order management
    Route::post('/orders', [OrderController::class, 'store']); // Client creates an order
    Route::get('/my-orders', [OrderController::class, 'index']); // Both roles can see their orders
});

