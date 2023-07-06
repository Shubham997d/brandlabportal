<?php

namespace App\Base\Http\Controllers\Auth;

use App\Base\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use \Illuminate\Foundation\Auth\AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    protected function validateLogin(Request $request)
    {
        // Don't allow deleted users to login
        $this->validate($request, [
            $this->username() => 'exists:users,' . $this->username() . ',active,1',
            $this->username() => 'exists:users,' . $this->username() . ',deleted,0',
            'password' => 'required|string',
        ], [
            $this->username() . '.exists' => 'The selected email is invalid or the account is deleted/disabled. Please contact the admin for more information'
        ]);
    }

    protected function authenticated(Request $request, $user)
    {   
        // Added For Handling Deleted User
        // Session::put('isUserLoggedIn', true);
        // send to projects with in progress status  
        return redirect('/');
    }
    

    
    
}
