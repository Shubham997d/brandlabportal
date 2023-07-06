<?php

namespace App\Project\Models;

use App\Base\Models\Role;
use App\Base\Models\User;
use App\Base\Models\Cycle;
use App\Base\Models\Group;
use App\Base\Models\Permission;
use App\Base\Contracts\HasMembers;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\ProjectCommissionUser;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Group implements HasMembers
{
    protected $type = 'project';

    protected $fillable = ['name', 'project_manager_id', 'office_id', 'team_id', 'owner_id','currency_code','public','status','cost','contact_term','monthly_usage_fee','deadline','completed_at','deal_id'];

    protected $appends = ['project_manager_username'];
    /**
     * @return BelongsToMany
     */
    public function members(): BelongsToMany 
    {   
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->with('userDuration');
    }    

    public function routeNotificationFor()
    {
        return '';
    }

    public function cycles()
    {
        return $this->morphMany(Cycle::class, 'cyclable');
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'group', 'role_has_permission');
    }

    public function roles()
    {
        return $this->morphToMany(Role::class, 'group', 'role_has_permission');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function settings()
    {
        return $this->hasOne(ProjectSetting::class, 'project_id')->withDefault();
    }

    public function makePublic()
    {
        $this->update(['public' => true]);
    }

    public function makePrivate()
    {
        $this->update(['public' => false]);
    }

    public function durations()
    {
        return $this->hasMany(ProjectUsersDuration::class, 'project_id');
    }
    public function commissionUsers()
    {
        return $this->hasMany(ProjectCommissionUser::class, 'project_id');
    }

    public function getProjectManagerUsernameAttribute(){       
        if (isset($this->project_manager_id)) {
            try {
                // dd($this->project_manager_id);
                $user = User::where('id',$this->project_manager_id )->first();

                return isset($user) ? $user->username : 'N/A';
         
            } catch (Exception $exception) {
                return 'N/A';
            }
        }
    } 

    public function projectCategories(){
        return $this->hasMany('App\Project\Models\ProjectCategoriesData','project_id', 'id')->with(['categoryDetails:id,name,slug']);
    }    

}
