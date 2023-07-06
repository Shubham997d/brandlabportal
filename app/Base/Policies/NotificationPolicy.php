<?php

namespace App\Base\Policies;

use App\Base\Models\UserRequest;
use App\Base\Models\User;
use App\Base\Models\Notification;
use App\Authorization\Authorization;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

     /**
     * Determine whether the user can view the activity log page
     *
     * @return mixed
     */
    public function index(User $user)
    {  
        return (new Authorization(auth()->user()))->userHasPermissionTo('index', 'activiy-log');
    }
}
