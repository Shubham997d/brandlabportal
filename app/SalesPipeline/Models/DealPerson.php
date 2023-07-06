<?php

namespace App\SalesPipeline\Models;

use Illuminate\Database\Eloquent\Model;
use App\Base\Models\Role;
use App\Base\Models\User;
use App\Base\Models\Cycle;
use App\Base\Models\Group;
use App\Base\Models\Permission;
use App\Base\Contracts\HasMembers;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DealPerson extends Model
{
    protected $type = 'deal_people';

    protected $casts = [
        'phone' => 'array', 'email' => 'array', 'files' => 'array' , 'extra_names' => 'array',
    ];

    protected $fillable = ['first_name', 'last_name','extra_names', 'email', 'phone', 'deal_id','files','source_of_lead'];

}
