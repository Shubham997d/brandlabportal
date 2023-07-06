<?php

namespace App\Base\Policies;

use App\Base\Models\UserRequest;
use App\Base\Models\User;
use App\Authorization\Authorization;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserRequestPolicy
{
    use HandlesAuthorization;

     /**
     * Determine whether the user can create request.
     *
     * @return mixed
     */
    public function canCreateRequest(User $user)
    {  
        return (new Authorization(auth()->user()))->userHasPermissionTo(request('request_type'), 'user-request');
    }
}
