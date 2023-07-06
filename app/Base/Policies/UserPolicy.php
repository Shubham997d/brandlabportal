<?php

namespace App\Base\Policies;

use App\Base\Models\User;
use App\Authorization\Authorization;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can invite new user.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function invite(User $user)
    {
        return (new Authorization($user))->userHasPermissionTo('invite', 'user');
    }

    /**
     * Determine whether the user can add user to a group.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function add(User $user)
    {
        return (new Authorization($user))->userHasPermissionTo('add', 'member');
    }

    /**
     * Determine whether the user can remove user to a group.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function remove(User $user)
    {
        return (new Authorization($user))->userHasPermissionTo('remove', 'member');
    }

     /**
     * Determine whether the user can remove user to a group.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorization($user))->userHasPermissionTo('index', 'user');
    }

     /**
     * Determine whether the user can edit other user's profile
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function profileUpdate(User $user)
    {
        if((new Authorization($user))->userHasPermissionTo('profile-update', 'user')){
                return true;
        }else{
                $requestedUser = User::where('username',request('username'))->first();
                if (isset($requestedUser->username) && auth()->user()->username == $requestedUser->username ) {
                    return true;
                }else{
                    return false;
                }
        }
    }

    /**
     * Determine whether the user can edit other user's account
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function accountUpdate(User $user)
    {
        if((new Authorization($user))->userHasPermissionTo('account-update', 'user')){
            return true;
        }else{
            return false;     
        }
        
    }


    /**
     * Determine whether the user can delete account
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function deleteAccount(User $user,$user_id)
    {        
        if ((int) $user_id === (int) $user->id) { //Don't let users delete themselves accidentally
            return false;
        }
        return (new Authorization($user))->userHasPermissionTo('delete-account', 'user');
    }
    
}
