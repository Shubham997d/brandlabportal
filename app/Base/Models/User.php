<?php

namespace App\Base\Models;

use App\Team\Models\Team;
use App\Office\Models\Office;
use App\Project\Models\Project;
use App\Project\Models\ProjectUsersDuration;
use App\TaskManager\Models\Task;
use App\Base\Utilities\Notifiable;
use App\Base\Models\Permission;
use App\Base\Models\UserRequest;
use App\Base\Models\RoleHasPermission;
use Laravel\Passport\HasApiTokens;
use App\Discussion\Models\Discussion;
use Lab404\Impersonate\Models\Impersonate;
use App\Project\Models\ProjectUser;
use App\Chat\Models\DirectMessage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Impersonate, HasApiTokens;

    protected $fillable = [
        'name', 'username', 'bio', 'designation','role_id', 'location', 'avatar', 'active', 'timezone', 'week_start', 'lang', 'email', 'password', 'colour','salary','yearly_salary','deleted'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function isDeleted(){
        return $this->deleted;
    }

    public function getAvatarAttribute($value)
    {
        return $value ? 'storage/' . $value : 'image/avatar.jpg';
    }

    public function team()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function project()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function userRequests()
    {
        return $this->hasMany(UserRequest::class, 'user_id');
    }
    public function office()
    {
        return $this->hasMany(Office::class, 'owner_id');
    }

    public function offices()
    {
        return $this->belongsToMany(Office::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function comment()
    {
        return $this->comments()->get();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }    

    public function task()
    {
        return $this->tasks()->get();
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'posted_by');
    }

    public function discussion()
    {
        return $this->discussions()->get();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function userRole()
    { 
        return $this->hasOne(Role::class,'id','role_id');
    }

    public function allowedPermissions($groupType = null, $groupId = null)
    {
        return $this->belongsToMany(Permission::class, 'allowed_permissions')->wherePivot('group_type', $groupType)->wherePivot('group_id', $groupId);
    }

    public function blockedPermissions($groupType = null, $groupId = null)
    {
        return $this->belongsToMany(Permission::class, 'blocked_permissions')->wherePivot('group_type', $groupType)->wherePivot('group_id', $groupId);
    }

    public function isAllowedTo($action, $resource, $groupRelated = false, $groupType = null, $groupId = null)
    {
        if ($groupRelated) {
            return $this->role->permissionsOnGroup($groupType, $groupId)->where(['action' => $action, 'resource' => $resource])->exists() ||
                $this->allowedPermissions($groupType, $groupId)->where(['action' => $action, 'resource' => $resource])->exists();
        }        
        return $this->role->permissions()->where(['action' => $action, 'resource' => $resource])->exists() ||
            $this->allowedPermissions()->where(['action' => $action, 'resource' => $resource])->exists();
    }

    public function isNotForbiddenTo($action, $resource, $groupType = null, $groupId = null)
    {
        return $this->blockedPermissions($groupType, $groupId)->where(['action' => $action, 'resource' => $resource])->doesntExist();
    }

    public function isMember($groupType, $groupId)
    {
        $groups = $groupType . 's';
        return $this->$groups()->where($groupType . '_id', $groupId)->exists();
    }

    public function isOwner($resource, $resourceId)
    {   
        return $this->$resource()->where('id', $resourceId)->first();
    }

    public function isAdmin()
    {
        return ($this->role->slug === 'admin') ? true : false;
    }
    public function isFreelancer()
    {
        return ($this->role->slug === 'freelancer') ? true : false;
    }

    public function inSalesteam()
    {
        return ($this->role->slug === 'sales_team') ? true : false;
    }

    public function isOwnerOfDeal($resource, $resourceId)
    {
        return $this->$resource()->where('owner_id', $resourceId)->first();
    }

    public function canImpersonate()
    {
        return $this->role->slug === 'owner';
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.' . $this->id;
    }

    public function receivedDirectMessages()
    {
        return $this->hasMany(DirectMessage::class, 'receiver_id');
    }

    public function sentDirectMessages()
    {
        return $this->hasMany(DirectMessage::class, 'sender_id');
    }

    public function unreadMessagesForAuthUser()
    {
        return $this->hasMany(DirectMessage::class, 'sender_id')->where('receiver_id', auth()->user()->id)->whereNull('read_at');
    }

    public function unreadDirectMessages()
    {
        return $this->receivedDirectMessages()->whereNull('read_at');
    }

    public function getUnreadDirectMessagesAttribute()
    {
        return $this->unreadDirectMessages()->exists();
    }

    public function file()
    {
        return $this->hasMany(File::class, 'owner_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class);
    }

    public function userProjectDuration()
    {
        return $this->hasMany(ProjectUsersDuration::class, 'user_id');
    }

    public function userDuration()
    {
        return $this->hasMany(ProjectUsersDuration::class, 'project_id', 'user_id');
    }

    public function deals()
    {
        return $this->hasMany('App\SalesPipeline\Models\Deals', 'owner_id')->with('getDealOrganisation');
    }   

    public function getAvailableFrontendPermissionsAttribute(){
        $permissions = Permission::where('resource', 'like', '%frontend%')->where('users.id', '=', auth()->user()->id)->join('role_has_permission', 'permissions.id', '=', 'role_has_permission.permission_id')->join('roles', 'roles.id', '=', 'role_has_permission.role_id')->join('users', 'users.role_id', '=', 'role_has_permission.role_id')->get(['action','resource','users.role_id']);
        return $permissions;
    }

    public function isUserMemberOfProject($project_id,$user_id = null)
    {
        return ProjectUser::where('user_id',isset($user_id) ? $user_id : auth()->user()->id )->where('project_id',$project_id)->exists();
    }    
}
