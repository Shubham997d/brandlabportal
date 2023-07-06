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

class DealLostReason extends Model
{
    protected $type = 'deal_lost_reasons';

    // protected $casts = [
    //     'phone' => 'array', 'email' => 'array', 'files' => 'array'
    // ];

    protected $fillable = ['deal_id','reason_id','notes'];

}
