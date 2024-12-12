<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth::check() && Auth::user()->role_id == 1) {
            return response()->json(['message' => 'Admin'], 200);
            return $next($request);
        } else {
            // return print unaithorized
            return response()->json(['message' => 'Unauthorized as admin'], 401);
        }
    }
}
