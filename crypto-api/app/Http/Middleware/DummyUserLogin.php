<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DummyUserLogin
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
        if(Auth::check())
            return $next($request);

        if(Auth::attempt([ 'email' => 'bob@example.com', 'password' => 'password' ]))
            return $next($request);

        return response()->json(['message' => 'User does not exist.'], 401); 
    }
}
