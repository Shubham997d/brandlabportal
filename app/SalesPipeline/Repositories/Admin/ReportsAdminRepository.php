<?php

namespace App\SalesPipeline\Repositories\Admin;

use App\SalesPipeline\Models\SalesPipeline;
use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\StageDetails;
use App\SalesPipeline\Models\DealOrganisation;
use App\SalesPipeline\Models\DealPerson;
use App\SalesPipeline\Models\DealActivity;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use App\Base\Models\User;
use App\Exports\DealsExport;
use Maatwebsite\Excel\Facades\Excel;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\SalesPipeline\Models\DealCategories;

class ReportsAdminRepository
{
    protected $model,$maxCount,$reportParams,$itemsData;   
    

    public function __construct(Deals $deals)
    {
        $this->model = $deals; // Set For Deals Reports        
        ini_set('max_execution_time', 6000); // Increased because of Currency API
    }


    public function getListingData($reportType,$params){
        (!isset($this->reportParams)) ? $this->setDefaultParmsAccordingToReportType($reportType,config('constants.salespipeline.miscellaneous.tabs.adminReportsTabs')[0],'frontend',$params) :  false;
        $query = $this->getFiltersQuery();               
        
        $reportData = $this->getListingTabData($query);

        $this->itemsData = (isset($this->reportParams['chartHasSameAsGetFields']) && $this->reportParams['chartHasSameAsGetFields'] === true ) ? $reportData : null;        
        $data['defaulFilterDateValues'] = $this->getFilterDefaultValues(false);
        $data['defaultFilterParams'] = $this->reportParams['params'];
        $data['filter']['users'] = $this->getUsersData(true); // dynamic filters data
        $data['filter']['categories'] = DealCategories::all(['id as value','name as text']);
        $data['fields'] = $this->getDynamicFieldsAccordingToReportType();
        $data['items'] = $reportData;
        $data['chartData'] = $this->getChartDataAccordingToReportType($reportData,$query);

        return $data;
        
    }

    public function getChartDataAccordingToReportType($reportData,$query){
        
        $this->setDefaultParmsAccordingToReportType($this->reportParams['reportType'],$this->reportParams['tab'],'chart',$this->reportParams['params']);
        $query = $this->extraJoins($query);
        if (count($reportData) === 0 || (isset($reportData['items']) && count($reportData['items']) == 0 )) {
            $chartData = array();      
        }else{
            $chartData = $this->getChartAccordingToReportType($query);
        }
        return $chartData;
    }

    

    public function getChartAccordingToReportType($query){
        $chartData = $this->getParticularGraphData($query);
        $data[config('constants.charts')['types'][$this->reportParams['chartType']]['field']] = $chartData;
        $data[config('constants.charts')['types'][$this->reportParams['chartType']]['field']]['maxCount'] = $this->getMaxCount();
        // dd($chartData);
        return $data;
     }

    public function mainQuery(){

        if (config('constants.salespipeline.miscellaneous.reportBelongsTo')[1] === $this->reportParams['reportBelongsTo']) { // deals
            $query = $this->model->join('deal_organisations', 'deals.id', '=', 'deal_organisations.deal_id')
                                 ->join('users', 'users.id', '=', 'deals.owner_id')
                                 ->leftjoin('deal_categories_data', 'deal_categories_data.deal_id', '=', 'deals.id')
                                 ->leftjoin('deal_categories', 'deal_categories.id', '=', 'deal_categories_data.category_id')
                                 ->leftjoin('deal_lost_reasons', 'deal_lost_reasons.deal_id', '=', 'deals.id');       
        }
        if (config('constants.salespipeline.miscellaneous.reportBelongsTo')[2] === $this->reportParams['reportBelongsTo']) { // activity
            $query = new DealActivity;
            $query = $query->join('users', 'users.id', '=', 'deal_activities.user_id');
        }
        $query = $this->extraJoins($query);
        
        return $query;     
    }

    public function getListingTabData($query){        
        if($this->reportParams['groupByUsingCollectionMethod'] === false && isset($this->reportParams['groupBy']) === true ){ // For deals progress report
            $reportData = $query->groupBy($this->reportParams['groupBy'])->get($this->reportParams['get'])->each->setAppends($this->reportParams['append']);
        }else{
            $reportData = $query->get($this->reportParams['get'])->each->setAppends($this->reportParams['append']);
        }
        return $reportData;
    }

    public function getSummary($reportType,$params){
        (!isset($this->reportParams)) ? $this->setDefaultParmsAccordingToReportType($reportType,config('constants.salespipeline.miscellaneous.tabs.adminReportsTabs')[1],'frontend',$params,true) :  false;        
        $query = $this->getFiltersQuery();
        $reportData['items'] = $this->getSummaryData($query);        
        $this->itemsData = (isset($this->reportParams['chartHasSameAsGetFields']) && $this->reportParams['chartHasSameAsGetFields'] === true ) ? $reportData['items'] : null;
        $data['defaultFilterParams'] = $this->reportParams['params'];
        $reportData['fields'] = $this->getDynamicFieldsAccordingToReportType();
        $reportData['chartData'] = $this->getChartDataAccordingToReportType($reportData,$query);
        $reportData['isBusy'] = false;
        return $reportData;
    }

