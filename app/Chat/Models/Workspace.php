<?php

namespace App\Chat\Models;

use Illuminate\Database\Eloquent\Model;

use App\Chat\Models\WorkspaceMember;

use App\Base\Models\User;

class Workspace extends Model
{
    protected $fillable = ['name', 'slug', 'meta', 'created_at', 'updated_at'];

    public function members()
    {
        return $this->hasMany(WorkspaceMember::class,'workspace_id', 'id')->with('user:id,username,avatar');
    }
    
}
