<?php

namespace App\Base\Http\Controllers;

use App\Base\Http\Requests\UpdateUserProfile;
use App\Base\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function update(UpdateUserProfile $request)
    {
        $request['salary'] = getHourlySalaryFormAnnualSalary($request->yearly_salary);
        auth()->user()->update($request->all());
        sendDynamicNotification(null, [ 'group_type' => config('constants.notifications.group_type')[4], 'action_slug' => config('constants.notifications.action_slug')[42], 'visible_to_admin' => true, 'visible_to_user' => false ]);
        return response()->json([
            'status'  => 'success',
            'message' => localize('misc.Account profile are updated'),
        ]);
    }

    public function updateUser(Request $request)
    {    
        $this->authorize('profileUpdate',User::class);
        User::where(['username' => request('username')])->update($request->all());       
        sendDynamicNotification(request('username'), [ 'group_type' => config('constants.notifications.group_type')[4], 'action_slug' => config('constants.notifications.action_slug')[42], 'visible_to_admin' => true, 'visible_to_user' => false ]);
        return response()->json([
            'status'  => 'success',
            'message' => localize('misc.Account profile updated'),
        ]);
        
    }
}
