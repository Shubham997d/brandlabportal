<?php

namespace App\Project\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCommissionUser extends Model
{	
	// public $timestamps = false;
    protected $fillable = ['project_id','user_id'];
}
