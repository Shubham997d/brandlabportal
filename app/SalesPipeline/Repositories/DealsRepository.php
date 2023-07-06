<?php

namespace App\SalesPipeline\Repositories;

use App\SalesPipeline\Models\SalesPipeline;
use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\StageDetails;
use App\SalesPipeline\Models\DealOrganisation;
use App\SalesPipeline\Models\DealPerson;
use App\SalesPipeline\Models\DealActivity;
use App\SalesPipeline\Models\DealNotes;
use App\SalesPipeline\Models\DealInvoice;
use App\SalesPipeline\Models\DealLostReason;
use App\SalesPipeline\Models\DealInvoiceDefault;
use App\SalesPipeline\Models\DealStages;
use App\SalesPipeline\Models\DealEmailStatus;
use App\Project\Models\Project;
use App\Project\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DealsImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Exports\DealsExport;
use Illuminate\Support\Facades\Notification;
use App\SalesPipeline\Notification\DealTransferOwnership;
use App\SalesPipeline\Models\DealCategoriesData;

use PDF;

class DealsRepository
{
    protected $model;

    public function __construct(Deals $deals)
    {
        $this->model = $deals;
        ini_set('max_execution_time', 6000);
    }

    public function store($data)
    {

        // Only will be admin to create deal for other users
        
        
        $deal = Deals::create([
                    'pipeline_stage'      => $data['data']['stage'] ?? config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deals_pipeline_stage'),
                    'status'              => config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deals_status'),
                    'owner_id'            => auth()->user()->isadmin() === true && isset($data['data']['owner']['id']) ? $data['data']['owner']['id'] : auth()->user()->id,
                    'creator_id'          => auth()->user()->id 
                ]);

        if(isset($deal->id)){
            $deal_id = $deal->id;

            DealOrganisation::create([
                'deal_id'     => $deal_id,
                'organisation' => $data['data']['organisation'],
                'title'     => $data['data']['title'] ?? null,
                'turnover'         => $data['data']['turnover'] ?? config('constants.salespipeline.miscellaneous.currency.default.price'),
                'currency_code'    => $data['data']['currency']['code'] ?? config('constants.salespipeline.miscellaneous.currency.default.code'),
                'expected_close_date'   => $data['closeDateField'] ?? null,
                'deal_color'      => config('constants.salespipeline.miscellaneous.dealColors.colors.default'),
                'deal_color_update_datetime'  => date(config('constants.salespipeline.miscellaneous.datetime.format.database'))

            ]);
            if ($data['data']['formActivity']['events']['start_date'] != null) {
            
            $event = array();
            $event['start'] = $data['data']['formActivity']['events']['start_date'].'T'.$data['data']['formActivity']['events']['start_time'];
            $event['end']   = $data['data']['formActivity']['events']['end_date'].'T'.$data['data']['formActivity']['events']['start_time'];
            $event['value'] = $data['data']['formActivity']['events']['value'];
            $event['type'] = $data['data']['formActivity']['events']['type'];

            DealActivity::create([
                'deal_id'     => $deal_id,
                'user_id' => $data['data']['formActivity']['userId'],
                'creator_id' => auth()->user()->id,
                'schedule_status'     => $data['data']['formActivity']['scheduler'],
                'notes'     => $data['data']['formActivity']['notes'],
                'deal_name'     => $data['data']['formActivity']['deal'],
                'activity_data' => json_encode($event),
                'timeline_time_in' => $event['start'],
                'timeline_time_out' => $event['end'],
                'activity_subject' => $event['value'],
                'activity_type' => $event['type'],
                'status' => $data['data']['formActivity']['status'] == true ? 1 : 0,
                'status_datetime' => $data['data']['formActivity']['status'] == true ? date(config('constants.salespipeline.miscellaneous.datetime.format.database')) : null
            ]);
            }
            DealEmailStatus::create([
                'deal_id'      => $deal_id,
                'reminder_status' => config('constants.salespipeline.deal_email_status.zero')
            ]);

            DealCategoriesData::create([
                'deal_id'     => $deal_id,
                'category_id' => isset($data['data']['category']['id']) ? (int) $data['data']['category']['id'] : config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_categories_data_category_id')
            ]);

            $data['data']['contactPerson'] = trim($data['data']['contactPerson']);
            if (strpos($data['data']['contactPerson'], ' ') !== false) {
                $contactPerson = explode(' ', $data['data']['contactPerson']);
                $firstName = $contactPerson[0];
                $lastName = $contactPerson[1];
            }else{
                $firstName = $data['data']['contactPerson'];
                $lastName = '';
            }

            DealPerson::create([
                'deal_id'     => $deal_id,
                'first_name' => $firstName,
                'last_name'     => $lastName ?? null,
                'phone'         => $data['phoneData'] ?? null, !empty($data['phoneData']) ? $data['phoneData'] : null,
                'source_of_lead' => $data['data']['source_of_lead'] ?? null,
                'email'      => !empty($data['emailData']) ? $data['emailData'] : null,
            ]);

            return $deal;
        }

    }

