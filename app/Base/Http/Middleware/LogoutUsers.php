<?php

namespace App\Base\Http\Middleware;

use Auth;
use Closure;
use Session;
use Illuminate\Http\Response;

class LogoutUsers
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
            $user = Auth::user();

            // $isUserLoggedIn = Session::get('isUserLoggedIn');
                        
            // // Handle Deleted User

            // if(empty($user) && $isUserLoggedIn == true){    
            //     Session::forget('isUserLoggedIn');
            //     return response()->json('Unauthenticated',401);
            // }

            if ($user) {
                if($user->active == false || $user->deleted == true ){
                    Auth::logout();                    
                }
            }
            return $next($request);
    }
}
