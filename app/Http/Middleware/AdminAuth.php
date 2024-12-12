<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth()->user()->role_id == 1 || Auth()->user()->role_id == 2){ // admin = 1 superAdmin = 2
            return $next($request);
        }else{
            return redirect()->route('login')->with('error', 'You do not have permission to access this page !');
        }
        
    }
    
    
}