    public function updateDealOrganisation($deal_id, $data)
    {
        return $dealOrganisation = DealOrganisation::where('deal_id', $deal_id)->updateOrCreate([
            'organisation' => $data['data']['Organisation'],
            'title'     => $data['data']['Title'] ?? null,
            'website'         => $data['data']['Website'] ?? null,
            'address'    => $data['data']['Address'] ?? null,
            'phone'    => $data['data']['Phone'] ?? null,
            'turnover'         => $data['data']['turnover'] ?? config('constants.salespipeline.miscellaneous.currency.default.price'),
            'currency_code'    => $data['data']['currency']['code'] ?? config('constants.salespipeline.miscellaneous.currency.default.code'),
            'expected_close_date'      => $data['closeDateField'] ?? null,
        ])->first();        
        
    }

    public function addDealNote($data){

        return $dealnote = DealNotes::create([
            'deal_id'     => $data['data']['deal_id'] ?? null,
            'title' => $data['data']['title'] ?? null,
            'description'     => $data['data']['description'] ?? null,
        ]);
        return $dealnote;

    }

    public function updateDealNote($data){
        if($data['data']['note_id']){
            DealNotes::where('id', $data['data']['note_id'])->update([
                'title' => $data['data']['title'],
                'description'     => $data['data']['description'] ?? null
            ]);
        }
    }

    public function updateDealPerson($deal_id, $data)
    {   
        $DealPerson = DealPerson::where('deal_id', $deal_id)->first();
        $DealPerson->first_name = $data['personFirstName'] ?? null;
        $DealPerson->last_name = $data['personLastName'] ?? null;
        $DealPerson->source_of_lead = $data['source_of_lead'] ?? null;
        $DealPerson->phone =  !empty($data['phoneData']) ? $data['phoneData'] : null;
        $DealPerson->extra_names =  !empty($data['personNameData']) ? $data['personNameData'] : null;
        $DealPerson->email = !empty($data['emailData']) ? $data['emailData'] : null;
        $DealPerson->save();
        
    }

    public function uploaddealfiles($deal_id, $data)
    {
        $files = json_decode($data['fileData']);
     
        $j= 0;
        $file_data1 = array();
        if(!empty($files)){
            foreach ($files as $file) {
                if(array_key_exists("file",$file)){
                    $file_data1[$j]['name'] = $file->file->name;
                }else{
                $name = substr($file->url, strpos($file->url, "files/") + 6);    
                $file_data1[$j]['name'] = $name;
                }
                $file_data1[$j]['size'] = $file->size;
                $file_data1[$j]['type'] = $file->type;
                $file_data1[$j]['extension'] = $file->extension;
                $j++;
            }

        }
        $file_data2 = array();
        $i= 0;
         if(!empty($data['fileData_new'])){
            foreach ($data['fileData_new'] as $file) {

                $name = $file->getClientOriginalName();
                $type = $file->getMimeType();
                $size = $file->getSize();
                $extension = $file->extension();
                $randomNumber = random_int(100000, 999999);
                $file_path = $file->storeAs('files', pathinfo($name,PATHINFO_FILENAME).'.'.$file->getClientOriginalExtension(), ['disk' => 'public']);
                $file_data2[$i]['name'] = $name;
                $file_data2[$i]['size'] = $size;
                $file_data2[$i]['type'] = $type;
                $file_data2[$i]['extension'] = $extension;
                $i++;
            }
        }

        $file_submit =  array_merge($file_data1,$file_data2);
        $DealPerson = DealPerson::where('deal_id', $deal_id)->first();
        $DealPerson->files = !empty($file_submit) ? $file_submit : null;
        $DealPerson->save();
        
    }

    public function addDealActivity($deal_id,$data){
        // dd(auth()->user()->id);
        $event_filter = array_filter($data['data']['events']);

        $validate = $this->validateDealActivityFields($event_filter);
        if ($validate['status'] === true) {
            $event = array();
            $start_date = date('Y-m-d',strtotime($event_filter['start_date']));
            $end_date   = date('Y-m-d',strtotime($event_filter['end_date']));
               
            $event_filter['start_time'] = $this->timeCorrect($event_filter['start_time'] ?? null);  
            $event_filter['end_time'] = $this->timeCorrect($event_filter['end_time'] ?? null);            
            if (array_key_exists("start_time",$event_filter)){
            $start_time = $event_filter['start_time'];
            }else{
                $start_time = "00:00";
            }
            if (array_key_exists("end_time",$event_filter)){
            $end_time = $event_filter['end_time'];
            }else{
                $end_time = "00:00";
            }
            $event['start'] = $start_date.'T'.$start_time;
            $event['end']   = $end_date.'T'.$end_time;
            $event['value'] = $event_filter['value'];
            $event['type'] = $event_filter['type'];
    
            // $DealActivity = DealActivity::where('deal_id', $deal_id)->first();
            $Dealcreate = DealActivity::create([
                'deal_id'     => $deal_id ?? null,
                'user_id' => $data['data']['userId'] ?? null,
                'creator_id' => auth()->user()->id,
                'schedule_status'     => $data['data']['scheduler'] ?? null,
                'notes'     => $data['data']['notes'] ?? null,
                'deal_name'     => $data['data']['deal'] ?? null,
                'activity_data' => json_encode($event) ?? null,
                'timeline_time_in' => $event['start'] ?? null,
                'timeline_time_out' => $event['end'] ?? null,
                'activity_subject' => $event['value'] ?? null,
                'activity_type' => $event['type'] ?? null,
                'status' => $data['data']['status'] == true ? 1 : 0,
                'status_datetime' => $data['data']['status'] == true ? date(config('constants.salespipeline.miscellaneous.datetime.format.database')) : null
            ]);
            
        }

        return $validate;
       
    }

