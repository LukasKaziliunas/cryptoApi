<?php

namespace App\Http\Middleware;

use App\Exceptions\JwtTokenNotParsedException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            Auth::parseToken()->authenticate();
            return $next($request);
        } catch (JWTException $e) {
            throw new JwtTokenNotParsedException();
        }

    }
}
