<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EndPoinController;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});


Route::prefix('auth-user')->middleware('auth:sanctum')->group( function () {
    Route::post('set-weekly-availabilities', [EndPoinController::class, 'setWeeklyAvailability']);
    Route::post('set-override-availabilities', [EndPoinController::class, 'setOverrideAvailability']);
});

Route::get('seller-availabilities/{userId}', [EndPoinController::class, 'getAvailability']);
