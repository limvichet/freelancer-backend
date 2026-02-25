<?php

namespace App\Http\Controllers\Api\V2;

use Auth;
use File;
use Storage;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Constants\GeneralConfig;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{

    public function __construct() {}

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'lang'     => 'required|in:en,kh'
        ]);

        app()->setLocale($fields['lang']);
        $email = $fields['email'];
        $maxAttempts = GeneralConfig::MAX_ATTEMPTS;
        $lockoutMinutes = GeneralConfig::LOCKOUT_TIME;
        $lockoutSeconds = $lockoutMinutes * 60;

        $key = 'login:' . $email . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $releaseTimestamp = RateLimiter::availableIn($key) + Carbon::now()->getTimestamp();
            $releaseTime = Carbon::createFromTimestamp($releaseTimestamp);
            $message = __('auth.too_many_attempts');
            $blockData = [
                'max_attemps' => $maxAttempts,
                'lockout_minutes' => $lockoutMinutes,
                'locked_until' => $releaseTime,
                'locked_message' => $message
            ];
            return ApiResponse::error($message, GeneralConfig::LOCKED_CODE, GeneralConfig::LOCKED_CODE, $blockData);
        }
        // ------------------------------------

        $user = User::where('email', $email)
            ->where('status', config('constants.CONST_APPROVED'))
            ->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            RateLimiter::hit($key, $lockoutSeconds);

            $message = __('auth.login_fail');

            $remaining = $maxAttempts - RateLimiter::attempts($key);

            $responseData = [
                'remaining_attempts' => $remaining > 0 ? $remaining : 0
            ];
            return ApiResponse::error($message, config('constants.codes.fail_401'), 401, $responseData);
        }
        RateLimiter::clear($key);

        $expiryDate = Carbon::now()->addMinutes(GeneralConfig::TOKEN_EXPIRE_TIME);
        $token = $user->createToken('usertoken', ['*'], $expiryDate)->plainTextToken;

        // $directory = public_path('storage/images/staffs/');
        // $path = $directory . $user->staff->photo;
        // if ($user->staff->photo && File::exists($path)) {
        //     $user->staff->photo =  url(Storage::url('images/staffs/' . $user->staff->photo));
        // } else {
        //     $user->staff->photo = null;
        // }


        $userData = [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
        ];

        $responseData = [
            'user' => $userData,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expiryDate->toIso8601String()
        ];

        return ApiResponse::success(__('auth.login_success'), null, $responseData);
    }

    public function refresh(Request $request)
    {
        $user = Auth::user();
        // Revoke the old token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        $expiryDate = Carbon::now()->addMinutes(GeneralConfig::TOKEN_EXPIRE_TIME);

        $newToken = $user->createToken('usertoken', ['*'], $expiryDate)->plainTextToken;

        $responseData = [
            'access_token' => $newToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiryDate->toIso8601String(),
        ];

        return ApiResponse::success('Token refreshed successfully', null, $responseData);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        // Revoke the current access token
        $user->currentAccessToken()->delete();

        app()->setLocale($request->lang ?? 'kh');

        return ApiResponse::success(__('auth.logout_success'));
    }


    public function changePassword(Request $request)
    {
        $fields = $request->validate([
            'password_old'        => 'required|min:6',
            'password'        => 'required|min:6',
            'password_confirmation' => 'required',
            'lang'            => 'required|in:en,kh'
        ]);
        app()->setLocale($fields['lang']);

        if ($fields['password'] !== $fields['password_confirmation']) {
            return ApiResponse::error(__('passwords.password_mismatch'));
        }
        $user = Auth::user();
        if (!$user) {
            $message = __('auth.user_not_found');
            return ApiResponse::error($message, config('constants.codes.fail_404'), 404);
        }
        if (!Hash::check($fields['password_old'], $user->password)) {
            $message = __('auth.wrong_old_password');
            return ApiResponse::error($message, config('constants.codes.fail_404'), 404);
        }

        $user->update([
            'password' => Hash::make($fields['password'])
        ]);

        return ApiResponse::success(__('passwords.password_changed'));
    }
}
