<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\CpdController;
use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\StaffController;
use App\Http\Controllers\API\V2\AddressController;

Route::prefix('v2')->group(function () {


     // 🔓 Public routes (no auth required)
    Route::prefix('public')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);


        Route::get('/provinces/{lang}/options', [AddressController::class, 'getProvinceOptions']);
        Route::get('/cpd/{lang}/learning-mode-options', [CpdController::class, 'getLearningModeOptions']);
        Route::get('/cpd/{lang}/activities', [CpdController::class, 'searchActivities']);
        Route::get('/cpd/{lang}/activities/{id}/details', [CpdController::class, 'activityDetails']);

        Route::get('/subjects', [CpdController::class, 'subjectsList']);
    });

    // 🔐 Secure routes (auth required)
    Route::prefix('secure')->middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);

        Route::get('/staffs/{staff_id}/profile', [StaffController::class, 'staffProfile']);
    });

});
