<?php

namespace App\TaskManager\Controllers;

use Exception;
use App\TaskManager\Models\Task;
use App\Base\Utilities\GroupTrait;
use App\Base\Http\Controllers\Controller;
use App\TaskManager\Requests\UpdateTaskRequest;
use App\TaskManager\Repositories\TaskRepository;
use App\TaskManager\Requests\ValidateTaskCreation;
use App\Base\Models\User;
use App\Base\Repositories\UserRepository;

class TaskDurationController extends Controller
{
    use GroupTrait;

    public function index(TaskRepository $repository)
    {
       try {
            $group = $this->getGroupModel();
            if ($group->notOpenForPublic()) {
                abort(401);
            } elseif (auth()->user()) {
                $this->authorize('view', $group);
            }
            $tasks = $repository->getTotalDurationWithAssignee(request('resource_type'), request('resource_id'));

            return response()->json([
                'status'   => 'success',
                'auth_user_id' => auth()->user()->id,
                'auth_user_role_id' => auth()->user()->role_id,
                'total'    => count($tasks),
                'tasks'    => $tasks,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Something went wrong',
            ]);
        }
    }

    public function userInfo(UserRepository $userRepository, $id)
    {
        
        $user = $userRepository->getUserById($id);

        return response()->json([
            'status'  => 'success',
            'user'   => $user,
        ]);
    }

   
}
