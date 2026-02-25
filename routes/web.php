<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\RateLimiter;

Route::get('/', function () {
    return view('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::group(['prefix' => '{locale}'], function () {
//     // Admin Dashboad
//     Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// });




Route::get('/clear-catche', function () {
    try {

        /* clear catch */
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return response()->json([
            'status' => 'success',
            'message' => [
                'CATCHE' => 'cleared successfully',
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/clear-login-attempts', function (Request $request) {
    try {

        $email = $request->query('email');
        if (!$email) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email is required to clear RateLimiter key',
            ], 400);
        }

        $ip = $request->query('ip') ?? $request->ip() ?? '127.0.0.1';

        // Build the throttle key same as in LoginRequest
        $key = Str::transliterate(Str::lower($email) . '|' . $ip);

        // Clear RateLimiter for this key
        RateLimiter::clear($key);

        return response()->json([
            'status' => 'success',
            'message' => "Login attempts cleared for {$email} ({$ip})",
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
