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

class DealInvoiceDefault extends Model
{
    protected $type = 'deal_invoice_defaults';

    protected $casts = [
        'content' => 'array'
    ];

    protected $fillable = ['deal_id','content','content_type'];

}
