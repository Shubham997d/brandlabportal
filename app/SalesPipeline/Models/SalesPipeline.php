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

class SalesPipeline extends Model
{
    protected $type = 'sales_pipelines';

  //  protected $fillable = ['company_info', 'timeline', 'budget', 'project_info', 'selling_type', 'contract_stage','onboarding_call_details','turn_over','user_id'];
}
