<?php

namespace App\Project\Policies;

use App\Base\Models\User;
use App\Project\Models\Asset;
use App\Authorization\Authorization;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Project\Models\ProjectUser;
use App\Project\Models\Project;

class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view asset.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function view(User $user, Asset $asset)
    {
        return (new Authorization($user))->userHasPermissionTo('view', 'asset');
    }

    /**
     * Determine whether the user can create asset.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {    
        if ((new Authorization($user))->userHasPermissionTo('create', 'asset') === true) {
            return true;
        }else{  // Allow member of project
            return auth()->user()->isUserMemberOfProject(request('project_id'));
        }
        
    }

    /**
     * Determine whether the user can update the asset.
     *
     * @param  \App\Base\Models\User $user
     * @return mixed
     */
    public function update(User $user)    {
        if ((new Authorization($user))->userHasPermissionTo('update', 'asset') === true) {
            return true;
        }else{ // Allow member of project
            return auth()->user()->isUserMemberOfProject(request('project_id'));
        }
    }

    /**
     * Determine whether the user can delete the asset.
     *
     * @param  \App\Base\Models\User $user     
     * @return mixed
     */
    public function delete(User $user)
    {    
        if ((new Authorization($user))->userHasPermissionTo('delete', 'asset') === true) {
            return true;
        }else{ // Allow member of project
            return auth()->user()->isUserMemberOfProject(request('project_id'));
        }
    }
  

    public function index(User $user,Project $project)
    {   
        $projectIsCompleted = $project->where('status',config('constants.project.status.completed'))->where('id',request('project_id'))->exists();
        if((new Authorization($user))->userHasPermissionTo('index', 'asset') == true){            
            if (auth()->user()->isAdmin() === true) {
                return true;
            }else{
                return ($projectIsCompleted === true) ? false : true;  // only allow users to see in-progress projects
            }
        }else{ // check if user is part of project (for freelancer role)            
            if ($projectIsCompleted === false) {
                return auth()->user()->isUserMemberOfProject(request('project_id'));
            }else{
                return false;
            }
        }
    }
}