    public function updateDealActivity($deal_id,$data){
        // print_r($deal_id);
        // print_r($data);
        $event_filter = array_filter($data['data']['events']);
        $validate = $this->validateDealActivityFields($event_filter);
        if ($validate['status'] === true) {
                $event = array();
                $start_date = date('Y-m-d',strtotime($event_filter['start_date']));
                $end_date   = date('Y-m-d',strtotime($event_filter['end_date']));
                
                $event_filter['start_time'] = $this->timeCorrect($event_filter['start_time'] ?? null);     
                $event_filter['end_time'] = $this->timeCorrect($event_filter['end_time'] ?? null);
                if (array_key_exists("start_time",$event_filter)){
                $start_time = $event_filter['start_time'];
                }else{
                    $start_time = "00:00";
                }
                if (array_key_exists("end_time",$event_filter)){
                $end_time = $event_filter['end_time'];
                }else{
                    $end_time = "00:00";
                }
                $event['start'] = $start_date.'T'.$start_time;
                $event['end']   = $end_date.'T'.$end_time;
                $event['value'] = $event_filter['value'];
                $event['type'] = $event_filter['type'];
                
                $activityUpdate = DealActivity::where('id',$data['data']['activityId'])->first();
                if($activityUpdate['status'] == 1 && $data['data']['status'] == false){
                    $activityUpdate->update(['status_datetime' => null]);
                }
                if ($activityUpdate['status'] == 0 && $data['data']['status'] == true) {
                    $activityUpdate->update(['status_datetime' => date(config('constants.salespipeline.miscellaneous.datetime.format.database'))]);
                }

                DealActivity::where('id',$data['data']['activityId'])->update([
                    'user_id' => $data['data']['userId'] ?? null,
                    'schedule_status'     => $data['data']['scheduler'] ?? null,
                    'notes'     => $data['data']['notes'] ?? null,
                    'deal_name'     => $data['data']['deal'] ?? null,
                    'activity_data' => json_encode($event) ?? null,
                    'timeline_time_in' => $event['start'] ?? null,
                    'timeline_time_out' => $event['end'] ?? null,
                    'activity_subject' => $event['value'] ?? null,
                    'activity_type' => $event['type'] ?? null,
                    'status' => $data['data']['status'] == true ? 1 : 0
                ]);

        }

        return $validate;
    }
    

    public function deleteDealActivity($deal_id,$data)
    {
        $dealActivity = DealActivity::where('deal_id', $deal_id)->where('timeline_time_in',$data['data']['start'])->where('timeline_time_out',$data['data']['end'])->delete();
    }

    public function updateDealInvoice($deal_id, $data)
    {
       DealInvoice::create([
            'deal_id'         => $deal_id ?? null,
            'invoice_type'    => $data['data']['formInvoice']['invoice_type'] ?? null,
            'invoice_content' => $data['data']['Invoice'] ?? null,
            'content'         => $data['data']['formInvoice'] ?? null,
            'invoice_number'  => $data['data']['formInvoice']['invoice_number'] ?? null,
            'invoice_date'    => $data['data']['formInvoice']['invoice_date'] ?? null,
            'file_name'       => $data['pdf_name'] ?? null,  
        ]);
    }

    public function downloadDealInvoicePdf($deal_id, $data)
    {
        $compactData = $data;
        $bank_details = DealInvoiceDefault::where('deal_id', $deal_id)->where('content_type', config('constants.salespipeline.deal_invoice_default')['bank_form'])->first();
        if ($bank_details == null) {
            $bank_details = DealInvoiceDefault::where('deal_id', null)->where('content_type', config('constants.salespipeline.deal_invoice_default')['bank_form'])->first();
        }
        $address_details = DealInvoiceDefault::where('deal_id', $deal_id)->where('content_type', config('constants.salespipeline.deal_invoice_default')['address_form'])->first();
        if ($address_details == null ) {
            $address_details = DealInvoiceDefault::where('deal_id', null)->where('content_type', config('constants.salespipeline.deal_invoice_default')['address_form'])->first();
        }
        $pdf_name = "invoice".uniqid().".pdf";
        $pdf = PDF::loadView('pdf/invoice',['name' => $compactData['data'],'bank_detail' => $bank_details,'address_details' => $address_details]);
        Storage::put('public/pdf/'.$pdf_name, $pdf->output());

        return $pdf_name;
    }

