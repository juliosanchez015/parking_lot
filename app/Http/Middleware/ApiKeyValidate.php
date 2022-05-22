<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyValidate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader("x-api-key")) {
            return response()->json([
                'status' => 401,
                'message' => '1 Acceso no autorizado',
            ], 401);
        }

        if ($request->hasHeader("x-api-key")) {
            $api_key = config("app.x_api_key");
            if ($request->header("x-api-key") != $api_key) {
                return response()->json([
                    'status' => 401,
                    'message' => '2 Acceso no autorizado',
                ], 401);
            }
        }

        return $next($request);
    }
}
