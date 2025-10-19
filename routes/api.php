<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BookingController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->name('v1.register');
    Route::post('/login', [AuthController::class, 'login'])->name('v1.login');


    Route::middleware('auth:sanctum')->group(function () {


        Route::post('/logout', [AuthController::class, 'logout'])->name('v1.logout');
        Route::get('/user', [AuthController::class, 'user'])->name('v1.user');


        Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);


        Route::apiResource('events', EventController::class)->only(['index', 'show']);


        Route::apiResource('bookings', BookingController::class)->only(['index', 'show']);

        Route::middleware('is_admin')->group(function () {


            Route::apiResource('categories', CategoryController::class)->only(['store', 'update']);
            Route::patch('categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('v1.categories.toggle');


            Route::apiResource('events', EventController::class)->only(['store', 'update']);
            Route::patch('events/{event}/toggle', [EventController::class, 'toggle'])->name('v1.events.toggle');


            Route::apiResource('bookings', BookingController::class)->only(['store', 'update']);
            Route::patch('bookings/{booking}/toggle', [BookingController::class, 'toggle'])->name('v1.bookings.toggle');
        });
    });
});
