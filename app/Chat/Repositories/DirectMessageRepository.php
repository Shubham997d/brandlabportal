<?php

namespace App\Chat\Repositories;

use App\Chat\Models\DirectMessage;
use App\Chat\Models\Workspace;
use App\Chat\Models\WorkspaceMember;
use App\Chat\Models\Messages;
use App\Base\Models\User;

class DirectMessageRepository
{
    /**
     * @var mixed
     */
    protected $model;

    /**
     * @param DirectMessage $message
     */
    public function __construct(DirectMessage $message)
    {
        $this->model = $message;
    }

    /**
     * @param  mixed $data
     * @return mixed
     */
    public function saveMessage($data)
    {
        return $this->model->create([
            'body'           => $data['body'],
            'sender_id'      => auth()->user()->id,
            'receiver_id'    => $data['receiver_id'],
            'attachment_id'  => $data['attachment_id'] ?? null,
            'read_at'        => null,
        ]);
    }

    public function getAllDirectMessages($senderId, $recieverId)
    {
        return $this->model->where(['sender_id' => $senderId, 'receiver_id' => $recieverId])
                           ->orWhere(function ($query) use ($senderId, $recieverId) {
                               $query->where(['sender_id' => $recieverId, 'receiver_id' => $senderId]);
                           })
                           ->with('user:id,avatar,username')
                           ->orderBy('id', 'desc')
                           ->paginate(config('constants.chat.conversation.pagination'));
    }

    public function getLatestDirectMessage($senderId, $recieverId)
    {
        return $this->model->where(['sender_id' => $senderId, 'receiver_id' => $recieverId])
                           ->orWhere(function ($query) use ($senderId, $recieverId) {
                               $query->where(['sender_id' => $recieverId, 'receiver_id' => $senderId]);
                           })
                           ->with('user:id,avatar,username')
                           ->orderBy('id', 'desc')
                           ->first();
    }

    public function getDirectUsersWithLatestMessages()
    {    
            $result = [];
            $users = User::get(['id','username','avatar'])->except(auth()->id()); // don't get current auth user
            $count = 0;
            foreach ($users as $key => $user) {
                $result[$count]['id'] = $user->id;
                $result[$count]['username'] = $user->username;
                $result[$count]['avatar'] = $user->avatar;
                $result[$count]['latestMessage'] = $this->getLatestDirectMessage(auth()->user()->id, $user->id);
                $count++;
            }
            return $result;
    }
}
