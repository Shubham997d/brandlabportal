<?php

namespace App\Project\Models;

use Illuminate\Database\Eloquent\Model;
use App\Base\Models\User;
use App\Base\Models\Project;

class ProjectUsersDuration extends Model
{  

    protected $fillable = ['project_id', 'user_id', 'duration'];

    public $timestamps = false;

    protected $casts = ['duration' => 'integer'];



    /**
     * @return BelongsToMany
     */


    // public function member_duration()
    // {
    //     return $this->hasOne(ProjectSetting::class, 'project_id');
    // }

}
