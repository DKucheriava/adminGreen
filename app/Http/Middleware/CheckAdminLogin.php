<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
// use Auth;

class CheckAdminLogin
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
        if (!Auth::guard('admin')->check()) {
            return redirect('/login');
        }
        return $next($request);
    }
}
