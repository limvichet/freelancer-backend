<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogApiRequests;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\CommuneController;
use App\Http\Controllers\Api\Admin\VillageController;
use App\Http\Controllers\Api\Admin\DistrictController;
use App\Http\Controllers\Api\Admin\GetStaffController;
use App\Http\Controllers\Api\Admin\LocationController;
use App\Http\Controllers\Api\Admin\ProvinceController;
use App\Http\Controllers\Api\Admin\GetLocationController;

/* public */
Route::prefix('admin-public')
    ->middleware([LogApiRequests::class])
    ->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);

        /* reset password */
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);

        /* verify email */
        Route::post('/email-verification-send', [AuthController::class, 'emailVerificationSend'])
                ->middleware(['auth:sanctum', 'throttle:6,1'])
                ->name('email.verification.send');

        Route::get('/email-verification-verify/{id}/{hash}', [AuthController::class,'emailVerificationVerify'])
                // ->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])
                ->middleware(['auth:sanctum', 'throttle:6,1'])
                ->name('email.verification.verify');

        Route::get('/email-verification-status', [AuthController::class,'emailVerificationStatus'])
                ->middleware(['auth:sanctum'])
                ->name('email.verification.status');
});


/* secure */
Route::prefix('admin-secure')
    // ->middleware(['auth:sanctum', 'verified', LogApiRequests::class])  
    ->middleware(['auth:sanctum', LogApiRequests::class])
    ->group(function () {

        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::get('/user', [UserController::class, 'profile']);

        // get loctions
        Route::get('/locations/get-provinces', [GetLocationController::class, 'getProvinces']);
        Route::get('/locations/get-districts', [GetLocationController::class, 'getDistricts']);
        Route::get('/locations/get-communes', [GetLocationController::class, 'getCommunes']);
        Route::get('/locations/get-villages', [GetLocationController::class, 'getVillages']);
        Route::get('/locations/get-locations', [GetLocationController::class, 'getLocations']);
        Route::get('/locations/get-location-types', [GetLocationController::class, 'getLocationTypes']);
        Route::get('/locations/get-location-regions', [GetLocationController::class, 'getLocationRegions']);
        Route::get('/locations/get-location-levels', [GetLocationController::class, 'getLocationLevels']);


        /* province resource */
        Route::apiResource('/locations/provinces', ProvinceController::class);
        /* district resource */
        Route::apiResource('/locations/districts', DistrictController::class);
        /* commune resource */
        Route::apiResource('/locations/communes', CommuneController::class);
        /* village resource */
        Route::apiResource('/locations/villages', VillageController::class);

        /* locations resource  */
        Route::apiResource('/locations/locations', LocationController::class);

        // get staffs
        Route::get('/staffs/get-positions', [GetStaffController::class, 'getPositions']);
        Route::get('/staffs/get-qualifications', [GetStaffController::class, 'getQualifications']);
        Route::get('/staffs/get-subjects', [GetStaffController::class, 'getSubjects']);
        Route::get('/staffs/get-institutions', [GetStaffController::class, 'getInstitutions']);
        Route::get('/staffs/get-professionals', [GetStaffController::class, 'getProfessionals']);
        Route::get('/staffs/get-professional-types', [GetStaffController::class, 'getProfessionalTypes']);
        Route::get('/staffs/get-status', [GetStaffController::class, 'getStatus']);
});