    public function updateDealInvoiceDefault($deal_id, $data)
    {
        // $id = StageDetails::latest('id', 'desc')->first();
        // dd($id);
        // dd($data);
        if ($data['data']['field_type'] != null) {
        if ($data['data']['field_type'] == config('constants.salespipeline.deal_invoice_default')['bank_form']) {
            $content = $data['data']['bankFields'];
        }else{
            $content = $data['data']['addressFields'];
        }
       $dealInvoiceDefault = DealInvoiceDefault::where('deal_id', $deal_id)->where('content_type', $data['data']['field_type'])->first();
        if ($dealInvoiceDefault == null) {
            DealInvoiceDefault::create([
                 'deal_id'         => $deal_id ?? null,
                 'content_type'    => $data['data']['field_type'] ?? null,
                 'content' => $content ?? null,
             ]);
        }else{
            DealInvoiceDefault::where('deal_id', $deal_id)->where('content_type', $data['data']['field_type'])->update([
                'deal_id'         => $deal_id ?? null,
                'content_type'    => $data['data']['field_type'] ?? null,
                'content' => $content ?? null,
            ]);
        }
        return "success";
    }

    }

    public function updateDealLostReason($deal_id, $data)
    {
        $DealLostReason = DealLostReason::where('deal_id', $deal_id)->first();
        if($DealLostReason == null)
        {
            DealLostReason::create([
                'deal_id'     => $deal_id ?? null,
                'reason_id' => $data['data']['reason'] ?? null,
                'notes'     => $data['data']['notes'] ?? null,
            ]);
        }else{
            DealLostReason::where('deal_id', $deal_id)->update([
                'deal_id'     => $deal_id ?? null,
                'reason_id' => $data['data']['reason'] ?? null,
                'notes'     => $data['data']['notes'] ?? null,
            ]);
        }
            
        
    }



    public function transferDealOwnership($deal_id, $data)
    {
        $owner = $data['owner'];
        $dealData = $this->model->find($deal_id);
        if($dealData->owner_id == $owner ){
            $result['success'] = false;
        }else{
            $dealData->owner_id = $owner;        
            $dealData->save();
            $result['success'] = true;
        }
        return $result;
        
    }

    public function updateDealStage($data)
    {
        $dealData = $this->model->where('id', $data['id'])->first();
        StageDetails::where('deal_id', $data['id'])->where('stage_id', $dealData->pipeline_stage)->update([
            'end_datetime' => date(config('constants.salespipeline.miscellaneous.datetime.format.database'))
        ]);

        StageDetails::where('deal_id', $data['id'])->where('stage_id', $data['stage_id'])->update([
            'start_datetime' => date(config('constants.salespipeline.miscellaneous.datetime.format.database'))
        ]);

        DealEmailStatus::updateOrCreate(
            ['deal_id' => $data['id']],
            ['reminder_status' => config('constants.salespipeline.deal_email_status.zero')]
        );

        return $this->model->where('id', $data['id'])->update([
            'pipeline_stage' => $data['stage_id'] ?? null
        ]);
        
    }

    public function updateDealStatus($request){ 
        $currentDeal = Deals::where('id',$request['data']['deal_id'])->first();
        $currentDeal->status = $request['data']['status'];        
        $currentDeal->save();

        if ($request['data']['status'] != config('constants.salespipeline.deal.status')['Lost']) {
            $DealLostReason = DealLostReason::where('deal_id', $request['data']['deal_id'])->delete();            
        }      
        Deals::where('id', $request['data']['deal_id'])->update([
            'status' =>  $request['data']['status'] ?? config('constants.salespipeline.deal.status')['Open'],
            'deal_won_datetime'  => ($request['data']['status']  == config('constants.salespipeline.deal.status')['Won']) ? date(config('constants.salespipeline.miscellaneous.datetime.format.database')) : null,
            'deal_lost_datetime' => ($request['data']['status'] == config('constants.salespipeline.deal.status')['Lost']) ? date(config('constants.salespipeline.miscellaneous.datetime.format.database')) : null,
        ]);
        $result['message'] ='Deal Status Updated';
        return $result;        
    }  
    

    public function validateDealActivityFields($data){
            $validated  = true;
            $startDateTime = intval(strtotime($data['start_date'])) + $this->convertTimeToSeconds($this->timeCorrect($data['start_time'] ?? null)); 
            $endDateTime = intval(strtotime($data['end_date'])) + $this->convertTimeToSeconds($this->timeCorrect($data['end_time'] ?? null)); 
            if ($endDateTime >= $startDateTime) {
                $validated = true;
            }else{
                $validated = false;
            }
            if($validated === true) {
                $data['status'] = true;
            }else{
                $data['status'] = false;
                $data['message'] = 'End Date/Time Should be Greater Than Start Date/Time';
            }
            return $data;
              
    }

    public function timeCorrect($data){
        if (isset($data)) {
            $data = explode(":",$data);
            $data[1] = ($data[1] == 'mm') ? '00' : $data[1];
            $data[0] = ($data[0] == 'HH') ? '00' : $data[0];
            $data = implode(":",$data);
            return $data;
        }else{
            return '00:00';
        }

              
    }

    public function convertTimeToSeconds($str){
        
        $seconds = 0;        
        if (isset($str)) {   
            $data = explode(":",$str);
            $seconds += (isset($data[0])) ? $data[0] * 60 * 60: $seconds;
            $seconds += (isset($data[1])) ? $data[1] * 60  : $seconds;
            return intval($seconds);
        }else{
            return $seconds;
        }
        
    }  

    public function dealColourUpdate($request){ // Deal Status update            
            DealOrganisation::where('deal_id',$request['deal_id'])->update([
                    'deal_color' => $request['color'],
                    'deal_color_update_datetime' => date(config('constants.salespipeline.miscellaneous.datetime.format.database'))
            ]);        
    }

