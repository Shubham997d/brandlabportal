<?php

namespace App\Base\Http\Controllers;

use App\Base\Models\User;
use App\Base\Http\Requests\UpdateUserAccount;

class UserAccountController extends Controller
{
    public function update(UpdateUserAccount $request)
    {
        $user = auth()->user();
        if ($this->emailDoesNotExist($request)) {
            $user->email = $request->get('email');
        }
        if (auth()->user()->username === 'guest') {
            return response()->json([
                'status'  => 'error',
                'message' => localize('misc.Username/Password is not updatable for this account'),
            ]);
        }
        if ($this->usernameDoesNotExist($request)) {
            $user->username = $request->get('username');
        }
        if ($request->get('new_password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->get('new_password'));
        }
        $user->save();
        sendDynamicNotification(null, [ 'group_type' => config('constants.notifications.group_type')[4], 'action_slug' => config('constants.notifications.action_slug')[44], 'visible_to_admin' => true, 'visible_to_user' => false ]); 
        return response()->json([
            'status'  => 'success',
            'message' => localize('misc.Account details are updated'),
        ]);
    }

    private function emailDoesNotExist($request)
    {
        return $request->email && ! User::where('email', $request->email)->exists();
    }

    private function usernameDoesNotExist($request)
    {
        return $request->username && ! User::where('username', $request->username)->exists();
    }
}
