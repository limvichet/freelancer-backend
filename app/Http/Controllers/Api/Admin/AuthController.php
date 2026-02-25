<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;

use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admin_users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // 🔹 Send email verification link
        // $user->sendEmailVerificationNotification();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(
            [
                'user' => $user,
                'token' => $token,
                'code'  => config('constants.codes.success'),
                'message' => config('constants.messages_en.request_success')
            ]
        );
    }

    public function login(LoginRequest $request)
    {

        $request->authenticate();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => config('constants.messages_en.login_fail'),
                'code'  => config('constants.codes.fail_401'),
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(
            [
                'user' => $user,
                'token' => $token,
                'message' => config('constants.messages_en.request_success'),
                'code'  => config('constants.codes.success'),
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect',
                'code'    => config('constants.codes.fail_401'),
            ], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
            'code'    => config('constants.codes.success'),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        try {

            $query = User::query();
            $user = User::where($query->qualifyColumn('email'), $request->input('email'))->first();

            if ($user) {
                $token = str_pad(random_int(1, 9999),6, '0', STR_PAD_LEFT);
                //$token = Str::random(40);
                $domain = URL::to('/');
                // $url = $domain . '/api/admin-public/reset-password?token' . $token;

                // $data['url'] = $url;
                $data['domain'] = $domain;
                $data['email'] = $request->email;
                $data['token'] = $token;
                $data['title'] = "Password Reset Email";
                $data['application'] = "freelandcer application";
                $data['datetime'] =  Carbon::now()->format('Y-m-d H:i:s');
                // $data['body'] = "Please click on below link to reset your password";

                // Send mail
                Mail::send('forgotPasswordMail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                // Save token in password_resets table
                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email'      => $request->email,
                        'token'      => $token,
                        'created_at' => now(),
                        'expires_at'  => now()->addMinutes(30), // Token valid for 30 minutes
                    ]
                );

                return response()->json([
                    'message' => 'A CODE has been sent to your email.',
                    'data'    => $data,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Email not found in our records',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal problem with the server.',
                'error' => $e->getMessage()
            ], status: 500);
        }
    }

    public function resetPassword(Request $request){

        $attributes = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $attributes['email'])->first();
        if(!$user){
            return response()->json([
                'message' => 'Incorrect email address provided',
                'code'    => 404,
            ], 404);
        }

        $resetRequest = PasswordReset::where('email', $user->email)->first();
        if(!$resetRequest || $resetRequest->token != $request->token || $resetRequest->expires_at->isPast()){
            return response()->json([
                'message' => 'An error occured, Please try again, token mismatch',
                'code'    => 400,
            ], 400);
        }

        $user->fill(['password' => Hash::make($attributes['password'])]);
        $user->save();

        // delete previous all token
        $user->tokens()->delete();

        $resetRequest->delete();

        // get token for authentication user
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(
            [
                'user' => $user,
                'token' => $token,
                'message' => config('constants.messages_en.request_success'),
                'code'  => config('constants.codes.success'),
            ],status: 200
        );
    }



    // verification email
    public function emailVerificationSend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent']);
    }


    public function emailVerificationVerify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {

            return response()->json('Already Verified');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json('Successfully Verified');
    }

    public function emailVerificationStatus(Request $request){
        return response()->json([
            'verified' => $request->user()->hasVerifiedEmail()
        ]);
    }

    public function updateLocationCode(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'location_code' => 'required|string|max:11',
        ]);

        // Current logged-in user
        $user = $request->user();

        // Update location code
        $user->location_code = $request->location_code;
        $user->save();

        return response()->json([
            'message' => 'Location code updated successfully',
            'code'    => config('constants.codes.success'),
            'user'    => $user,
        ]);
    }

}



