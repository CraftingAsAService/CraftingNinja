<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! (Auth::check() && in_array(Auth::user()->username, config('site.admins'))))
            return response('Unauthorized.', 401);

        return $next($request);
    }
}
