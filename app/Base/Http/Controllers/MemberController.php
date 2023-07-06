<?php

namespace App\Base\Http\Controllers;

use App\Base\Models\User;
use App\Base\Utilities\GroupTrait;
use App\Base\Exceptions\UserIsNotMember;
use App\Base\Notifications\BecameNewMember;
use App\Base\Exceptions\UserIsAlreadyMember;
use App\Base\Notifications\RevokedMembership;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\Project;
use App\TaskManager\Models\Task;

class MemberController extends Controller
{
    use GroupTrait;

    public function store()
    {
        $this->authorize('add', User::class);
        $group = $this->getGroupModel();
        if ($this->userIsAlreadyMember($group, request('user_id'))) {
            throw new UserIsAlreadyMember;
        }
        $user = User::select(['id', 'name', 'username', 'avatar'])->find(request('user_id'));
        $group->members()->save($user);

        $projectUsersDuration = new ProjectUsersDuration;
        $projectUsersDuration->insert(['project_id' => request('group_id'), 'user_id' => request('user_id'),'duration' => 0]);        
        sendDynamicNotification(['project' => Project::where('id',request('group_id'))->first(), 'member' => $user], [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[46], 'visible_to_admin' => true, 'visible_to_user' => false ]); 

        // $user->notify(new BecameNewMember($group, auth()->user()));

        return response()->json([
            'status'   => 'success',
            'message'  => localize('misc.User added', ['type' => request('group_type')]),
            'user'     => $user,
        ]);
    }

    public function destroy()
    {
        $this->authorize('remove', User::class);
        $group = $this->getGroupModel();
        $user = $group->members()->where('user_id', request('user_id'))->first();
        throw_if(! $user, new UserIsNotMember());

        if (request('group_type') == 'project') {
            if ($group->owner_id != (int) request('user_id')) // Don't let user delete owner from project
            {
                    $projectUsersDuration = new ProjectUsersDuration;
                    $projectUsersDuration->where(['project_id' => request('group_id'), 'user_id' => request('user_id')])->delete();
                    $task = new Task;
                    $task = $task->where(['taskable_id' => request('group_id'), 'assigned_to' => request('user_id')])->delete();        
                    $group->members()->detach($user);                    
                    sendDynamicNotification(['project' => Project::where('id',request('group_id'))->first(), 'member' => $user], [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[47], 'visible_to_admin' => true, 'visible_to_user' => false ]); 
                    return response()->json([
                        'status'   => 'success',
                        'message'  => localize('misc.User removed', ['type' => request('group_type')]),
                        'user'     => $user,
                    ]);                    
                } 
                else{
                    return response()->json([
                        'status'   => 'error',
                        'message'  => 'You cannot remove project owner from the project'
                    ]);
                }
            }

    }

    private function userIsAlreadyMember($group, $userId)
    {
        return $group->members()->where('user_id', $userId)->exists();
    }

    public function index()
    {
        $group = $this->getGroupModel();

        return response()->json([
            'status'  => 'success',
            'items'   => count($group->members),
            'members' => $group->members,
        ]);
    }
}
