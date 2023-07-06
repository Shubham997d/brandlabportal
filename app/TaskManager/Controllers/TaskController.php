<?php

namespace App\TaskManager\Controllers;

use Exception;
use App\TaskManager\Models\Task;
use App\Base\Utilities\GroupTrait;
use App\Base\Http\Controllers\Controller;
use App\Base\Repositories\MentionRepository;
use App\TaskManager\Requests\UpdateTaskRequest;
use App\TaskManager\Repositories\TaskRepository;
use App\TaskManager\Requests\ValidateTaskCreation;
use App\Project\Models\ProjectUser;
use App\Project\Models\Project;
use request;
class TaskController extends Controller
{
    use GroupTrait;

    public function store(ValidateTaskCreation $request, TaskRepository $repository, MentionRepository $mentionRepository)
    {
        try {
            $this->authorize('create', Task::class);
            $data = $request->all();
            $task = $repository->create($data);

            $task->tags()->attach(request('labels'));
            if (request('mentions')) {
                $mentionRepository->create('task', $task);
            }
            $task->load('user:id,avatar,username,name', 'status', 'tags:tag_id,label');           
            
            sendDynamicNotification(['project' => Project::where('id',$data['group_id'])->first(),'task' => $task ], [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[39], 'visible_to_admin' => true, 'visible_to_user' => false ]);

            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.New task has been created'),
                'task'    => $task,
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function show(Task $task)
    {
       // $this->authorize('view', $task);
        $task->load('user');

        return response()->json([
            'status' => 'success',
            'task'   => $task,
        ]);
    }

    public function index(TaskRepository $repository)
    {            
            $this->authorize('index', [Task::class, new Project]);
            $tasks = $repository->getAllTaskWithAssigneeAndDuration(request('resource_type'), request('resource_id'));
    
            return response()->json([
                'status'   => 'success',
                'auth_user_id' => auth()->user()->id,
                'auth_user_role_id' => auth()->user()->role_id,
                'total'    => count($tasks),
                'tasks'    => $tasks,
            ]);
       
    }

    public function delete(UpdateTaskRequest $request,Task $task )
    {
        $this->authorize('delete', $task);        
        $task->load('user:id,avatar,username');        
        sendDynamicNotificationNow(['project' => Project::where('id',$request->group_id)->first(),'task' => $task ], [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[41], 'visible_to_admin' => true, 'visible_to_user' => false ]);
        $task->delete();        
        return response()->json([
            'status'  => 'success',
            'message' => localize('misc.The task has been deleted'),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->all();
        $this->authorize('update', $task);
        $task->update($data);
        $task->tags()->sync(request('labels'));
        $task->load('user:id,avatar,username', 'status', 'tags');

        sendDynamicNotification(['project' => Project::where('id',$data['group_id'])->first(), 'task' => $task], [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[40], 'visible_to_admin' => true, 'visible_to_user' => false ]);

        return response()->json([
            'status'  => 'success',
            'message' => localize('misc.Task has been updated'),
            'task'    => $task,
        ]);
    }
}
