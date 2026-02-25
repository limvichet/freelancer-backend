<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($message = '', $code = null, $data = null)
    {
        return response()->json([
            'code' => $code ?? config('constants.codes.success'),
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public static function error($message = '', $code = null, $status = 400, $data = null)
    {
        return response()->json([
            'code' => $code ?? config('constants.codes.fail_400'),
            'message' => $message,
            'data' => $data
        ], $status);
    }
}