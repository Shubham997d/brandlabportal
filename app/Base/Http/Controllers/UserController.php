<?php

namespace App\Base\Http\Controllers;

use Exception;
use DateTimeZone;
use App\Base\Models\User;
use App\Base\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Base\Repositories\UserRepository;
use App\Base\Notifications\UserRegistered;
use App\Base\Models\Role;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\ProjectUser;
use App\TaskManager\Models\Task;
use App\SalesPipeline\Models\Deals;
use Illuminate\Support\Facades\Notification;
use App\Base\Notifications\SendInvitationToRegister;
use Cache;

class UserController extends Controller
{
    public function index(UserRepository $userRepository, Request $request)
    {
        try {
            if(auth()->user()->can('index',User::class) === true){
                $withDeleted = isset($request->withDeleted) ? $request->withDeleted : true;
                $users = $userRepository->getAllUsers($withDeleted);      
                return response()->json([
                    'status'  => 'success',
                    'users'   => $users,
                ]);               
            }else{
                return response()->json([
                    'status'  => 'error',
                    'message' => config('constants.miscellaneous.permissions')[403]
                ],403);
            }

        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
        
    }

    public function sentInvitationToRegister(Request $request)
    {
        try {
            $this->authorize('invite', User::class);
            $data = $request->validate([
                'emailAddress' => 'required|email',
            ]);
            if (! User::where('email', $data['emailAddress'])->first()) {
                // need to fix this 
                Notification::route('mail', $data['emailAddress'])                
                ->notify(new SendInvitationToRegister(auth()->user(),['to_email' => $data['emailAddress'] ,'send_only_mail' => true ])); 

                Notification::send(auth()->user(), new SendInvitationToRegister(auth()->user(),['to_email' => $data['emailAddress'], 'send_only_mail' => true ]));
                return response()->json([
                    'status'  => 'success',
                    'message' => localize('misc.Invitation sent successfully'),
                ]);
            }
            return response()->json([
                'status'  => 'error',
                'message' => localize('misc.Email already exist'),
            ], 409);
        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function show(User $user)
    {

        try {
            $user->load('projects');     

            return response()->json([
                    'user'      => $user,
                    'status'  => 'success',            
                ]);
            } catch (Exception $exception) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $exception->getMessage(),
                ]);
            }
    }

    public function checkUsername(Request $request)
    {
        try {
            if ($this->usernameExists($request->username)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => localize('misc.Username exists'),
                ], 409);
            }

            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.Username does not exist'),
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function me()
    {
        return response()->json(app('auth')->guard()->user());
    }

    private function usernameExists($username)
    {
        return (auth()->user()->username !== $username) && User::where('username', $username)->exists();
    }


    public function updateUserStatus($user,$toggle,$token,Request $request)
    {
        try {
        $user = User::where('id',$user)->first();
        if ($user) {
            $user_token = Token::where('email',$user->email)->first()->token;
            
            if ($user_token == $token ) {                
                if ($toggle == 'inactive') {
                    if ($user->active == config('constants.user.status.active')) {
                        $user->active = config('constants.user.status.inactive');
                        $user->save();                        
                    }
                }
                if ($toggle == 'active') {    
                    if ($user->active == config('constants.user.status.inactive')) {
                        $user->active = config('constants.user.status.active');
                        $user->save();
                        $user->avatar_path = config('app.url').'/'.$user->avatar;
                        $role = Role::find(($user->role_id));       
                        $user->role_name = $role->name;                        
                        $user->notify(new UserRegistered($user, ['actionSlug' => config('constants.notifications.action_slug')[49]],['user' => $user,'authUser' => auth()->user()]));  
                    }     
                }
               
                $message = 'User Status Updated to '.$toggle;
                $class = 'success';
                return view('auth.user-updated',compact('message','class'));                 
            }
        }
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $class = 'error';
            return view('auth.user-updated',compact('message','class'));             
        }      
        
    }


    public function deleteUser($user_id,$token,Request $request)
    {
        try { 
        $user = User::where('id',$user_id)->first();
        if ($user) {
                $user_token = Token::where('email',$user->email)->first()->token;
                if ($user_token == $token ) {               
                    Token::where('email',$user->email)->delete();
                    $tasks = Task::where('assigned_to',$user->id)->exists();
                    $projectUsersDuration = ProjectUsersDuration::where('user_id',$user->id)->exists();
                    $projectUsers = ProjectUser::where('user_id',$user->id)->exists();                    
                    $deals = Deals::where('owner_id',$user->id)->exists();                    
                    if ($tasks === false && $projectUsersDuration === false && $projectUsers === false && $deals === false) {                        
                            $user->delete();
                    }else{ // if any entry belongs to user on the project then soft delete it
                        $user->deleted = true;
                        $user->save();
                    }
                    sendDynamicNotificationNow($user, [ 'group_type' => config('constants.notifications.group_type')[4], 'action_slug' => config('constants.notifications.action_slug')[50], 'visible_to_admin' => true, 'visible_to_user' => false ]); 
                    $message = 'User Deleted Successully';
                    $class = 'success';
                    return view('auth.user-updated',compact('message','class'));  
                }
            }
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $class = 'error';
            return view('auth.user-updated',compact('message','class'));   
        }      
        
    }

    public function deleteUserFromDashboard($user_id,UserRepository $userRepository){
        
            $this->authorize('deleteAccount',[User::class,$user_id]);
            $user = $userRepository->softDeleteUser($user_id);
            sendDynamicNotification($user, [ 'group_type' => config('constants.notifications.group_type')[4], 'action_slug' => config('constants.notifications.action_slug')[43], 'visible_to_admin' => true, 'visible_to_user' => false ]); 
            
            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.User deleted Successully.'),
            ]);   

        
    }

    public function getAvailableUsers(UserRepository $userRepository, Request $request)
    {
        try {
            $users = $userRepository->getAvailableUsers();      
            return response()->json([
                'status'  => 'success',
                'users'   => $users,
            ]);              

        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }        
    }

    public function getOnlineUsers()
    {
        $users = User::all();
        $online_users = array();
        foreach ($users as $user) {
            if (Cache::has('user-is-online-' . $user->id)){
                    $online_users[] = $user->id;
             }         
        }

        return response()->json([
            'status'  => 'success',
            'online_users' => $online_users
        ]);
    }
    

    
}


//ProjectUsersDuration::where('user_id',$user_id)->delete(); // User Project Duration
//ProjectUser::where('user_id',$user_id)->delete(); // User Project Duration
//Task::where('assigned_to',$user_id)->delete(); // Task