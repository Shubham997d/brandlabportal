<?php

namespace App\TaskManager\Policies;

use App\Base\Models\User;
use App\TaskManager\Models\Task;
use App\Authorization\Authorization;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Project\Models\ProjectUser;
use App\Project\Models\Project;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view task.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function view(User $user, Task $task)
    {
        return (new Authorization($user))->userHasPermissionTo('view', 'task');
    }

    /**
     * Determine whether the user can create task.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {    
        if ((new Authorization($user))->userHasPermissionTo('create', 'task') === true) {
            return true;
        }else{  // Allow task owner
            return (auth()->user()->id === request('assigned_to')) ? true : false;
        }
        
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Base\Task        $task
     * @return mixed
     */
    public function update(User $user, Task $task)
    {

        if ((new Authorization($user))->userHasPermissionTo('update', 'task') === true) {
            return true;
        }else{ // Allow task owner
            return (auth()->user()->id === request('assigned_to') || auth()->user()->id === $task->assigned_to ) ? true : false;
        }
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Base\Task        $task
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {    
        if ((new Authorization($user))->userHasPermissionTo('delete', 'task') === true) {
            return true;
        }else{ // Allow task owner
               return (auth()->user()->id === $task->assigned_to) ? true : false;
        }
    }


    /**
     * Determine whether the user can view tasks.
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Base\Task        $task
     * @return mixed
     */


    public function index(User $user,Project $project)
    {   
        $projectIsCompleted = $project->where('status',config('constants.project.status.completed'))->where('id',request('resource_id'))->exists();
        if((new Authorization($user))->userHasPermissionTo('index', 'task') == true){
            if (auth()->user()->isAdmin() === true) { // allow only admin to view completed projects
                return true;
            }else{
                return ($projectIsCompleted === true) ? false : true;
            }
        }else{ // check ifs user is part of project (for freelancer role)
            if ($projectIsCompleted === false) {
                $projectUser = new ProjectUser; 
                $userExistInProject = $projectUser->where('user_id',auth()->user()->id)->where('project_id',request('resource_id'))->first();                        
                return isset($userExistInProject) ? true : false;                
            }else{
                return false;
            }
        }
    }
}





