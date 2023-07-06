<?php

namespace App\SalesPipeline\Models;

use Illuminate\Database\Eloquent\Model;
use App\Base\Models\Role;
use App\Base\Models\User;
use App\SalesPipeline\Models\DealOrganisation;
use App\SalesPipeline\Models\DealInvoiceDefault;
use App\SalesPipeline\Models\StageDetails;
use App\SalesPipeline\Models\DealCategoriesData;
use App\SalesPipeline\Models\DealCategories;
use App\Base\Models\Cycle;
use App\Base\Models\Group;
use App\Base\Models\Permission;
use App\Base\Contracts\HasMembers;
use App\SalesPipeline\Models\DealLostReason;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use AmrShawky\LaravelCurrency\Facade\Currency;
use NumberFormatter;

class Deals extends Model
{
    protected $type = 'deals';
    protected $casts = [];    


    public function __construct(array $attributes = array()) {
            parent::__construct($attributes);            
            $dateFormat = config('constants.salespipeline.miscellaneous.date.format.model');
            $datetimeFormat = config('constants.salespipeline.miscellaneous.date.format.datetime.model');
            $this->casts['created_at'] = $dateFormat;
            $this->casts['deal_won_datetime'] = $datetimeFormat;
            $this->casts['deal_lost_datetime'] = $datetimeFormat;
    }

    protected $fillable = ['pipeline_stage', 'status', 'owner_id','creator_id','deal_won_datetime','deal_lost_datetime'];  
  

    public function getStageData(){ 
        return $this->hasMany('App\SalesPipeline\Models\Deals','pipeline_stage', 'pipeline_stage')->with(['getStageDetail', 'getDealPerson', 'getDealOrganisation', 'getDealActivity','getDealInvoiceDefaultBank','getDealInvoiceDefaultAddress','getDealInvoice','getDealCategory']);
    }

    public function stageData(){ 
        return $this->hasMany('App\SalesPipeline\Models\Deals','pipeline_stage', 'pipeline_stage')->with(['getDealOrganisation:deal_id,organisation,title,deal_color,turnover,currency_code,currency_symbol','getDealCategory']);
    }

    public function deal_stages(){
        return $this->hasMany('App\SalesPipeline\Models\Deals','pipeline_stage', 'pipeline_stage');
    }

    public function deals(){
        return $this->hasMany('App\SalesPipeline\Models\Deals','owner_id');
    }

    public function getDealNotes(){
        return $this->hasMany('App\SalesPipeline\Models\DealNotes','deal_id', 'id');
    }

    public function getStageDetail(){
        return $this->hasMany('App\SalesPipeline\Models\StageDetails','deal_id', 'id');
    }

    public function getDealPerson(){
        return $this->hasOne('App\SalesPipeline\Models\DealPerson','deal_id', 'id');
    }

    public function getDealActivity(){
        return $this->hasMany('App\SalesPipeline\Models\DealActivity','deal_id', 'id');
    }

    public function getDealOrganisation(){
        return $this->hasOne('App\SalesPipeline\Models\DealOrganisation','deal_id', 'id');
    }

    public function getDealInvoiceDefaultBank(){
        return $this->hasOne('App\SalesPipeline\Models\DealInvoiceDefault','deal_id', 'id')->where('content_type',config('constants.salespipeline.deal_invoice_default')['bank_form']);
    }
    public function getDealInvoiceDefaultAddress(){
        return $this->hasOne('App\SalesPipeline\Models\DealInvoiceDefault','deal_id', 'id')->where('content_type',config('constants.salespipeline.deal_invoice_default')['address_form']);
    }
    public function getDealInvoice(){
        return $this->hasMany('App\SalesPipeline\Models\DealInvoice','deal_id', 'id');
    }
    
    public function getDealCategory(){
        return $this->hasMany('App\SalesPipeline\Models\DealCategoriesData','deal_id', 'id')->with(['categoryDetails:id,name,slug']);
    }    

