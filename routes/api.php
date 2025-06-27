<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\UserController;

$admin_public_path = '/admin-public';//Public API
$admin_secure_path = '/admin-secure';//Secure API (Require Token)

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/api', function () {
    return response()->json([
        'message' => 'Hello from Laravel API!'
    ]);
});


Route::prefix($admin_public_path)->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::prefix($admin_secure_path)->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'profile']);
});
