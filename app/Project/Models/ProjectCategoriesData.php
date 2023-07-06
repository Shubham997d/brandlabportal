<?php

namespace App\Project\Models;

use Illuminate\Database\Eloquent\Model;
use App\Project\Models\ProjectCategories;
use App\Base\Models\Role;
use App\Base\Models\User;
use App\Base\Models\Cycle;
use App\Base\Models\Group;
use App\Base\Models\Permission;
use App\Base\Contracts\HasMembers;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectCategoriesData extends Model
{

    protected $table = 'project_categories_data';

    protected $fillable = ['project_id', 'category_id'];

    public function categoryDetails(){
        return $this->hasMany('App\Project\Models\ProjectCategories', 'id','category_id');
    }
    
}
