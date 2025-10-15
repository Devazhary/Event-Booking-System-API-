<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoryController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [AuthController::class, 'user'])->name('user');
});

Route::apiResource('/categories', CategoryController::class)->only('index', 'show');

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('is_admin')->group(function () {

        Route::apiResource('/categories', CategoryController::class)->only('store', 'update', 'destroy');
        Route::patch('/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');
    });
});
