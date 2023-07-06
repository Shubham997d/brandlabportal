<?php

namespace App\Base\Http\Controllers;

use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
    public function store(Request $request)
    {
        try {
            auth()->user()->update([
                'avatar' => $request->file('avatar')->storeAs('avatars', $request->user()->username . '.png', ['disk' => 'public']),
            ]);

            sendDynamicNotification(null, [ 'group_type' => config('constants.notifications.group_type')[4], 'action_slug' => config('constants.notifications.action_slug')[45], 'visible_to_admin' => true, 'visible_to_user' => false ]); 

            return response()->json([
                'status'     => 'success',
                'message'    => 'Avatar uploaded successfully',
                'avatar'     => auth()->user()->avatar,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
