<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIdTokenWithName
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->query('id') ?? $request->header('id');
        $token = $request->query('token') ?? $request->header('token');
        $tokenName = $request->query('token_name') ?? $request->header('token_name');

        if (!$id || !$token || !$tokenName) {
            return response()->json(['error' => 'Missing id, token, or token_name'], 400);
        }

        $tokenSet = config("api_tokens.{$id}");

        if (!$tokenSet || !isset($tokenSet[$tokenName])) {
            return response()->json(['error' => 'Invalid id or token_name'], 401);
        }

        if ($tokenSet[$tokenName] !== $token) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
