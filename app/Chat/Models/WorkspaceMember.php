<?php

namespace App\Chat\Models;

use Illuminate\Database\Eloquent\Model;

use App\Base\Models\User;

class WorkspaceMember extends Model
{
    protected $fillable = ['user_id', 'workspace_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