    // public function dealOrganisations(){        
    //     return $this->hasMany('App\SalesPipeline\Models\DealOrganisation','deal_id', 'id');
    // }

    public function getAdminDealsData(){
        return $this->hasMany('App\SalesPipeline\Models\Deals','id')->with(['getStageDetail', 'getDealPerson', 'getDealOrganisation','getDealActivity','getDealInvoiceDefault']);
    }
    public function dealOwner(){        
        return $this->hasOne(User::class,'id', 'owner_id')->with('deals');
    }

    public function dealDetails(){        
        return $this->hasOne(User::class,'id', 'owner_id')->with('deals');
    }

    public function getDealLinkAttribute()
    {
        $deal_link['id'] = (isset($this->id)) ? $this->id : null;
        $deal_link['title'] = (isset($this->title)) ? $this->title : null;
        return $deal_link;
    }
    
    public function getDealCurrentStageAttribute()
    {
        if (isset($this->pipeline_stage)) {
            return config('constants.salespipeline.deal_stages.stage')[$this->pipeline_stage];
        }
    }

    public function getDealStageAttribute()
    {
        if (isset($this->stage_id)) {
            return config('constants.salespipeline.deal_stages.stage')[$this->pipeline_stage];
        }
    }
    public function getDealPriceWithCurrencyAttribute()
    {
        $data = (isset($this->id)) ? DealOrganisation::where('deal_id',$this->id)->first() : null;
        $result = array();
        if (isset($data)) {
            $result['turnover'] = $data->turnover;
            $result['currency_code'] = $data->currency_code;
        }
        return $result;
    }

    public function getDealPriceWithCurrencySymbolAttribute()
    {
        $data = (isset($this->id) && !isset($this->currency_code)) ? DealOrganisation::where('deal_id',$this->id)->first() : null;
        $currrencies = config('constants.miscellaneous.currrencies');
        if (isset($data->currency_code)) {
            $key = array_search($data->currency_code, array_column($currrencies, 'code'));
            $symbol = $currrencies[$key]['symbol'];
            $value = $data->turnover;
        }elseif (isset($this->currency_code)) {
            $key = array_search($this->currency_code, array_column($currrencies, 'code'));
            $symbol = $currrencies[$key]['symbol'];
            $value = $this->turnover;
        }else{
            return null;
        }        
        return (isset($symbol)) ? $symbol.' '.$value : (isset($this->turnover) ? $this->turnover : (isset($data->turnover) ? $data->turnover : null ));
        
    }

    public function getDealStatusAttribute()
    {
        return (isset($this->status)) ? array_search($this->status, config('constants.salespipeline.deal.status')) : null;
    }
    public function getCreaterAttribute(){

        $creator = (isset($this->creator_id)) ? User::where('id',$this->creator_id)->get(['username'])->first() : null;        
        return isset($creator) ? $creator->username : null;
    }

    public function getDealLostReasonAttribute(){ 
         $reason = (isset($this->reason_id)) ?  config('constants.salespipeline.miscellaneous.deal_lost_reasons.label')[$this->reason_id] : null;
         return $reason;
    }
    public function getDealLostReasonNoteAttribute(){ 
        $reason = (isset($this->reason_id)) ? DealLostReason::where('deal_id',$this->id)->first()->notes : null;
        return $reason;
    }    

    public function getConvertedValueAttribute(){
        
        if (isset($this->id)) {
            
            $data = DealOrganisation::where('deal_id',$this->id)->first();
            $convertedValue = (isset($data)) ? getConvertedValue($data) : null;
            return $convertedValue;            
        }
    }
  

    public function getTotalAmountAttribute(){ 
        $convertedValue = 0;
        if (isset($this->owner_id)) {
            foreach ($this->dealOwner->deals as $deal) {
                $convertedValue = convertedValue($deal->getDealOrganisation) + $convertedValue;
            }  
        }else{
            $convertedValue = null;
        }        
        return $convertedValue;
    }
    
