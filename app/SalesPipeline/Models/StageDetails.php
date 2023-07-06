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

class StageDetails extends Model
{
    protected $type = 'stage_details';

    // protected $casts = [
    //     'phone' => 'array', 'email' => 'array',
    // ];

    protected $fillable = ['deal_id', 'stage_id', 'start_datetime','end_datetime'];
}
