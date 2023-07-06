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

class DealInvoice extends Model
{
    protected $type = 'deal_invoices';

    protected $casts = [
        'invoice_content' => 'array', 'content' => 'array'
    ];

    protected $fillable = ['deal_id','content','invoice_type','invoice_content','file_name','invoice_date','invoice_number'];

}
