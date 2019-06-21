<?php

namespace App\Http\Middleware;

use App\Models\Users;
use Closure;
use Illuminate\Support\Facades\Session;

class checkLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has('user.id')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