    public function getSummaryData($query){        

        // $query->groupBy('username')->get($this->reportParams['get'])->load('dealOwner')->each->setAppends($this->reportParams['append'];
        
        $reportData = $query->get($this->reportParams['get'])->each->setAppends($this->reportParams['append']);
        if (count($reportData) > 0) {
            $data = $this->getSummaryDataByUsers($reportData);
        }else{
            $data = [];
        }
        return $data;
    }

    public function getFormattedData($reportData){
        
        // $reportData = $this->addKeysInCollectionAccordingToReportType($reportData);
        if($this->reportParams['groupByIsDynamic'] === false && $this->reportParams['groupByUsingCollectionMethod'] === true){
            $reportData = $reportData->groupBy($this->reportParams['groupBy']);
        }else{  
        if ($this->reportParams['groupByUsingCollectionMethod'] === true) {                

                if(isset($this->reportParams['groupByExtraFields']['outerField'])){                    
                    $reportData = $reportData->groupBy(function($val) {                         
                                $field = $this->reportParams['groupByExtraFields']['outerField'];
                                return  isset($this->reportParams['groupByExtraFields']['format']) ? Carbon::parse($val->$field)->format($this->reportParams['groupByExtraFields']['format']) : $val->$field;
                            });
                }
                if(isset($this->reportParams['groupByExtraFields']['innerField'])){
                    $reportData = $reportData->groupBy($this->reportParams['groupByExtraFields']['innerField']);
                }   
            }
        }
        return $this->mapCollectionAccordingToReportType($reportData);
    }

    
    public function mapCollectionAccordingToReportType($reportData){
        $data = array();
        $lostReasons =   config('constants.salespipeline.miscellaneous.deal_lost_reasons.label');       

        if ($this->reportParams['groupByIsDynamic'] === false) { // Allow total_amount && average_amount to be calculated only on sort type price of deals and summary tab
            $result = $reportData->map(function ($group) use ($data, $lostReasons) {               
                $data['total_amount'] = (in_array('total_amount',$this->reportParams['requiredFields']) && ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[1] || $this->reportParams['tab'] == config('constants.salespipeline.miscellaneous.tabs.adminReportsTabs')[1])) ?  $group->sum('converted_value') : null;
                $data['username'] = (in_array('username',$this->reportParams['requiredFields'])) ?  $group->first()['username'] : null;            
                $data['total_deals'] = (in_array('total_deals',$this->reportParams['requiredFields'])) ?  $group->count() : null;
                $data['average_amount'] = (in_array('average_amount',$this->reportParams['requiredFields']) && ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[1] || $this->reportParams['tab'] == config('constants.salespipeline.miscellaneous.tabs.adminReportsTabs')[1])) ? round($data['total_amount']/$data['total_deals'],2) : null;
                $data['backgroundColor'] = (in_array('backgroundColor',$this->reportParams['requiredFields'])) ? $group->first()['background_color'] : null;
                $data['reasonName'] = (in_array('reasonName',$this->reportParams['requiredFields'])) ? $group->first()['reason_name'] : null;
                $data['reasonBackgroundColor'] = (in_array('reasonBackgroundColor',$this->reportParams['requiredFields'])) ? $group->first()['reason_background_color'] : null;                
                if(in_array('reasonCount',$this->reportParams['requiredFields'])){
                    $reason = (isset($group->first()['reason_id'])) ? formatString($lostReasons[$group->first()['reason_id']],false,true) : 'not-lost';
                    $data[$reason] = (isset($group->first()['reason_id'])) ?  $group->count() : null;
                }
                return $data;
            });             
        }
        
        if ($this->reportParams['groupByIsDynamic'] === true) {          
            $reportData = isset($this->reportParams['groupByExtraFields']['format']) ? $this->mergeCollectionDataBasedOnDateAndYear($reportData) : $reportData;
            $dealStages =   in_array('dealStages',$this->reportParams['requiredFields']) ? config('constants.salespipeline.deal_stages.stage') : null;
            $users = in_array('users',$this->reportParams['requiredFields']) ? $this->getUsersData(false,true) : null; 
            $activityTypes = in_array('actvity_types',$this->reportParams['requiredFields']) ? config('constants.salespipeline.miscellaneous.actvity_types.label') : null; 
            
            $result = $reportData->map(function ($group,$key) use ($data, $dealStages,$users,$activityTypes) { 
                if (isset($dealStages)) {  // For Deal Progress Report
                    foreach ($dealStages as $stageId => $stage) {  
                        if(isset($group)) { 
                            $data[formatString($stage,false,true)] = (in_array('total_deals',$this->reportParams['requiredFields'])) ?  $group->where('stage_id', $stageId)->wherenotnull('start_datetime')->count() : 0; 
                            if (isset($this->reportParams['params']['field']) && $this->reportParams['params']['field'] == 'pipeline_stage') {   // for pipeline_stage_filter
                                $data[formatString($stage,false,true)] = (in_array('total_deals',$this->reportParams['requiredFields']) == true && $this->reportParams['params']['value'] == $stageId) ?  $group->where('stage_id', $this->reportParams['params']['value'])->wherenotnull('start_datetime')->count() : 0;
                            }
                            $data['deal_stage_entered_month_year'] = in_array('deal_stage_entered_month_year',$this->reportParams['requiredFields']) ? $key : null;
                            $data['username'] = in_array('username',$this->reportParams['requiredFields']) ? $key : null;
                        }else{      // Meaning value do not exist add default values 
                            $data[formatString($stage,false,true)] = (in_array('total_deals',$this->reportParams['requiredFields'])) ? 0 : null;                            
                            $data['deal_stage_entered_month_year'] = in_array('deal_stage_entered_month_year',$this->reportParams['requiredFields']) ? $key : null;
                            $data['username'] = in_array('username',$this->reportParams['requiredFields']) ? $key : null;
                        }
                    } 
                                       
                } 
                if (isset($users)) { // For Deal Won Over Time Report
                    foreach ($users as $username) {                         
                        if(isset($group)) {                             
                            if(in_array('total_amount',$this->reportParams['requiredFields'])){            
                                if($this->reportParams['params']['chartSortType'] === config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0] && $this->reportParams['requestType'] === 'chart')  {
                                    $data[formatString($username,false,true)] = $group->where('username', $username)->wherenotnull($this->reportParams['groupByExtraFields']['outerField'])->count() ??  0;
                                }
                                if($this->reportParams['params']['chartSortType'] === config('constants.salespipeline.miscellaneous.graphs.chartSortType')[1] || ($this->reportParams['isSummary'] === true && $this->reportParams['requestType'] === 'frontend')) {
                                    $data[formatString($username,false,true)] = $group->where('username', $username)->wherenotnull($this->reportParams['groupByExtraFields']['outerField'])->sum('converted_value') ??  0;
                                }
                            }
                            $data['total_count_of_deals'] = (in_array('total_count_of_deals',$this->reportParams['requiredFields'])) ?  $group->wherenotnull($this->reportParams['groupByExtraFields']['outerField'])->count() : null;  // Count only those deals where wontime is not null
                            $data['deal_won_month_year'] = (in_array('deal_won_month_year',$this->reportParams['requiredFields'])) ? $key : null; 
                        }else{  // Meaning value do not exist add default values 
                            $data[formatString($username,false,true)] = (in_array('total_amount',$this->reportParams['requiredFields'])) ? 0 : null;
                            $data['deal_won_month_year'] = (in_array('deal_won_month_year',$this->reportParams['requiredFields'])) ? $key : null;
                            $data['total_count_of_deals'] = (in_array('total_count_of_deals',$this->reportParams['requiredFields'])) ? 0 : null;
                        }                        
                    }   
                      
                }

                if (isset($activityTypes)) {
                    foreach ($activityTypes as $activityType) {
                        $data[formatString($activityType,false,true)] = $group->where('activity_type', $activityType)->count() ??  0;
                        $data['activity_status'] = (in_array('activity_status',$this->reportParams['requiredFields'])) ? $key : null;
                        $data['total_count_of_activities'] = (in_array('total_count_of_activities',$this->reportParams['requiredFields'])) ?  $group->count()  : 0;
                    }
                }
                
                return $data;
            }); 
            // Sort By Month & Year in correct order
            if(isset($this->reportParams['groupByExtraFields']['format'])){ // For Month/Year
                    $result = $result->sortBy(function ($item, $key) {                 
                    return strtotime($key);
                });
            }    
            
        } 
        
        if (isset($this->reportParams['goToMapDynamicFuntion'])) { // Go straight to map dynamic Function
             return $result;    
        }
        
        if($this->reportParams['remmoveNullKey'] === true){ unset($result[null]); } 
        $result = array_values($result->toArray());
        
        // dd($result);
        return $result;
    }
    

    public function getDynamicFieldsAccordingToReportType(){           

            if ($this->reportParams['haveDynamicFields'] === true) {
                    $dynamicFields = $this->getDynamicFields();                    
                    $this->reportParams['defaultFields'] = $this->reportParams['defaultFieldsNotAllowed'] === true ?  array_merge($this->reportParams['defaultFields'],$dynamicFields) : $dynamicFields;
            }
            return $this->reportParams['defaultFields'];

    }    

    public function getDynamicFields(){
        $result = array();
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[1] ) {
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[2] ) {            
            $result =  array_merge($this->formatDataAccodingToVueTable($this->getUsersData(false,true),true,'currency'),$this->formatDataAccodingToVueTable(['Total Count Of Deals'],true,'number'));
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[3] ) {

            if ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0]) { // for number of deals
                $result =  $this->formatDataAccodingToVueTable(config('constants.salespipeline.deal_stages.stage'));                
            }
            if ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[3]) { // group by users
                
                $result = array_merge($this->formatDataAccodingToVueTable(['Username'],true,'label','Total'),$this->formatDataAccodingToVueTable(config('constants.salespipeline.deal_stages.stage')));                
            }
             
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[4] ) {
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[5] ) {
            $result =  $this->formatDataAccodingToVueTable(config('constants.salespipeline.miscellaneous.deal_lost_reasons.label'));
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[6] ) {                  
            $result =  array_merge($this->formatDataAccodingToVueTable(config('constants.salespipeline.miscellaneous.actvity_types.label'),true,'nummber'),$this->formatDataAccodingToVueTable(['Total Count Of Activities'],true,'number'));       
        }
        return $result;

    }   
    
    public function addKeysInCollectionAccordingToReportType($collection){
        $result = array();
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[1] ) {
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[2] ) {
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[3] ) {
            
        }   
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[4] ) {
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[5] ) {
            $result =  config('constants.salespipeline.miscellaneous.deal_lost_reasons.label');
            $result = (count($result) > 0) ? $this->addKeysInCollection($collection,$result,true) : $result;
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[6] ) {                  
                    
        }        

        return $result;
    } 
    
    public function chartDataAccordingToReport($collection){
        $result = array();
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[1] ) {
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[2] ) {
                    
                     $result['main'] = array_column($collection,'deal_won_month_year'); // For Deal Won Over Time Report (Stacked Chart)
                        foreach ($collection as $key => $value) {                                                      
                            foreach ($value as $childKey => $childValue) {                          
                                if ($childKey != 'deal_won_month_year' && $childKey != 'total_count_of_deals') {                              
                                    $arr[$childKey]['label'] = formatString($childKey,false,false);
                                    $arr[$childKey]['data'][] = $value[$childKey];
                                    $arr[$childKey]['backgroundColor'] = User::where('username',$arr[$childKey]['label'])->first()->colour ?? null; // get colour of user
                                    $arr[$childKey]['barThickness'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.barThickness');
                                    $arr[$childKey]['borderWidth'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.borderWidth');
                                    $arr[$childKey]['fill'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.fill');
                                }
                            }                          
                    } 
                    $result['datasets'] = array_values($arr);
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[3] ) {  // For Deal Progress Report (Stacked Chart)          
                if ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0]) { // for number of deals
                    $result = $this->getDynamicChartData($collection,'deal_stage_entered_month_year',['deal_stage_entered_month_year','username'],config('constants.salespipeline.deal_stages.stage'),config('constants.salespipeline.deal_stages.colour'));                    
                }
                if ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[3]) { // group by users
                    $result = $this->getDynamicChartData($collection,'username',['deal_stage_entered_month_year','username'],config('constants.salespipeline.deal_stages.stage'),config('constants.salespipeline.deal_stages.colour'));                    
                }
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[4] ) {
            
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[5] ) {                  
                    
        }
        if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[6] ) { // For Deal Actvity Report (Stacked Chart)          
             $result = $this->getDynamicChartData($collection,'activity_status',['activity_status','total_count_of_activities'],config('constants.salespipeline.miscellaneous.actvity_types.label'),config('constants.salespipeline.miscellaneous.actvity_types.colour'));
        }        
        
        return $result;
    } 
    
    public function getDynamicChartData($collection,$main,$notAllowdKeys = [],$labels = [],$colors = []){
                $result['main'] = array_column($collection,$main);
                    foreach ($collection as $key => $value) {                          
                        foreach ($value as $childKey => $childValue) {                          
                            if (in_array($childKey,$notAllowdKeys) === false) {                              
                                $chartData[$childKey]['label'] = formatString($childKey,false,false);
                                $chartData[$childKey]['data'][] = $value[$childKey];
                                $chartData[$childKey]['backgroundColor'] = getKeyFromArrayAndThenValueFromAnotherArray($childKey,$labels,$colors);
                                $chartData[$childKey]['barThickness'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.barThickness');
                                $chartData[$childKey]['borderWidth'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.borderWidth');
                                $chartData[$childKey]['fill'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.fill');
                            }
                        }                          
                }
                $result['datasets'] = array_values($chartData);
                return $result;
    }
    
    public function formatDataAccodingToVueTable($data,$sortable = true, $fieldType = 'number',$lastRowlabel = false){
            $result =  array();
            $count = 0;
            foreach ($data as $key => $value) {               
                    $result[$count]['key'] = formatString($value,false,true);
                    $result[$count]['label'] = $value;
                    $result[$count]['sortable']= $sortable;
                    $result[$count]['fieldType']= $fieldType;
                    $result[$count]['lastRowlabel']= $lastRowlabel;
                    $count++;
            }
            return $result;

    }
        

    public function getSummaryDataByUsers($reportData){
           $userDeals = $this->getFormattedData($reportData);        
           return $userDeals;
           
    }    

    public function getAverageDealsValue($data){
        return $average_amount = $data['total_amount']/$data['total_deals'];
    }
   

    public function getOnlyReportListingData($reportType,$params){        
        // Added Filter Params tab name listing
        (!isset($this->reportParams)) ? $this->setDefaultParmsAccordingToReportType($reportType,'model','common',$params) :  false;
        $query = $this->getFiltersQuery();
             
        $reportData = $this->getListingTabData($query);
        return $reportData;
    }

    public function getUsersData($forFilter = false , $onlyUsernames = false)
    {  
        if($forFilter === true){
            $users = User::whereIn('role_id',config('constants.salespipeline.miscellaneous.rolesAllowed.forSales'))->where('id','>',config('constants.miscellaneous.mainUserId'))->get(['id as value','username as text']);
        }
        if ($onlyUsernames === true) {            
            $users = array_column(User::whereIn('role_id',config('constants.salespipeline.miscellaneous.rolesAllowed.forSales'))->where('id','>',config('constants.miscellaneous.mainUserId'))->get()->toArray(),'username');
        }

        return $users;
    }

    public function getFiltersQuery(){
        
        $query = $this->mainQuery(); // Default
        
        // default field
        if (config('constants.salespipeline.miscellaneous.reportBelongsTo')[1] === $this->reportParams['reportBelongsTo']) {
                $filterField = (isset($this->reportParams['params']['filterField'])) ? $this->reportParams['params']['filterField']: 'deals.created_at';
        }
        if (config('constants.salespipeline.miscellaneous.reportBelongsTo')[2] === $this->reportParams['reportBelongsTo']) {
                $filterField = (isset($this->reportParams['params']['filterField'])) ? $this->reportParams['params']['filterField']: 'deal_activities.created_at';
        }

        //dynamic filtes

        if (isset($this->reportParams['params']['filterType'])) {

            if ($this->reportParams['params']['filterType'] === 'select') {                
                $query = (isset($this->reportParams['params']['field'])) ? $query->where($this->reportParams['params']['field'],$this->reportParams['params']['value']) : $query;                
            }
            if ($this->reportParams['params']['filterType'] === 'input') {
                if ($this->reportParams['params']['isRangeType'] == true) {
                    $query = (isset($this->reportParams['params']['field'])) ? $query->where($this->reportParams['params']['field'],$this->reportParams['params']['range'],$this->reportParams['params']['value']) : $query;                    
                }
            }
            if (($this->reportParams['params']['filterType'] === 'daterange') || !isset($this->reportParams['params']['filterType'])) {
                if (isset($this->reportParams['params']['value'])) {                
                    if (is_array($this->reportParams['params']['value']) ) {
                        $query = (isset($this->reportParams['params']['field'])) ? $query->whereIn($this->reportParams['params']['field'],$this->reportParams['params']['value']) : $query;             
                    }else{
                        if(isset($this->reportParams['params']['isDefaultFilterField'])){
                            if($this->reportParams['params']['isDefaultFilterField'] == true){
                                $query = (isset($this->reportParams['params']['field'])) ? $query->where($this->reportParams['params']['field'],$this->reportParams['params']['value']) : $query;
                            }
                        }
                    }
                }                
            }
        }

        $this->getFilterDefaultValues(true); // For Only Daterange
        
        // For model/popup
        if (isset($this->reportParams['params']['extraField']) && $this->reportParams['params']['type'] == 'basic'  ) {
            if ($this->reportParams['reportType'] === config('constants.salespipeline.miscellaneous.reportTypes')[6] ) {  // Update Value to status id
                $this->reportParams['params']['extraValue'] = array_search($this->reportParams['params']['extraValue'],config('constants.salespipeline.miscellaneous.activity_status.label'));              
            }
            $query = $query->where($this->reportParams['params']['extraField'], $this->reportParams['params']['extraValue']);
        }
        
        if (isset($this->reportParams['params']['extraField']) && $this->reportParams['params']['type'] == 'monthYear' ) {
            $date = Carbon::parse($this->reportParams['params']['extraValue']);
            $query = $query->whereYear($this->reportParams['params']['extraField'], '=', $date->year)
                           ->whereMonth($this->reportParams['params']['extraField'], '=', $date->month);
        }
        // Add Default startDate & endDate
        $query = (isset($this->reportParams['params']['startDate'])) ? $query->whereDate($filterField,'>=', $this->reportParams['params']['startDate']) : $query;
        $query = (isset($this->reportParams['params']['endDate'])) ? $query->whereDate($filterField,'<=',$this->reportParams['params']['endDate']) : $query;
        return $query;
    }

    public function addExtraFilters($query,$params = []){        
        $query = (isset($params['field'])) ? $query->where($params['field'], $params['value']) : $query;
        return $query;
    }
   
    
    public function getMaxCount(){

            // Will return at least 10 as max if 0            
            if ($this->maxCount['value'] == null) {
                return (int) config('constants.salespipeline.miscellaneous.graphs.horizontal_bargraph_data.maxDealsCount');
            }else{
                $digits = strlen((string) round($this->maxCount['value'],0));
                $number = (int) getNumberOfParticularLength($digits); 
                $this->maxCount['value'] = ($this->maxCount['value'] < 1000) ? closestMultiple($this->maxCount['value'],config('constants.salespipeline.miscellaneous.graphs.horizontal_bargraph_data.multipleValue')) : closestMultiple($number,config('constants.salespipeline.miscellaneous.graphs.horizontal_bargraph_data.multipleValue'));
                return (int) ($this->maxCount['value'] === 0 || $this->maxCount['value'] == null ) ? config('constants.salespipeline.miscellaneous.graphs.horizontal_bargraph_data.multipleValue')  : $this->maxCount['value'];    
            }
           
    }

    public function getFilterDefaultValues($inFilters = false){
        $defaulFilterDateValues = array();        
        if (!(isset($this->reportParams['params']['startDate']) && $this->reportParams['params']['endDate'])) {
            if($inFilters === true){
                    $this->reportParams['params']['startDate'] = $this->getDateTimeValue('startDate');
                    $this->reportParams['params']['endDate'] = $this->getDateTimeValue('endDate');

            }else{
                $defaulFilterDateValues['startDate'] = date(config('constants.salespipeline.miscellaneous.date.current_year.start_date'));
                $defaulFilterDateValues['endDate'] = date(config('constants.salespipeline.miscellaneous.date.current_year.end_date'));
            }      
        }else{
            // Keep current values
            $defaulFilterDateValues['startDate'] = $this->reportParams['params']['startDate'];
            $defaulFilterDateValues['endDate'] = $this->reportParams['params']['endDate'];
        }   
        return $defaulFilterDateValues;
    }

    public function getColumnNamesAndAddExtraFields($data){        
        $fields = array_keys((array)$data);
        $data = array();
        $count = 0;
        foreach ($fields as $field) {
            $data[$count]['key'] = $field;
            $data[$count]['sortable'] = true;
            $count++;
        }
        return $data;
    }

    public function getChartQuery($query){           
        
        $query = $this->getChartDataAccordingToSortType($query);
        return $query;
    }

    public function getChartDataAccordingToSortType($query){      
        $query = $query->get($this->reportParams['get'])->each->setAppends($this->reportParams['append']);

        return $query;
    }
    
    public function getParticularGraphData($query)
    {   
        $data = !isset($this->itemsData) ? $this->getChartQuery($query) : [];
        $datasets = $this->getDatasetsAccordingToChartSortType($data);

        $chartData = $this->getChartData($datasets);
        return $chartData;
    }
    

    public function getChartData($datasets){ 
            
            if($this->reportParams['isChartStacked'] === true) { 
                return $datasets;
            }else{
                $this->maxCount['value'] = (isset($datasets['data']) && count($datasets['data']) > 0 ) ?  max($datasets['data']) : 10;
                $result = array();            
                $result['main'] = isset($datasets['main']) ? $datasets['main'] : [];            
                $result['datasets'][0]['label'] = isset($datasets['labels']) ? $datasets['labels'] : $this->getChartLabel();
                $result['datasets'][0]['backgroundColor'] = isset($datasets['backgroundColor']) ? $datasets['backgroundColor'] : [];
                $result['datasets'][0]['data'] = isset($datasets['data']) ? $datasets['data'] : [];
                $result['datasets'][0]['barThickness'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.barThickness');
                $result['datasets'][0]['borderWidth'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.borderWidth');                
                $result['datasets'][0]['fill'] = config('constants.salespipeline.miscellaneous.graphs.defaultDatasets.fill');
            }

          return $result;
    }

    public function getChartLabel(){
        $label = '';
        $label = ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0]) ? formatString($this->reportParams['params']['chartSortType']) : $label;
        $label = ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[1]) ? formatString($this->reportParams['params']['chartSortType'],true) : $label;
        return $label;      
    }

    public function getDatasetsAccordingToChartSortType($reportData){       
        $datasets = array();  
        $reportData =   !isset($this->itemsData) ? $this->getFormattedData($reportData) : $this->itemsData;
        // dd($this->reportParams['chartDefaultFields']);
        if (array_key_exists('dynamic',$this->reportParams['chartDefaultFields']) === true){        
                    $datasets = $this->filterChartDataIfDynamic($reportData);
        }else{
            foreach ($this->reportParams['chartDefaultFields'] as $key => $value) {            
                    $datasets[$key] = array_column($reportData, $value);
            }
        
        }
        return $datasets;
    }

    public function filterChartDataIfDynamic($reportData){     
            $datasets = $this->chartDataAccordingToReport($reportData);
            return $datasets;
                
    }

    public function getReportsListing($reportType,$params){
            $data = $this->getListingData($reportType,$params);
            return $data;

    }

    public function export($reportType,$username = null,$params){
        $tabType = isset($params['tabType']) ? $params['tabType'] : config('constants.salespipeline.miscellaneous.tabs.adminReportsTabs')[0]; // Default Tab
        (!isset($this->reportParams)) ? $this->setDefaultParmsAccordingToReportType($reportType,$tabType,'export',$params) :  false;
        $reportData = $this->getOnlyReportListingData($this->reportParams['reportType'],$this->reportParams['params']);
        $data['items'] = $reportData->toArray();
        $fields = config('constants.salespipeline.reports.reportType')[$this->reportParams['reportType']]['export']['fields'];        
        $data['items'] = mapArray($data['items'],$fields);
        
        if (count($data['items']) > 0) {
            $data['headings'] = $this->getExportHeadings(false,$data['items'][0]);
            $result['success'] = true;
            $result['export'] = new DealsExport($data);
        }else{
            $result['success'] = false;
            $result['export'] = array();
        }
        return $result;
    }    

    public function getExportHeadings($isDynamic = false,$data = []){  
        if ($isDynamic) {
            $headings = array_keys($data); 
            array_walk($headings, function(&$value){
                return formatString($value);  
            });            
        }else{            
            $headings = config('constants.salespipeline.reports.reportType')[$this->reportParams['reportType']]['export']['headings'];
        }
        return $headings;
    }

    public function addKeysInCollection($collection,$keys = [],$formatKey = true,$defaultValue = null){
        
        if (count($keys) > 0) {
            foreach ($collection as $key => $value) {
                foreach ($keys as $newKey) {
                    $newKey = ($formatKey === true ) ? formatString($newKey,false,true) : $newKey;                    
                    $value->$newKey = $defaultValue;
                }
            }       
        }
        return $collection; 

    }


    public function isJoined($query, $table){
        $joins = collect($query->getQuery()->joins);
        return $joins->pluck('table')->contains($table);
    }

    public function extraJoins($query,$JoinForModel = false){        
        // Check if table is already joined with the requested 
        (isset($this->reportParams['join']['left']) === true && $this->isJoined($query, $this->reportParams['join']['left']['secondTable']) === false) ?
            $query->leftJoin(
            $this->reportParams['join']['left']['secondTable'],
            $this->reportParams['join']['left']['secondTableId'],
            $this->reportParams['join']['left']['operater'],
            $this->reportParams['join']['left']['mainTable']
        ) : $query;

        return $query;
    }

    public function getMonthListFromDate($start,$end)
    {   
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        
        foreach (CarbonPeriod::create($start, '1 month',$end) as $month) {
                $months[$month->format(config('constants.salespipeline.miscellaneous.date.sortBy.format'))] = null;
        }
        return $months;
    }

    public function mergeCollectionDataBasedOnDateAndYear($reportData){           
        // Set Start Date and End Date Time To Max And Min of the Sql Database of the column              
        // dd($this->reportParams);
        $startDate =  isset($this->reportParams['params']['startDate']) ? $this->reportParams['params']['startDate'] : $this->getDateTimeValue('startDate');
        $endDate =   isset($this->reportParams['params']['endDate']) ? $this->reportParams['params']['endDate'] : $this->getDateTimeValue('endDate');
        $dates =  $this->getMonthListFromDate($startDate,$endDate); 
        $reportData = $reportData->union($dates);
        return $reportData;
    }

    public function getDateTimeValue($dateType = 'startDate'){     
        
        // Get current date if 
        $yearStartDate = date(config('constants.salespipeline.miscellaneous.date.current_year.start_date'));
        $yearEndDate = date(config('constants.salespipeline.miscellaneous.date.current_year.end_date'));
        $yearStartDateTime = (int) strtotime($yearStartDate);
        $yearEndDateTime = (int) strtotime($yearEndDate);
        if(isset($this->reportParams['params']['filterType']) && $this->reportParams['params']['filterType'] != 'daterange'){ // Date Range Should be updated to min and max databae field values if filter is equal to daterange

            if ($dateType == 'startDate') {
                $databaseMinDateTime = (int) strtotime(DB::table($this->reportParams['wholeRequestType']['defaultDateRange']['table'])->min($this->reportParams['wholeRequestType']['defaultDateRange']['field']));
                $date = ($yearStartDateTime <= $databaseMinDateTime ) ? $yearStartDateTime : $databaseMinDateTime;
            }
            
            if ($dateType == 'endDate') {
                $databaseMaxDateTime = (int) strtotime(DB::table($this->reportParams['wholeRequestType']['defaultDateRange']['table'])->max($this->reportParams['wholeRequestType']['defaultDateRange']['field']));
                $date = ($yearEndDateTime >= $databaseMaxDateTime ) ? $yearEndDateTime : $databaseMaxDateTime;
            }
            
            return date(config('constants.salespipeline.miscellaneous.date.format.database'),$date);
        }else{
            $date = ($dateType == 'startDate') ? $yearStartDate : $yearEndDate;
            return $date;
        }
        
        
    }
    

    public function setDefaultParmsAccordingToReportType($reportType,$tab,$requestType,$params,$isSummary = false){
            $report = config('constants.salespipeline.reports.reportType');            
           
            $this->reportParams['params'] = (isset($params) == true && count($params) == 0) ? $report[$reportType]['defaultFilterParams'] : $params;  // Set Default Report Params
            $this->reportParams['defaultFilterParams'] = (isset($params) == true && count($params) == 0) ? true : false; 
            $this->reportParams['reportType'] = $reportType;
            $this->reportParams['tab'] = $tab;
            $this->reportParams['isSummary'] = $isSummary;            
            $this->reportParams['requestType'] = $requestType;
            $this->reportParams['wholeRequestType'] = $report[$reportType]['sql'][$tab][$requestType];
            $this->reportParams['chartType'] = $report[$reportType]['chart'];
            $this->reportParams['isChartStacked'] = $report[$reportType]['isChartStacked'];            
            $this->reportParams['append'] = $report[$reportType]['sql'][$tab][$requestType]['append'];
            
            $this->reportParams['get'] = $report[$reportType]['sql'][$tab][$requestType]['get'];
            $this->reportParams['join'] = $report[$reportType]['sql'][$tab][$requestType]['join'];
            $this->reportParams['with'] = $report[$reportType]['sql'][$tab][$requestType]['with'];
            $this->reportParams['remmoveNullKey'] = $report[$reportType]['sql'][$tab][$requestType]['remmoveNullKey'];            
            $this->reportParams['defaultFields'] = $report[$reportType]['fields'][$tab];
            $this->reportParams['haveDynamicFields'] = $report[$reportType]['sql'][$tab][$requestType]['haveDynamicFields'];                
            $this->reportParams['params']['chartSortType'] = (isset($this->reportParams['params']['chartSortType'])) ? $this->reportParams['params']['chartSortType'] : config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0];  // Default Param For Chart             

            // get fields data according to the chartSortType
            $this->reportParams['requiredFields'] = isset($report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType']) ? $report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType'][$this->reportParams['params']['chartSortType']]['requiredFields'] :  $report[$reportType]['sql'][$tab][$requestType]['requiredFields'];
            $this->reportParams['groupBy'] = isset($report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType']) ? $report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType'][$this->reportParams['params']['chartSortType']]['groupBy'] : $report[$reportType]['sql'][$tab][$requestType]['groupBy'];

            $this->reportParams['defaultFieldsNotAllowed'] = isset($report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType']) ? $report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType'][$this->reportParams['params']['chartSortType']]['defaultFieldsNotAllowed'] : true;

            $this->reportParams['groupByUsingCollectionMethod'] = isset($report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType']) ? $report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType'][$this->reportParams['params']['chartSortType']]['groupByUsingCollectionMethod'] : $report[$reportType]['sql'][$tab][$requestType]['groupByUsingCollectionMethod'];             
            $this->reportParams['groupByIsDynamic'] = isset($report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType']) ? $report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType'][$this->reportParams['params']['chartSortType']]['groupByIsDynamic'] :  $report[$reportType]['sql'][$tab][$requestType]['groupByIsDynamic'];
            $this->reportParams['groupByExtraFields'] = isset($report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType']) ? $report[$reportType]['sql'][$tab][$requestType]['multipleChartSortType'][$this->reportParams['params']['chartSortType']]['groupByExtraFields'] :  ( isset($report[$reportType]['sql'][$tab][$requestType]['groupByExtraFields']) ?  $report[$reportType]['sql'][$tab][$requestType]['groupByExtraFields'] : null);
            
            $this->reportParams['chartHasSameAsGetFields'] = isset($report[$reportType]['sql'][$tab]['chart']['chartHasSameAsGetFields']) ? $report[$reportType]['sql'][$tab]['chart']['chartHasSameAsGetFields'] : null;
            $this->reportParams['chartDefaultFields'] = isset($report[$reportType]['sql'][$tab]['chart']['chartDefaultFields'][$this->reportParams['params']['chartSortType']]) ? $report[$reportType]['sql'][$tab]['chart']['chartDefaultFields'][$this->reportParams['params']['chartSortType']] : null; // Params For Chart Type
            $this->reportParams['reportBelongsTo'] = $report[$reportType]['reportBelongsTo'];



            if ($requestType == 'chart') {
                // dd($this->reportParams);
            }

                
    }

   
}























// Unused Code

// $query->select([DB::raw('CAST(deals.created_at AS DATE) AS "Deal created"') ,'title','username AS owner','organisation','turnover As value','expected_close_date'])
//         ->join('deals', 'deal_organisations.deal_id', '=', 'deals.id')
//         ->join('users', 'users.id', '=', 'deals.owner_id')
//         ->get();            



// DB::enableQueryLog();   
// dd($query->toSql());
// dd(DB::getQueryLog());

// $params['chartSortType'] = (isset($params['chartSortType'])) ? $params['chartSortType'] : config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0];
// $query = ($params['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0]) ? $query->groupBy('owner_id')->select('owner_id', DB::raw('count(*) as total'))->with('dealOwner:id,username,colour')->get(): $query;
// $query = ($params['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[1]) ? $query->groupBy('owner_id')->select('owner_id', DB::raw('SUM(turnover) AS total'))->with('dealOwner:id,username,colour')->orderBy('total', 'DESC')->get() :  $query;
// $query = ($params['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[1]) ? $query->get($get)->each->setAppends($appends) :  $query;
// $this->maxCount['value'] = ($params['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0]) ? $query->count() : $query[0]->total;
// $this->reportParams['params']['chartSortType'] = $params['chartSortType'];

// $defaulFilterDateValues['startDate'] = $this->model->min('created_at');
// $defaulFilterDateValues['startDate'] = Carbon::parse($defaulFilterDateValues['startDate'])->format(config('constants.salespipeline.miscellaneous.date.format.database'));
// $defaulFilterDateValues['endDate'] = $this->model->max('created_at');
// $defaulFilterDateValues['endDate'] = Carbon::parse($defaulFilterDateValues['endDate'])->format(config('constants.salespipeline.miscellaneous.date.format.database'));


// dd($sumn);
// $count = 0;
// foreach ($reportData as $deal) {            
//     $result[$deal->username]['count'] = isset($result[$deal->username]['count']) ? $result[$deal->username]['count'] + $result[$deal->username]['count'] : 1;
//     if (isset($result[$deal->username]['total_amount']) && isset($result[$deal->username]['owner_id']) ) {                
//         $result[$deal->username]['total_amount'] = $this->getConvertedCurrencyData(['deal_id' => $deal->id]) + $result[$deal->username]['total_amount'];
//         $result[$deal->username]['total_deals'] = $result[$deal->username]['count'];
//     }else{           

//         $result[$deal->username]['total_amount'] = $this->getConvertedCurrencyData(['deal_id' => $deal->id]);
//         $result[$deal->username]['owner_id'] = $deal->owner_id;
//         $result[$deal->username]['username'] = $deal->username;                
//         $result[$deal->username]['total_deals'] = $result[$deal->username]['count'];
//         ($forChart === true) ? $result[$deal->username]['backgroundColor'] = $deal->dealOwner->colour : false;                
//     }                
// }


// dd($query);       
       
        
// // if ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[0]) {
// //         $query = $query->groupBy('owner_id')->select('owner_id', DB::raw('count(deals.id) as total'))->with('dealOwner:id,username,colour')->get();
// //         $this->maxCount['value'] = $query->count();
// // }
// if ($this->reportParams['params']['chartSortType'] == config('constants.salespipeline.miscellaneous.graphs.chartSortType')[1]) {
        // }
