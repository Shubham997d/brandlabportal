<?php

namespace App\Base\Http\Middleware;

use Auth;
use Closure;
use Session;
use Illuminate\Http\Response;

class CheckUserPermission
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
            return $next($request);
    }
}
