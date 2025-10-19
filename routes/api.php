<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BookingController;


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

Route::apiResource('/events', EventController::class)->only('index', 'show');

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('is_admin')->group(function () {
        Route::apiResource('/events', EventController::class)->only('store', 'update', 'destroy');
        Route::patch('/events/{event}/toggle', [EventController::class, 'toggle'])->name('categories.toggle');
    });
});


Route::apiResource('/bookings', BookingController::class)->only('index', 'show');

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('is_admin')->group(function () {
        Route::apiResource('/bookings', BookingController::class)->only('store', 'update', 'destroy');
        Route::patch('/bookings/{booking}/toggle', [BookingController::class, 'toggle'])->name('categories.toggle');
    });
});
