<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdministrator
{
    /**
     * If user not logged in, give them a chance by going to login page
     * If they're already logged in but just don't have access go to
     * unauthorized page. Using abort to go to custom error page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (is_null(Auth::user())) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
        elseif (Auth::user()->isAdmin()) {
            return $next($request);
        }
        else {
            abort(403);
        }
    }
}
