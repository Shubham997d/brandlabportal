<?php

namespace App\SalesPipeline\Models;

use Illuminate\Database\Eloquent\Model;
use App\SalesPipeline\Models\DealCategories;
use App\Base\Models\Role;
use App\Base\Models\User;
use App\Base\Models\Cycle;
use App\Base\Models\Group;
use App\Base\Models\Permission;
use App\Base\Contracts\HasMembers;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DealCategoriesData extends Model
{

    protected $table = 'deal_categories_data';

    protected $fillable = ['deal_id', 'category_id'];

    // protected $append = ['name'];

    // public function getNameAttribute(){      
    //     return isset($this->category_id) == true ? DealCategories::where('category_id',$this->category_id)->first()->name : null;
    // }

    public function categoryDetails(){
        return $this->hasMany('App\SalesPipeline\Models\DealCategories', 'id','category_id');
    }
    
}