    public function getTotalDealsAttribute(){
        return (isset($this->dealOwner->deals)) ? $this->dealOwner->deals->count() : null;         
    }
    public function getInvoiceIdAttribute(){
        $invoice = 'Inv-';
        return (isset($this->id)) ? $invoice.$this->id : null;         
    }


    public function getBackgroundColorAttribute(){        
        if (isset($this->owner_id)) {
            $user = User::where('id',$this->owner_id)->first();
            $colour = isset($user) ?  $user->colour : null;
            return $colour;            
        }
    }
    public function getreasonBackgroundColorAttribute(){        
        if (isset($this->reason_id)) {
             return config('constants.salespipeline.miscellaneous.deal_lost_reasons.colour')[$this->reason_id];
        }
    }
    public function getreasonNameAttribute(){       // Also With Currency Symbol 
        if (isset($this->reason_id)) {
             return config('constants.salespipeline.miscellaneous.deal_lost_reasons.label')[$this->reason_id];
        }
    }

    public function getdealStageBackgroundColorAttribute(){ 
                    
        if (isset($this->stage_id)) {
             return config('constants.salespipeline.deal_stages.colour')[$this->stage_id];
        }
    }

    public function getexpectedClosedDateFormattedAttribute(){       
        if (isset($this->expected_close_date)) {
             return date(config('constants.salespipeline.miscellaneous.date.format.frontend'),strtotime($this->expected_close_date));
        }
    }

    public function getOwnerAttribute(){

        $owner = (isset($this->owner_id)) ? User::where('id',$this->owner_id)->first(['username']) : null;
        if($owner == null){
            return null;
        }
        return $owner->username;
    }

    public function getDealPeoplePhoneAttribute(){
       
        if (isset($this->phone)) {  // Deal People Model Variable
            return json_decode(json_decode($this->phone,true),true);
        }
        return null;
    }

    public function getDealPeopleEmailAttribute(){

        if (isset($this->email)) {  // Deal People Model Variable
            return json_decode(json_decode($this->email,true),true);
        }
        return null;
    }

    public function getDealOrganisationPhoneAttribute(){
        if (isset($this->id)) {
            $data = DealOrganisation::where('deal_id',$this->id)->first(['phone']);
            return isset($data->phone) ? $data->phone : null;
        }
        return null;
    }

    public function getDealColorLabelAttribute(){
        if (isset($this->deal_color)) {         
            $label = getKeyFromArrayAndThenValueFromAnotherArray($this->deal_color,config('constants.salespipeline.miscellaneous.dealColors.colorCodes'),config('constants.salespipeline.miscellaneous.dealColors.colorLabels'));
            return $label;
        }
        return null;
    }

    public function getDealCurrentColorLabelAttribute(){

        if (isset($this->id)) {
            $data = DealOrganisation::where('deal_id',$this->id)->first(['deal_color']);
            if (isset($data->deal_color)) {
                $label = getKeyFromArrayAndThenValueFromAnotherArray($data->deal_color,config('constants.salespipeline.miscellaneous.dealColors.colorCodes'),config('constants.salespipeline.miscellaneous.dealColors.colorLabels'));
                return $label;     
            }else{
                return null;
            }
        }
        return null;
    }

    public function getDealNameAttribute(){
        if (isset($this->id)) {
            $data = DealOrganisation::where('deal_id',$this->id)->first(['title']);
            return isset($data->title) ? $data->title : null;
        }
        return null;
    }

    public function getDealCategoryNameAttribute(){
        if (isset($this->id)) {
            $data = DealCategoriesData::where('deal_id',$this->id)->first(['category_id']);
            if (isset($data->category_id)) {
                $deal_category =  DealCategories::where('id',$data->category_id)->first(['name']);
                return $deal_category->name;
            }else{
                return null;        
            }
        }
        return null;
    }
    
}


// unused code

// protected $appends = ['deal_link','deal_status','creater'];    

// protected $casts = [
//     'created_at' => $this->castDateForamt,
//     'deal_won_datetime' => $this->castDatetimeForamt,
//     'deal_lost_datetime' => $this->castDatetimeForamt
// ];