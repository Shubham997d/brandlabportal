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

class DealActivity extends Model
{
    protected $type = 'deal_activities';

    // protected $casts = [
    //     'phone' => 'array', 'email' => 'array', 'files' => 'array'
    // ];

    protected $fillable = ['activity_data', 'timeline_time_in', 'timeline_time_out','activity_subject','activity_type','user_id','creator_id','deal_id','schedule_status','notes','status','status_datetime'];

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);            
        $dateFormat = config('constants.salespipeline.miscellaneous.date.format.model');
        $datetimeFormat = config('constants.salespipeline.miscellaneous.date.format.datetime.model');
        $dateFormatActivity = config('constants.salespipeline.miscellaneous.date.format.activity');
        $this->casts['created_at'] = $dateFormat;
        $this->casts['timeline_time_in'] = $dateFormatActivity;
        $this->casts['timeline_time_out'] = $dateFormatActivity;
    }

    public function getActivityStatusAttribute()
    {        
        if (isset($this->status)) {
            return config('constants.salespipeline.miscellaneous.activity_status.label')[$this->status];
        }
    }

    public function getCreaterNameAttribute()
    {        
        if (isset($this->creator_id)) {
                $user = User::where('id',$this->creator_id )->first();
                return isset($user) ? $user->username : 'N/A';
        }
    }

    public function getActivityBackgroundColorAttribute()
    {   
        if (isset($this->activity_type)) {                      
            $activity_type_index = array_search($this->activity_type,config('constants.salespipeline.miscellaneous.actvity_types.label'));
            return isset($activity_type_index) ? config('constants.salespipeline.miscellaneous.actvity_types.colour')[$activity_type_index] : null;
        }
    }    

}
