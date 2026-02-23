<?php

namespace App\Http\Middleware;

use App\Helper\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySingatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');
        $secret = config('admin.keys.service');

        if (!$signature || !$timestamp) {
            return ApiResponse::error("Missing Headers", null, 401);
        }
        // if (abs(time() - ($timestamp / 1000)) > 300) {
        //     return response()->json(['message' => 'Request expired.'], 403);
        // }

        $method = strtoupper($request->method());
        $path = $request->path(); 
        $dataToHash = $method . $path . $timestamp;
        $computedSignature = hash_hmac('sha256', $dataToHash, $secret);
        if (!hash_equals($computedSignature, (string)$signature)) {
            return ApiResponse::error("Invalid Signature", null, 403);
        }
        return $next($request);
    }
}
