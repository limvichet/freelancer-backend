<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        // Capture params BEFORE the request pipeline runs
        $capturedParams = $this->extractParams($request);

        $response = $next($request); // auth/sanctum resolves user here

        try {
            ApiLog::create([
                'method'  => $request->method(),
                'url'     => $request->fullUrl(),
                'ip'      => $request->ip(),
                'headers' => $this->sanitizeHeaders($request->headers->all()),
                'params'  => $capturedParams,
                'user_id' => optional($request->user())->id,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }

    private function extractParams(Request $request): array
    {
        // 1) Normal case: includes query + body/json when headers are correct
        $params = $request->all();

        // 2) If empty (e.g., missing Content-Type), try raw JSON body
        if (empty($params)) {
            $raw = $request->getContent();
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $params = $decoded;
            }
        }

        // 3) Add query explicitly (kept separate is often clearer)
        if ($query = $request->query()) {
            $params = ['_query' => $query, '_body' => $params];
        }

        // 4) Replace files with safe metadata
        foreach ($request->files->keys() as $key) {
            if ($file = $request->file($key)) {
                $params[$key] = [
                    'original_name' => $file->getClientOriginalName(),
                    'size'          => $file->getSize(),
                    'mime_type'     => $file->getMimeType(),
                ];
            }
        }

        return $this->sanitizeParams($params);
    }

    private function sanitizeHeaders(array $headers): array
    {
        foreach (['authorization', 'cookie', 'set-cookie'] as $h) {
            if (isset($headers[$h])) $headers[$h] = ['[REDACTED]'];
        }
        return $headers;
    }

    private function sanitizeParams(array $params): array
    {
        foreach (['password','password_confirmation','current_password','token'] as $k) {
            if (isset($params[$k])) unset($params[$k]); // or hash if you prefer
        }
        return $params;
    }
}
