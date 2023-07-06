<?php

namespace App\Project\Models;

use Illuminate\Database\Eloquent\Model;
use App\Base\Models\Role;
use App\Base\Models\User;
use App\Base\Models\Cycle;
use App\Base\Models\Group;
use App\Base\Models\Permission;
use App\Base\Contracts\HasMembers;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectCategories extends Model
{
    protected $fillable = ['name', 'slug'];
    
}
