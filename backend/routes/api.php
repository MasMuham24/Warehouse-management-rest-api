<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Support\Facades\Route;

// ==========================
// Authentication
// ==========================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ==========================
// Authenticated Routes
// ==========================

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ==========================
    // Category
    // ==========================

    Route::get('/categories', [CategoryController::class, 'index'])->middleware('role:admin,staff,viewer');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->middleware('role:admin,staff,viewer');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:admin,staff');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:admin,staff');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:admin,staff');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:admin');

    // ==========================
    // Product
    // ==========================

    Route::get('/products', [ProductController::class, 'index'])->middleware('role:admin,staff,viewer');
    Route::get('/products/{product}', [ProductController::class, 'show'])->middleware('role:admin,staff,viewer');
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:admin,staff');
    Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('role:admin,staff');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->middleware('role:admin,staff');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('role:admin,staff');

    // ==========================
    // Supplier
    // ==========================

    Route::get('/suppliers', [SupplierController::class, 'index'])->middleware('role:admin,staff,viewer');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->middleware('role:admin,staff,viewer');
    Route::post('/suppliers', [SupplierController::class, 'store'])->middleware('role:admin,staff');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->middleware('role:admin,staff');
    Route::patch('/suppliers/{supplier}', [SupplierController::class, 'update'])->middleware('role:admin,staff');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->middleware('role:admin,staff');

    // Stock Management
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->middleware('role:admin,staff,viewer');
    Route::post('/stock-movements/in', [StockMovementController::class, 'storeIn'])->middleware('role:admin,staff');
    Route::post('/stock-movements/out', [StockMovementController::class, 'storeOut'])->middleware('role:admin,staff');
    Route::post('/stock-movements/adjustment', [StockMovementController::class, 'adjustment'])->middleware('role:admin,staff');

    // Dashboard Api
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:admin,staff,viewer');
    Route::get('/dashboard/movements', [DashboardController::class, 'movements']); 
});
