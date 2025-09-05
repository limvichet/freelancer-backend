<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogApiRequests;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\LocationController;
use App\Http\Controllers\Api\Admin\GetLocationController;


/* public */
Route::prefix('admin-public')
    ->middleware([LogApiRequests::class]) 
    ->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);
});


/* secure */
Route::prefix('admin-secure')
    ->middleware(['auth:sanctum', LogApiRequests::class])  
    ->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [UserController::class, 'profile']);


        Route::get('/locations/get-provinces', [GetLocationController::class, 'getProvinces']);
        Route::get('/locations/get-districts', [GetLocationController::class, 'getDistricts']);
        Route::get('/locations/get-communes', [GetLocationController::class, 'getCommunes']);
        Route::get('/locations/get-villages', [GetLocationController::class, 'getVillages']);

        Route::get('/locations/get-locations', [GetLocationController::class, 'getLocations']);
        Route::get('/locations/get-location-types', [GetLocationController::class, 'getLocationTypes']);
        Route::get('/locations/get-location-regions', [GetLocationController::class, 'getLocationRegions']);
        Route::get('/locations/get-location-levels', [GetLocationController::class, 'getLocationLevels']);

        Route::apiResource('/locations/locations', LocationController::class);

});