    public function uploadDeals($request){ // Deal Status update

        try { 

            $deals = Excel::toArray(new DealsImport(), $request->file('uploadDeals'));
            $filteredData = array();     
            $sliced_array = array();
            unset($deals[0][0]); // Skip headings          
            foreach ($deals[0] as $deal) { // Slice array
                $sliced_array[] = array_slice($deal, 0, count(config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals.columns')));
            }
            $deals[0] = $sliced_array;

            $importColumns = array_merge(config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals.columns'),config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals.defaultColumns'));
            $maxColumns = count($importColumns);
            
            $result = [];
            $result['success'] = true;
            foreach ($deals[0] as $key => $deal) {
                $nullCount =  count(array_filter(array_pad($deal,count($importColumns),null), 'checkNotIsset')); // get null count in array by padding array
                if($nullCount === $maxColumns) { // know which rows are empty
                    unset($deals[0][$key]); 
                }else{
                    $data = $this->getDefaultValuesOfDeals(array_combine($importColumns,array_pad($deal,count($importColumns),null))); 
                    array_walk_recursive($data,function(&$v){ $v = isset($v) ? trim($v) :$v; });                   
                    $validator = $this->validator($data);                    
                    if ($validator->fails()) { 
                        // dd($validator->messages()->get('*'));
                        $result['message'] = formatLaravelErrorsInHtml($validator->messages()->get('*'),$key+2); //+2 beacause array starts at 0 and headings are at 1st row
                        // $result['message'] = 'Please check your import file once, there are some errors in it';                       
                        $result['success'] = false;                        
                    }else{
                        $filteredData[] = $data;
                    }
                    if($result['success'] === false) { break; }
                } 
            }    
            if(isset($result['success']) && $result['success'] == false) {
                $result['success'] = false;                
                return $result;
            }else{
                return $this->insertAllDealsDataInDatabase($filteredData);
            }  
            
        } catch (\Exception $exception) {
                $result['message'] = 'Please check your import file once, there are some errors in it';                       
                $result['success'] = false;  
                dd($exception);
                return  $result;
        }
            
                  
    }

    public function getDefaultValuesOfDeals($filteredData){ // map and add default values
        $dealsImport = config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals');
        //set deal organisation name as deal title if deal organisation name does not exists same for deal title
        $updated_array = array(); 
        foreach ($filteredData as $key => $value) {  
                switch ($key) {
                    case $dealsImport['columns'][0]:                        
                        $deal_organisation_title = isset($filteredData[$dealsImport['columns'][2]]) ? $filteredData[$dealsImport['columns'][2]] : null;
                        $updated_array[$key] = (isset($value) && $value != config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_title')) ? $value : ((($value == config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_title') || !isset($value) ) && isset($deal_organisation_title))  ? $deal_organisation_title : null);
                        break;
                    case $dealsImport['columns'][1]:
                        if (strpos($value, ' ') !== false) {
                            $firstName = explode(' ', $value);                                
                            $updated_array[$key] = $firstName[0];
                        }else{                                
                            $updated_array[$key] = $value;                                
                        }
                        break;
                    case $dealsImport['columns'][2]:                        
                        $deal_organisation_name = isset($filteredData[$dealsImport['columns'][0]]) ? $filteredData[$dealsImport['columns'][0]] : null;                       
                        $updated_array[$key] = (isset($value) && $value != config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_title')) ? $value : ((($value == config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_title') || !isset($value) ) && isset($deal_organisation_name))  ? $deal_organisation_name : config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_title'));
                        break;
                    case $dealsImport['columns'][3]:
                        $updated_array[$key] = $value;
                        break;
                    case $dealsImport['columns'][4]:
                        $updated_array[$key] = $value;
                        break;
                    case $dealsImport['columns'][5]:
                        $updated_array[$key] = $value;
                        break;
                    case $dealsImport['columns'][6]:
                        $updated_array[$key] = isset($value) ? $value : config('constants.salespipeline.miscellaneous.currency.default.price');
                        break;
                    case $dealsImport['columns'][7]:
                        $updated_array[$key] = isset($value) ? $value : config('constants.salespipeline.miscellaneous.currency.default.code');
                        break;
                    case $dealsImport['columns'][8]:
                        try {
                            if(isset($value) && preg_match('/^\d+$/', $value)) {
                                $updated_array[$key] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format(config('constants.salespipeline.miscellaneous.date.format.database'));
                            }else{
                                $updated_array[$key] = Carbon::parse($value)->format(config('constants.salespipeline.miscellaneous.date.format.database'));
                            }
                        } catch (\Exception $e) {
                            $updated_array[$key] = $value;
                         }
                        break; 
                    case $dealsImport['columns'][9]:                            
                            $updated_array[$key] = isset($value) ? (int) $value : config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_categories_data_category_id');
                    break;
                    
                    case $dealsImport['defaultColumns'][0]:
                        $updated_array[$key] = auth()->user()->id;
                        break;
                    case $dealsImport['defaultColumns'][1]:
                        $updated_array[$key] = auth()->user()->id;
                        break;
                    case $dealsImport['defaultColumns'][2]:
                        $updated_array[$key] = config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_deal_color');
                        break;
                    case $dealsImport['defaultColumns'][3]:
                        $updated_array[$key] = date(config('constants.salespipeline.miscellaneous.datetime.format.database'));
                        break;
                    case $dealsImport['defaultColumns'][4]:
                        $updated_array[$key] = config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.stage_details_stage_id');
                        break;
                    case $dealsImport['defaultColumns'][5]:
                        $updated_array[$key] =  date(config('constants.salespipeline.miscellaneous.datetime.format.database'));
                        break;  
                    case $dealsImport['defaultColumns'][6]:
                        $updated_array[$key] =  date(config('constants.salespipeline.miscellaneous.datetime.format.database'));
                        break;
                    case $dealsImport['defaultColumns'][7]:
                        if (strpos($filteredData[$dealsImport['columns'][1]], ' ') !== false) {
                            $lastName = explode(' ', $filteredData[$dealsImport['columns'][1]]);                                
                            $updated_array[$key] = $lastName[1];
                        }else{
                            $updated_array[$key] = null;
                        }                
                        break;
                    case $dealsImport['defaultColumns'][8]:
                        $updated_array[$key] = config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deals_status');
                        break;
                    case $dealsImport['defaultColumns'][9]:
                        $updated_array[$key] =  config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deals_pipeline_stage');
                        break;                    
                    default:
                                                    
                        break;
                }
        }
        return $updated_array;
    
    }


    protected function validator(array $data) // check import data
    {
        // dd($data[config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][2]]);
        return Validator::make($data, [
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][0]           => 'required|string|max:100',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][1]           => 'required|string|max:100',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][2]           => $data[config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][2]] == config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_title') ?  'required|string|max:100' : 'required|string|unique:App\SalesPipeline\Models\DealOrganisation,title|max:100',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][3]           => 'string|nullable|max:100',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][4]           => 'nullable|max:100',            
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][5]           => 'string|nullable|max:100',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][6]           => 'required|numeric',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][7]           => ['required', Rule::in(array_column(config('constants.miscellaneous.currrencies'),'code')) ],
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][8]           => 'required|date_format:Y-m-d',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['columns'][9]           => ['required','integer', Rule::in([1,2])], //right now I am assumming there will be only 2 deal categoreis, add categorei ids here dynamically if incresaed
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][0]    => 'required',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][1]    => 'required',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][2]    => 'nullable',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][3]    => 'nullable',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][4]    => 'required',            
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][5]    => 'required',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][6]    => 'required',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][7]    => 'string|nullable|max:100',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][8]    => 'required',
                config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals')['defaultColumns'][9]    => 'required',
                      
            ]
        ); 
    }


    public function insertAllDealsDataInDatabase($data){
        $dealsImport = config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.import.deals');
        $deal_stages = config('constants.salespipeline.deal_stages.stage');
        foreach ($data as $key => $value) {

            $deals = new Deals;
            $deal = $deals->create([
                'pipeline_stage'      => $value[$dealsImport['defaultColumns'][9]],
                'status'              => $value[$dealsImport['defaultColumns'][8]],
                'owner_id'            => $value[$dealsImport['defaultColumns'][0]],
                'creator_id'          => $value[$dealsImport['defaultColumns'][1]],
            ]);

            if(isset($deal->id)){
                $dealOrganisation = new DealOrganisation;
                $dealOrganisation->create([
                    'deal_id'               => $deal->id,
                    'organisation'          => $value[$dealsImport['columns'][0]],
                    'title'                 => $value[$dealsImport['columns'][2]],
                    'turnover'              => $value[$dealsImport['columns'][6]],
                    'currency_code'         => $value[$dealsImport['columns'][7]],
                    'expected_close_date'   => $value[$dealsImport['columns'][8]],
                    'deal_color'                   => $value[$dealsImport['defaultColumns'][2]],
                    'deal_color_update_datetime'   => $value[$dealsImport['defaultColumns'][3]]
                ]);

                $dealEmailStatus = new DealEmailStatus;
                $dealEmailStatus->create([
                    'deal_id'         => $deal->id,
                    'reminder_status' => config('constants.salespipeline.deal_email_status.zero')
                ]);

                $dealPerson = new DealPerson;

                $dealPerson->create([
                    'deal_id'           => $deal->id,
                    'first_name'        => $value[$dealsImport['columns'][1]],
                    'last_name'         => $value[$dealsImport['defaultColumns'][7]],
                    'phone'             => json_encode([[ 'value' => $value[$dealsImport['columns'][4]]]]),
                    'source_of_lead'    => $value[$dealsImport['columns'][5]],
                    'email'             => json_encode([['value' => $value[$dealsImport['columns'][3]]]]),
                ]);

                DealCategoriesData::create([
                    'deal_id'     => $deal->id,
                    'category_id' => $value[$dealsImport['columns'][9]],
                ]);

                foreach($deal_stages as $deal_key => $deal_val){
                    StageDetails::create([
                        'deal_id'       => $deal->id,
                        'stage_id'      => $deal_key ?? $value[$dealsImport['defaultColumns'][4]],                        
                        'start_datetime' => $value[$dealsImport['defaultColumns'][4]] == $deal_key ? $value[$dealsImport['defaultColumns'][5]] : null,
                    ]);
                }
            }            
        }

        $result['success'] = true;
        $result['message'] = 'Deals Imported Successfully';

        return $result;

    }

    public function dealsExport($onlyCurrentUser = true, $request){
        $deals = new Deals;
        $dealsExport = config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.export.deals');
        $query = $deals->join('deal_organisations', 'deals.id', '=', 'deal_organisations.deal_id')
                        ->join('users', 'users.id', '=', 'deals.owner_id')
                        ->leftjoin('deal_categories_data', 'deal_categories_data.deal_id', '=', 'deals.id')
                        ->leftjoin('deal_categories', 'deal_categories.id', '=', 'deal_categories_data.category_id')
                        ->leftjoin('deal_lost_reasons', 'deal_lost_reasons.deal_id', '=', 'deals.id')
                        ->leftjoin('deal_people', 'deal_people.deal_id', '=', 'deals.id');

        $query = ($onlyCurrentUser == true) ? $query->where('deals.owner_id','=',auth()->user()->id) : $query;
        $query = (isset($request['dealColor']) && $request['dealColor'] != config('constants.salespipeline.miscellaneous.dealColors.colors.greyColor')) ? $query->where('deal_organisations.deal_color','=',$request['dealColor']) : $query;
        $query = isset($request['status']) ? $query->whereIn('deals.status',$request['status']) : $query;
        // $query = $query->where('deals.pipeline_stage',1);
        // $query = (isset($request->data['dealOwner']) && $request->data['dealOwner'] != 0 && $onlyCurrentUser == false ) ? $query->where('users.id','=',auth()->user()->id) : $query;
        $deals = $query->get($dealsExport['get'])->each->setAppends($dealsExport['append']);
        $data['items'] = $this->mapCollectionInNeededFormat($deals);
        // $data['items'] = mapArray($data['items'],array_keys($dealsExport['headings']));

        if (count($data['items']) > 0) {
            $data['headings'] = array_values($dealsExport['headings']);
            $result['success'] = true;
            $result['export'] = new DealsExport($data);            
        }else{
            $result['success'] = false;
            $result['export'] = array();
        }

       return $result;
        
    }


    public function mapCollectionInNeededFormat($deals){
        $dealsExport = config('constants.salespipeline.miscellaneous.frontend.dealsListingPage.export.deals');
        
        $deals = $deals->map(function ($deal) use ($dealsExport)  {  
            
            $filterdArray = [];
            $multifieldsArray = [];
            
            foreach ($dealsExport['headings'] as $key => $heading) {
                    
                    if (in_array($key,$dealsExport['multipleFields']['fields'])) {
                        foreach ($dealsExport['multipleFields']['index'][$key] as $k => $v) {
                            try {
                                $multifieldsArray[$v] =  (isset($deal[$key][$k]) && !empty($deal[$key][$k]['value']) && isset($deal[$key][$k]['value'])) ? $deal[$key][$k]['value'] : null;
                          
                            } catch (\Exception $exception) {                                
                                $multifieldsArray[$v] = null;
                            }
                        }                      
                    }                      
                    $filterdArray[$key] = (in_array($key,array_keys($multifieldsArray))) ? $multifieldsArray[$key] : $deal[$key];

                    if (in_array($key,$dealsExport['dateTimeFields'])) {
                        $filterdArray[$key] =  Carbon::parse($deal[$key])->format(config('constants.salespipeline.miscellaneous.date.format.datetime.frontend'));
                    }
            }
            
            return $filterdArray;
        });
        
        return $deals->toArray();
    }

    public function validateTheRequest($request,$action,$requestId = false){

        $data = [];
        $data['success'] = false;    
        switch ($action) {
            case 'createDeal': // check if default is not N/A
                $deal_title = (isset($request['data']['title']) && $request['data']['title'] != config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_title') )  ? DealOrganisation::where('title',$request['data']['title'])->exists() : false;
                $data['success'] = ($deal_title == false) ? true : false;
                $data['status']  =  'error';
                $data['message'] = 'Deal Title already exists';
                break;
            case 'updateOrganisation':                
                $deal_title = (isset($request['data']['Title']) && $request['data']['Title'] != config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_organisations_title') ) ?  DealOrganisation::where('title',$request['data']['Title'])->where('deal_id','!=',$requestId)->exists() : false;
                $data['success'] = ($deal_title == false) ? true : false;
                $data['status']  =  'error';
                $data['message'] = 'Deal Title already exists';
                 break;
            default:
            $data['success'] = true;
            $data['status']  = 'success';
                break;
         }
         return $data;

    }


    public function dealCategoryUpdate($data){    // update deal category                       
        DealCategoriesData::where('deal_id',$data['deal_id'])->update([
            'category_id' => $data['dealCategory']['id'] ?? config('constants.salespipeline.miscellaneous.defaultValuesOfDeals.deal_categories_data_category_id'),
        ]);
        
    }

    public function filterNeededDeals($request){  
        //$request->data['dealOwner'] = 0 means every owner
        //$request->data['dealCategory'] = 0 means every category
        $all_data_array = $this->model->join('deal_organisations','deals.id', '=', 'deal_organisations.deal_id')
                                ->join('deal_categories_data','deals.id', '=', 'deal_categories_data.deal_id')
                                ->select('deals.id')->where(function($q) use ($request){
                                (isset($request->data['dealOwner']) && $request->data['dealOwner'] != config('constants.salespipeline.miscellaneous.every.dealOwner') ) ? $q->where('owner_id', $request->data['dealOwner']) : $q;
                                (isset($request->data['dealCategory']) && $request->data['dealCategory'] != config('constants.salespipeline.miscellaneous.every.dealCategory') ) ? $q->where('category_id', $request->data['dealCategory']) : $q;
                                isset($request->data['title']) ? $q->where('title','like', '%'.$request->data['title'].'%') : $q;
                                isset($request->data['status']) ? $q->whereIn('status', $request->data['status']) : $q;
                                (isset($request->data['dealColor']) && $request->data['dealColor'] != config('constants.salespipeline.miscellaneous.dealColors.colors.greyColor')) ? $q->where('deal_color', $request->data['dealColor']) : $q;
                            })->get(['id'])->toArray();

        return array_column($all_data_array, 'id');    
        
    }

    public function deaslMapData($ids){  // get deals ids and map data according to number of stages. Done this way as old developer did not leave any option)
        $all_deals = [];
        $dealStages = config('constants.salespipeline.deal_stages.stage');
        $paginationForComparison = [];
        foreach ($dealStages as $stageId => $stageName) {
            $stageId = (int) $stageId;
            $this->model = $this->model->where('pipeline_stage',$stageId)->first();
            $data = $this->model->setRelation('stageData', $this->model->stageData()->whereIn('id', $ids)->paginate(config('constants.salespipeline.pagination')));
            $stage_data = [];
            $stage_data['pipeline_stage'] = $stageId;                        
            $paginationForComparison = $paginationForComparison + $this->storePaginationForComparison($stageId,$data);         
            $stage_data['stage_data'] = $data->stageData->getCollection();
            $all_deals['data'][] = $stage_data; 
        }        
        $maxStageData = collect($paginationForComparison)->sortByDesc(['total'])->first(); // get pagination of that stage which has more deals
        $result['pagination'] = $this->getPaginationData($maxStageData);
        $result['all_deals'] = $all_deals;
        $result['ids'] = $ids;
        return $result;        
    }

    public function deaslListing($request){
        $ids = $this->filterNeededDeals($request);
        $all_deals = $this->deaslMapData($ids);
        return $all_deals;
    }

    public function storePaginationForComparison($stageId,$data){
        return [
            (int) $stageId => 
            [
                'total' => $data->stageData->total(),
                'stageData' => $data->stageData
            ]
        ];
    }

    public function getPaginationData($data){ // from relation data 
        return $result = [
            'from' => $data['stageData']->currentPage(),
            'to' => $data['stageData']->currentPage() + 1,
            'total' => $data['stageData']->total(),
            'data' => [['sample_data'=> true]], // keep this
            'next_page_url' => $data['stageData']->nextPageUrl(),
            'prev_page_url' =>  $data['stageData']->previousPageUrl(),
            'last_page' => $data['stageData']->lastPage(),
            'per_page' => $data['stageData']->perPage(),
            'current_page' => $data['stageData']->currentPage(),
            'path' => $data['stageData']->path(),
            'query' => [],
        ];
        
    }

    public function tranferDeals($request){
        $tranferDeals = null;
        if (isset($request->tranferDealsTo)) {
            if ($request->tranferDealsTo != $request->tranferFromUser['id']) {                
                $allDeals = array_column(Deals::where('owner_id',$request->tranferFromUser['id'])->get(['id'])->toArray(),'id');
                if (isset($allDeals) && count($allDeals) > 0) {
                    Deals::whereIn('id',$allDeals)->update(['owner_id' => $request->tranferDealsTo]);
                    $tranferDeals = $allDeals;
                }
            }           
        }
        return $tranferDeals;
    }     
    

}

// create empty project code
// $deal_organisation = $deal_organisation->where('deal_id',$request['deal_id'])->first();
// $data['name'] = (isset($deal_organisation->title)) ? $deal_organisation->title : null;
// $data['cost'] = (isset($deal_organisation->turnover)) ? $deal_organisation->turnover : null;
// $data['currency_code'] = (isset($deal_organisation->currency_code)) ? $deal_organisation->currency_code : null;
// $data['project_manager_id'] = Deals::where('id', $request['deal_id'])->first()->owner_id; // Set Deal Owner as project manager
// $data['deal_id'] = $request['deal_id'];
// $newProject = $repository->storeProject($data,true); 
// $repository->addDefaultMemberToProject($newProject->id,config('constants.permissions.projects.createProject.miscellaneous.defaultUser.id'));  // Add Default Owner of the project
// if ($data['project_manager_id'] != config('constants.permissions.projects.createProject.miscellaneous.defaultUser.id')) {
//     $repository->addDefaultMemberToProject($newProject->id,$data['project_manager_id']);
// }
// $category_id = DealCategoriesData::where('deal_id',$request['deal_id'])->first()['category_id'];
// $repository->createProjectCategory($newProject->id,$category_id);  /* Deal category ids are same as project category ids */
