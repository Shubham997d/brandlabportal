<?php

namespace App\Base\Http\Controllers\Auth;

use App\Base\Models\Role;
use App\Base\Models\User;
use App\Base\Models\Token;
use App\Base\Models\Invite;
use Illuminate\Http\Request;
use App\Office\Models\Office;
use App\Base\Notifications\UserRegistered;
use App\Base\Notifications\UserRegisteredUpdateAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Base\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Mail\Mailable;
use App\Base\Notifications\UserRegistered as UserRegisteredNotification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use \Illuminate\Foundation\Auth\RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array                                      $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|email:rfc,dns|max:255|unique:users',
            'job_title'=> 'required|string|max:255',
            'salary'   => 'required',            
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
            'role_id' => 'required',
            'avatar' => 'required|mimes:jpeg,png,jpg|max:2048'
        ]
    );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        // dd($data);
        return User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'designation'=> $data['job_title'],
            'yearly_salary'=> $data['salary'],
            'salary'   => getHourlySalaryFormAnnualSalary($data['salary']),
            'role_id'  => $data['role_id'],
            'password' => Hash::make($data['password']),
            'active'   => 0            
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @param  mixed                     $token
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {    
        //get roles for all users 
            
        $roles = Role::get(['id','name','slug']);
        $user_inputs = array();                
        return view('auth.register',compact('roles','user_inputs'));        
    }

    /**
     * @param Request $request
     * @param $token
     */
    public function confirmNewRegistration(Request $request)
    {
        if ($request->_token) {
            // dd($request->all());    
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                $errors = $validator->messages()->get('*');
                $user_inputs = $request->all();
                $roles = Role::get(['id','name','slug']);                
                return view('auth.register',compact('roles','user_inputs','errors'));                               
                
            } else {                
                $user = $this->create($request->all());            
                $user->update([
                    'avatar' => $request->file('avatar')->storeAs('avatars', $user->username . '.png', ['disk' => 'public']),
                ]);   
                $this->createUserToken($request->all());                
                $this->sendAdminEmailRegardingNewUserRegistration($user,$request->all());                
                return redirect('register/completed');
            } 
            
        }
    }

    /**
     * @param Request $request
     * @param $token
     */
    public function registerViaLink(Request $request, $token)
    {
        $invite = Invite::where('link', url('register/invite-link/' . $token))->first();
        if ($invite) {
            $this->register($request);
            $user = User::whereEmail($request->email)->first();
            $role = Role::find($invite->role_id);
            $user->role_id = $role->id;
            $user->save();
            return redirect('/');
        }

        abort(403);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed                    $user
     * @return void
     */
    protected function registered(Request $request, $user)
    {
        $office = Office::where('name', 'Headquarter')->first();
        if ($office) {
            $user->offices()->attach($office->id);
        }
        Mail::to($user->email)
            ->send(new UserRegistered($user));
        Notification::send($this->getRecipients(), new UserRegisteredNotification($user));
    }

    private function getRecipients()
    {
        return User::whereHas('role', function ($query) {
            $query->where('slug', 'owner')
                  ->orWhere('slug', 'admin');
        })->get();
    }

    public function registrationCompleted()
    {   
        $message = array();
        $message['heading'] = 'Thank you for signing up';
        $message['content'] = 'Your verification will be reviewed shortly by an admin';
        return view('auth.completed',compact('message'));
    }

    public function sendAdminEmailRegardingNewUserRegistration($user,$user_signup_data)
    {   
        $mail_data = $user_signup_data;
        $user_token = Token::where('email',$user->email)->first()->token;        
        $role = Role::where('id',$user_signup_data['role_id'])->first();
        if($role){
            $mail_data['role_name'] = $role->name;    
        }else{
            $mail_data['role_name'] = Role::where('role_id',3)->first()->name; // Member Role ID Need to update in future 
        }
        $mail_data['role_name'] = $role->name;
        $mail_data['yearly_salary'] = $user->yearly_salary;
        $mail_data['avatar_path'] = config('app.url').'/'.$user->avatar;
        $mail_data['accept'] = config('app.url').'/data/users/'.$user->id.'/status-update/active/'.$user_token;
        $mail_data['deny'] = config('app.url').'/data/users/'.$user->id.'/delete-user/'.$user_token;
        unset($mail_data['avatar']);
        $mail_data = (object) $mail_data;
        foreach (explode(',', env('MAIL_ADDRESSES')) as $email) { 
            $admin = User::where('email',$email)->first();
            $admin->notify(new UserRegisteredUpdateAdmin($user, ['actionSlug' => config('constants.notifications.action_slug')[48]],['user' => $mail_data]));
        }  
    }    

    public function createUserToken($data)
    {
        return Token::create([
                    'token' => $data['_token'],
                    'role_id' => $data['role_id'],
                    'email' => $data['email']                
                ]);
    }
    
}
