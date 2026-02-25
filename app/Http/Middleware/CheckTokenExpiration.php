<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PersonalAccessToken;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = explode(' ', $request->header('Authorization'))[1] ?? null;
        if ($accessToken) {
            $tokenModel = PersonalAccessToken::findToken($accessToken);
            if ($tokenModel && $tokenModel->expires_at && now()->greaterThan($tokenModel->expires_at)) {
                $tokenModel->delete();
                return response()->json([
                    'message' => 'Token has expired'
                ], 401);
            }
        }

        return $next($request);
    }

}
