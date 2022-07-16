<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if ( !auth()->user() || !in_array(auth()->user()->role->name, $roles)){
            return response()->json(['message' => 'not found'], 401);
        }

        return $next($request);
    }
}
