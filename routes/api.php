<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\LocationController;


/* public */
Route::prefix('admin-public')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});


/* secure */
Route::prefix('admin-secure')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'profile']);


    Route::get('/locations/get-provinces', [LocationController::class, 'getProvinces']);
    Route::get('/locations/get-districts', [LocationController::class, 'getDistricts']);
    Route::get('/locations/get-communes', [LocationController::class, 'getCommunes']);
    Route::get('/locations/get-villages', [LocationController::class, 'getVillages']);

    Route::get('/locations/get-locations', [LocationController::class, 'getLocations']);
    Route::get('/locations/get-location-types', [LocationController::class, 'getLocationTypes']);
    Route::get('/locations/get-location-regions', [LocationController::class, 'getLocationRegions']);
    Route::get('/locations/get-location-levels', [LocationController::class, 'getLocationLevels']);


});
