<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* * API Routes
 * 
 * This file defines the API routes for the application.
 * It includes routes for user authentication and accessing protected resources.
 * 
 * Middleware: 
 * - 'auth:sanctum' is applied to routes that require authentication.
 * 
 * Routes:
 * - POST /login: Public route for user login, handled by AuthController@login.
 * - GET /dashboard: Protected route for accessing dashboard data, handled by DashboardController@index.
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Public route for user login
Route::post('/login', [AuthController::class, 'login']);

// Protected routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});