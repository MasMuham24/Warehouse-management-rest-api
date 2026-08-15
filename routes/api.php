<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('categories', [CategoryController::class, 'index'])->middleware('role:admin,staff,viewer');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->middleware('role:admin,staff,viewer');
    Route::post('categories', [CategoryController::class, 'store'])->middleware('role:admin,staff');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('role:admin,staff');
    Route::patch('categories/{category}', [CategoryController::class, 'update'])->middleware('role:admin,staff');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:admin');
});
