<?php

namespace App\Http\Middleware;

use App\Helper\ApiResponse;
use App\Traits\SignatureTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySingatureMiddleware
{
    use SignatureTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Signature');
        $randomString = $request->header('X-Randomstring');
  
        if (!$signature || !$randomString) {
            return ApiResponse::error("Missing Headers", null, 401);
        }
        if (abs(time() - ($randomString / 1000)) > 300) {
            return response()->json(['message' => 'Request expired.'], 403);
        }

        $method = strtoupper($request->method());
        $path = $request->path(); 
        $computedSignature = $this->getSignatureAdata($method, $path, $randomString);
        if (!hash_equals($computedSignature, (string)$signature)) {
            return ApiResponse::error("Invalid Signature", null, 403);
        }
        return $next($request);
    }
}
