<?php

namespace App\SalesPipeline\Repositories;
use App\SalesPipeline\Models\SalesPipeline;
use App\SalesPipeline\Models\Deals; 
use App\SalesPipeline\Models\StageDetails;
use App\SalesPipeline\Models\DealOrganisation;
use App\SalesPipeline\Models\DealPerson;
use App\SalesPipeline\Models\DealInvoice;
use App\SalesPipeline\Models\DealLostReason;
use App\SalesPipeline\Models\DealActivity;
use App\SalesPipeline\Models\DealNotes;
use App\SalesPipeline\Models\DealInvoiceDefault;
use App\SalesPipeline\Models\DealEmailStatus;
use App\Project\Models\Project;
	
class StageDetailRepository
{
    protected $model;

    public function __construct(StageDetails $StageDetails)
    {
        $this->model = $StageDetails;
    }

    public function store($data, $deal_id, $deal_stages)
    {
        foreach($deal_stages as $deal_key=> $deal_val){
            StageDetails::create([
                'deal_id'  => $deal_id,
                'stage_id'  => $deal_key ?? 1,
                // 'end_datetime' => $data['data']['stage'] > $deal_key ? date(config('constants.salespipeline.miscellaneous.datetime.format.database')) : null,
                // 'start_datetime' => $data['data']['stage'] >= $deal_key ? date(config('constants.salespipeline.miscellaneous.datetime.format.database')) : null,
                 // 'end_datetime' => $data['data']['stage'] > $deal_key ? date(config('constants.salespipeline.miscellaneous.datetime.format.database')) : null,
                 'start_datetime' => $data['data']['stage'] == $deal_key ? date(config('constants.salespipeline.miscellaneous.datetime.format.database')) : null,
            ]);
        }
    }

    public function delete($deal_id)
    {   
        $this->deleteAllResourcesOfDeal($deal_id);        
    }

    public function deleteMultipleDeals(){
        $deals = Deals::where('owner_id',70)->where('pipeline_stage',1)->get(['id']);
        foreach ($deals as $deal) {
            $this->deleteAllResourcesOfDeal($deal->id);
        }
    }

    public function deleteAllResourcesOfDeal($deal_id){

        StageDetails::where('deal_id', $deal_id)->delete();

        DealOrganisation::where('deal_id', $deal_id)->delete();
        
        DealPerson::where('deal_id', $deal_id)->delete();

        DealInvoice::where('deal_id', $deal_id)->delete();

        DealLostReason::where('deal_id', $deal_id)->delete();

        DealActivity::where('deal_id', $deal_id)->delete();

        DealNotes::where('deal_id', $deal_id)->delete();

        DealInvoiceDefault::where('deal_id', $deal_id)->delete();

        DealEmailStatus::where('deal_id', $deal_id)->delete();

        $projectHasDeal = Project::where('deal_id', $deal_id)->first();

        if (isset($projectHasDeal)) { // empty deal
            $projectHasDeal->deal_id = null;
            $projectHasDeal->save();
        }
        return true;
    }


}
