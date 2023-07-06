<?php

namespace App\SalesPipeline\Models;

use Illuminate\Database\Eloquent\Model;
use App\Base\Models\Role;
use App\Base\Models\User;
use App\Base\Models\Cycle;
use App\Base\Models\Group;
use App\Base\Models\Permission;
use App\Base\Contracts\HasMembers;
use App\SalesPipeline\Models\Deals;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DealOrganisation extends Model
{
    protected $type = 'deal_organisations';
    protected $appends = ['deal_current_colour'];    
    protected $fillable = ['title', 'organisation','turnover','currency_code','phone','email','expected_close_date', 'address', 'website', 'deal_id','deal_color','deal_color_update_datetime'];    


    public function __construct(array $attributes = array()) {        
        parent::__construct($attributes);            
        $dateFormat = config('constants.salespipeline.miscellaneous.date.format.model');        
        $this->casts['expected_close_date'] = $dateFormat;
        // $this->attributes['turnover'] = config('constants.salespipeline.miscellaneous.currency.default.price');
        // $this->attributes['title'] = 'N/A';
        // $this->attributes['deal_color'] = config('constants.salespipeline.miscellaneous.dealColors.colors.default');
       
    }

    public function getDeal(){
        return $this->hasOne('App\SalesPipeline\Models\Deals', 'id', 'deal_id');
    }

    public function getDealCurrentColourAttribute(){       
        // Check if deal stage has been updated at any moment if not then change its color
        // updated the deal color according to client requirement
        // $dealStagnated = StageDetails::where('deal_id',$this->deal_id)->where('updated_at','<',Carbon::now()->subDays(config('constants.salespipeline.miscellaneous.dealColors.changeDealColorNoOfDays.dealStagnated')))->get(['deal_id']);
        // $color = config('constants.salespipeline.miscellaneous.dealColors.colors.default');

        // $color = ($color != config('constants.salespipeline.miscellaneous.dealColors.colors.default'))  ?  $color : ((isset($this->deal_color))  ? $this->deal_color : $color);
        // if(isset($this->deal_color_update_datetime) && $this->deal_color == config('constants.salespipeline.miscellaneous.dealColors.colors.greenColor')) {            
        //     $deal_color_update_datetime = (int) Carbon::parse($this->deal_color_update_datetime)->addDays(config('constants.salespipeline.miscellaneous.dealColors.changeDealColorNoOfDays.dealReturnToColor'))->timestamp;
        //     $current_date_time = (int) Carbon::now()->timestamp;
        //     $color = ($current_date_time > $deal_color_update_datetime) ? config('constants.salespipeline.miscellaneous.dealColors.colors.returnToColor') : $color;            
        // }
        // $dealStagnatedCount = $dealStagnated->groupBy('deal_id');
        // $color = (isset($dealStagnatedCount[$this->deal_id]) && count($dealStagnatedCount[$this->deal_id]) === count(config('constants.salespipeline.deal_stages.stage'))) ? config('constants.salespipeline.miscellaneous.dealColors.colors.dealStagnated') : $color;                 
        return (isset($this->deal_color) == true) ? $this->deal_color : config('constants.salespipeline.miscellaneous.dealColors.colors.default');
    }

    public function dealColorLabel($deal_color = null){
        $deal_color = isset($deal_color) ? $deal_color : $this->deal_color;
        if (isset($deal_color)) {         
            $label = getKeyFromArrayAndThenValueFromAnotherArray($deal_color,config('constants.salespipeline.miscellaneous.dealColors.colorCodes'),config('constants.salespipeline.miscellaneous.dealColors.colorLabels'));
            return $label;
        }
        return null;
    }

    public function owner(){
        $owner = Deals::where('id',$this->deal_id)->first(['owner_id']);
        return $owner;
    }
}
