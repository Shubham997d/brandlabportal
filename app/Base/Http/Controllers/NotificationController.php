<?php

namespace App\Base\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'status'        => 'success',
            'data' => auth()->user()->notifications()->where('visible_to_user',true)->orderBy("created_at", "desc")->paginate(10),
            'unreadNotificationsCount' => auth()->user()->unreadNotifications()->where('visible_to_user',true)->count()
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $read = false;
        if(isset($request->id)){
            $notification = auth()->user()->notifications()->where('id', $request->id)->first();
            if(isset($notification->read_at)){
                $notification->update(['read_at' => null]);
                $message = 'Notification is marked as unread';
                $read = false;
            }else{
                $notification->update(['read_at' => Carbon::now()]);
                $message = 'Notification is marked as read';
                $read = true;
            }
        }
        if(isset($request->mark_all)) {
            auth()->user()->unreadNotifications->markAsRead();
            $message = 'Notifications marked as read';
            $read = true;
            $markAll = true;
        }
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'read' => $read,
            'markAll' => (isset($markAll) && $markAll == true) ? true : false
        ]);
    }
}
