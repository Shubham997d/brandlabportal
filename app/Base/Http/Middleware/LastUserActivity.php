<?php

namespace App\Base\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Cache;
use Carbon\Carbon;
use App\Base\Events\UserOnlineStatus;

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            event(new UserOnlineStatus()); /* set user status online */
        }
        return $next($request);
    }
}
