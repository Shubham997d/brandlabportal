<?php

namespace App\Project\Policies;

use App\Base\Models\User;
use App\Project\Models\Project;
use App\Project\Models\ProjectUser;
use App\Authorization\Authorization;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the project.
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Project\Project  $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {   
        if((new Authorization($user))->userHasPermissionTo('view', 'project') == true){
            return true;
        }else{ // check if user is part of project (for freelancer role)          

            $projectIsCompleted = $project->where('status',config('constants.project.status.completed'))->where('id',$project->id)->exists();
            if ($projectIsCompleted === false) { // Allow only in-progress projects
                $projectUser = new ProjectUser; 
                $userExistInProject = $projectUser->where('user_id',auth()->user()->id)->where('project_id',$project->id)->first();                        
                return isset($userExistInProject) ? true : false;                
            }else{
                return false;
            }
        }
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorization($user))->userHasPermissionTo('create', 'project');
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Project\Project  $project
     * @return mixed
     */
    public function delete(User $user)
    {
        // return (new Authorization($user))->userHasPermissionTo('delete', 'project', $project->id, false, 'project', $project->id);
        return (new Authorization($user))->userHasPermissionTo('delete', 'project');
    }

    /**
     * Determine whether the user can change the project settings.
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Project\Project  $project
     * @return mixed
     */
    public function settings(User $user, Project $project)
    {
        return $user->isMember(request('group_type'), request('group_id')) && ($user->role->slug === 'owner' || $user->role->slug === 'admin');
    }


     /**
     * Determine whether the user can change the view all projects
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Project\Project  $project
     * @return mixed
     */
    public function index(User $user)
    {       
        // Allow only admin to access to completed projects && inprogress to all the user types
        if((new Authorization($user))->userHasPermissionTo('index', 'project') === true){
            if(request()->status == config('constants.project.status.in_progress')){
                return true;
            }  
            if((int) request()->status == (int) config('constants.project.status.completed') && auth()->user()->isAdmin() == true){
                 return true;
            }else{
                 return false;
            }          
        }
    }

     /**
     * Determine whether the user can change the update a project
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Project\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        if((new Authorization(auth()->user()))->userHasPermissionTo('update', 'project') == true){
            return true;
        }else{   
            /* check if user is project manager and in sales team (for sales user) */
            if(auth()->user()->inSalesteam() === true){
                $can = $project->where('status',config('constants.project.status.in_progress'))->where('id',$project->id)->where('project_manager_id',auth()->user()->id)->exists();
                return ($can == true) ? true : false;                
            }else{
                return false;
            }
            
        }
    }


      /**
     * Determine whether the user can change the view a project details
     *
     * @param  \App\Base\Models\User $user
     * @param  \App\Project\Project  $project
     * @return mixed
     */
    public function viewProjectDetails(User $user)
    {
        return (new Authorization($user))->userHasPermissionTo('viewProjectDetails', 'project');
    }

    
}
