<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\DateController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::apiResource('dates', DateController::class)->only(['show']);
});



Route::get('/hello', function () {
    return response()->json([
        'message' => 'Hello from Laravel API!'
    ]);
})->middleware('check.id.token.name');

Route::get('/secure-data', function (Request $request) {
    $token = $request->query('token');  // or use $request->header('token')

    // Check if token matches a predefined value (for demo)
    if ($token !== 'my-secret-token') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response()->json([
        'data' => 'This is secure data.',
        'token' => $token,
    ]);
});