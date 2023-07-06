<?php

namespace App\Base\Repositories;

use App\Base\Models\User;
use App\Project\Models\Project;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\ProjectUser;
use DB;
use App\SalesPipeline\Models\Deals;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }    

    public function getAllUsers($withDeletedUsers = true, array $columns = ['id','name','username','avatar','designation','colour','deleted','role_id','salary','active'])
    {   
        
        $this->model =  (filter_var($withDeletedUsers, FILTER_VALIDATE_BOOLEAN) == false) ? $this->model->where('deleted',false) : $this->model;
        $users_details = $this->model->with('userRole')->with('role')->with('userProjectDuration')->get($columns); 
        // Add Total User duraiotn & project name in loop 
        if (isset($users_details) && count($users_details) > 0) {
            
            foreach ($users_details as $index => $users_detail) {
                $user_total_duration = $this->getUserTotalProjectDuration($users_detail->id);
                $users_detail->userTotalDuration = $user_total_duration;
                foreach ($users_detail->userProjectDuration as $userProjectDuration) {                    
                    $user_project  = Project::where('id',$userProjectDuration->project_id)->first(); 
                    if ($user_project) {
                        $userProjectDuration->project_name = $user_project->name;                        
                        $userProjectDuration->is_project_manager = (isset($user_project->project_manager_id) && $user_project->project_manager_id == $users_detail->id) ? true : false;
                    }                    
                }
                $users_detail->hasDeals = Deals::where('owner_id',$users_detail->id)->exists();
            }
           
        }
        
        return $users_details;
    }

    public function getUserByEmail(string $email)
    {
        return $this->model->where('email', $email)->select(['name', 'username', 'bio', 'designation', 'email', 'timezone', 'week_start', 'lang', 'location','colour'])->first();
    }

    public function getUserById($id)
    {
        return $this->model->where('id', $id)->select(['name', 'username', 'bio', 'designation', 'email', 'timezone', 'week_start', 'lang', 'location','colour'])->first();
    }

    public function getUserNameAndColourById($id)
    {
        return $this->model->where('id', $id)->select(['username','colour'])->first()->toArray();
    }

    public function getUserTotalProjectDuration($user_id)
    {
         $projectUsersDuration = new ProjectUsersDuration;
         $total_duration = $projectUsersDuration->where('user_id',$user_id)->sum('duration');
         if (empty($total_duration)) {
            $total_duration = 0;
         }
         return (int) $total_duration;
    }


    public function getAvailableUsers(array $column = ['id as value','username as text','avatar'])
    {
        return $this->model->where('active',true)->where('deleted',false)->get($column);
    }

    public function softDeleteUser($user_id)
    {
            $user = $this->model->where('id',$user_id)->first();     
            $user->deleted = true;
            $user->active = config('constants.user.status.inactive');
            $user->save();
            return $user;
    }
}
