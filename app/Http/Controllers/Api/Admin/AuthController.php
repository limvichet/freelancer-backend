<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admin_users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

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

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => config('constants.messages_en.login_fail') ,
                'code'  => config('constants.codes.fail_401'),
            ], 401);
        }

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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
