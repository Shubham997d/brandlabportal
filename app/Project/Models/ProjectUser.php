<?php

namespace App\Project\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{	
	public $timestamps = false;
	protected $table = 'project_user';
    // protected $fillable = ['task_enabled', 'discussion_enabled', 'message_enabled', 'event_enabled', 'file_enabled', 'roadmap_enabled'];
}
