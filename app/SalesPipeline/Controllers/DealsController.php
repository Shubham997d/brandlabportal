<?php
namespace App\SalesPipeline\Controllers;

use App\SalesPipeline\Models\SalesPipeline;
use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\DealNotes;
use App\SalesPipeline\Models\DealActivity;
use App\SalesPipeline\Models\StageDetails;
use App\SalesPipeline\Models\DealOrganisation;
use App\Base\Http\Controllers\Controller;
use App\SalesPipeline\Requests\DealsRequest;
use App\SalesPipeline\Repositories\DealsRepository;
use App\SalesPipeline\Repositories\StageDetailRepository;
use App\SalesPipeline\Models\DealInvoiceDefault;
use App\SalesPipeline\Models\DealEmailStatus;
use App\SalesPipeline\Models\DealCategories;
use App\Base\Models\User;
use Illuminate\Http\Request;
use DB;
use App\Project\Models\Project;
use PDF;
use Response;
use Carbon\Carbon;
use App\Base\Mail\followUpDeal;
use Illuminate\Support\Facades\Mail;
use App\Base\Mail\projectManagerMail;
use Spatie\DbDumper\Databases\MySql;
use Config;
use App\Exports\DealsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Notification;
use App\SalesPipeline\Notifications\DealColorUpdated;
use Illuminate\Notifications\Notifiable;
use App\SalesPipeline\Notifications\NotificationTest;
use App\Base\Notifications\DailyTaskUpdate;
use App\SalesPipeline\Models\DealCategoriesData;



class DealsController extends Controller
{
    
    public function deal_users(Request $request){
        try { 
             // Where user is not deleted and has role permission
            $users = User::whereIn('role_id',config('constants.salespipeline.miscellaneous.rolesAllowed.forSales'))->where('id','>',config('constants.miscellaneous.mainUserId'))->select('id', 'name','role_id','deleted', 'avatar', 'username')->get();
            return response()->json(['status'=> 'success','users'=> $users]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }

    }

    public function show_search_deals(Request $request, DealOrganisation $dealOrganisation)
    {    
        try { 
            $this->authorize('index', Deals::class); // sames as show deals permisson 
            $dealOrganisation = $dealOrganisation->join('deals','deals.id', '=', 'deal_organisations.deal_id');        
            $dealOrganisation = isset($request->data['status']) ? $dealOrganisation->whereIn('deals.status',$request->data['status']) : $dealOrganisation;
            $dealOrganisation = isset($request->data['title']) ? $dealOrganisation->where('deal_organisations.title', 'LIKE', "%".$request->data['title']."%") : $dealOrganisation;
            $all_org_detail = $dealOrganisation->orderBy('deal_organisations.created_at', 'ASC')->get(['deals.id','deal_organisations.title']);
            return response()->json(['status'=> 'success','all_org_detail'=> $all_org_detail]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show(Request $request, Deals $deals, DealsRepository $repository) // Get All Deals
    {   
        try { 
        $this->authorize('index', Deals::class);  
                            
        $result = $repository->deaslListing($request);
        $ids = $result['ids'];
        $DealOrganisation =   DealOrganisation::where(function($q) use ($request, $ids){
                                    $q->whereIn('deal_id', $ids);
                                })->get(['turnover']);
                         
      
        return response()->json(['status'=> 'success', 'all_steps_data' => $result['all_deals'],'pagination' => $result['pagination'], 'total_records' => count($result['ids']), 'sum_of_turnover' => $DealOrganisation->sum('turnover')]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(DealsRequest $request, DealsRepository $repository, StageDetailRepository $stageRepository)
    {
        try {          
            $wholeRequest = $request->all();            
            $this->authorize('createDeals', [Deals::class,$wholeRequest['data']['owner']['id']]); 
            $result = $repository->validateTheRequest($wholeRequest,'createDeal');
            if ($result['success'] === true) {
                $deal_stages = config('constants.salespipeline.deal_stages.stage');
                $deals = $repository->store($wholeRequest);
                $deal = $stageRepository->store($wholeRequest, $deals->id, $deal_stages);
                sendDynamicNotification(Deals::where('id',$deals->id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[0], 'visible_to_admin' => true, 'visible_to_user' => false ]);
                return $this->successResponse(
                    'misc.New Deal has been created',
                    'deals',
                    $deals,
                    201
                );                
            }else{
                return response()->json($result,400);
            }
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
    
    public function delete(Deals $deal, StageDetailRepository $stageRepository)
    {
        try { 
            $this->authorize('updateDeals', Deals::class);
            sendDynamicNotificationNow(Deals::where('id',$deal->id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[24], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            $stageRepository->delete($deal->id);
            $deal->delete();            
            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.The deal has been deleted'),
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function delete_note($note_id, DealNotes $dealNotes, DealsRequest $request)
    {
        try {        
            $this->authorize('updateDeals', Deals::class);
            $deal = Deals::where('id',$request->all()['deal_id'])->first();
            $dealNotes = $dealNotes->find($note_id);            
            $dealNotes->delete();            
            sendDynamicNotification($deal, [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[25], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.The deal note has been deleted'),
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

  

    public function organisation_update($deal_id, DealsRepository $repository, DealsRequest $request)
    {      
        try {
            $this->authorize('updateDeals', Deals::class);

            $result = $repository->validateTheRequest($request->all(),'updateOrganisation',$deal_id);

            if ($result['success'] === true) {
                $dealOrganisation = $repository->updateDealOrganisation($deal_id, $request->all());
                sendDynamicNotification($dealOrganisation, [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[9], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            }else{
                return response()->json($result,400);
            }

            return $this->successResponse(
                'misc.Deal Organisation has been updated.',
                'organisation',
                201
            );

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function transfer_owner_update($deal_id, DealsRepository $repository, DealsRequest $request)
    {

        try {
            $this->authorize('transferOwnership', [Deals::class,$request->owner]);
            $result = $repository->transferDealOwnership($deal_id, $request->all());
            if ($result['success'] === true) {
                // notification is in observer DealsObservor                 
                return $this->successResponse(
                    'misc.Deal Owner has been updated.',
                    'owner',
                    201
                );
            }else{
                return response()->json([
                    'status'   => 'error',
                    'message'   => 'New deal owner is same as old deal owner'
                ],400);
            }


        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function contact_person_update($deal_id, DealsRepository $repository, DealsRequest $request)
    {      
        try {
            $this->authorize('updateDeals', Deals::class);
            $repository->updateDealPerson($deal_id, $request->all());
            sendDynamicNotification(Deals::where('id',$deal_id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[10], 'visible_to_admin' => true, 'visible_to_user' => false ]);

            return $this->successResponse(
                'misc.Deal Person has been updated.',
                'organisation',
                201
            );

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function getDeal($deal_id, Deals $deal, DealsRequest $request)
    {    

        try {
            $this->authorize('show', Deals::class);
            $deal_status_list = config('constants.salespipeline.deal.status');

            $deal_data = $deal->with(['getStageData' => function($query) use ($deal_id){
                                $query->where('id', $deal_id);
                            },'getDealNotes','getDealInvoice'])->where('id', $deal_id)->first();

            if (isset($deal_data)) {
                $deal_data['status_list'] = $deal_status_list;
                   return response()->json([
                       'status'  => 'success',
                       'data' => $deal_data
                   ]);                
            }else{
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Deal not found'
                ],404);  
            }
                
            
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
       
    }

    public function dealStatusUpdate(DealsRepository $repository, DealsRequest $request){  
        // this should only be used for when deal status is changed to lose,open,paused and also when a project has already been created for the deal
        try { 
            $data = $request->all();
            $deal = Deals::where('id',$data['data']['deal_id'])->first();
            $this->authorize('updateDealStatus', [Deals::class,$deal]);
            $result = $repository->updateDealStatus($data);
            sendDynamicNotification($deal, [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[26], 'visible_to_admin' => true, 'visible_to_user' => false ]);   
            return response()->json([
                'status'  => 'success',
                'message'  => $result['message']
            ]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function getDealNotes($deal_id, DealNotes $dealnotes, DealsRequest $request)
    {       
        try {
            $deal_notes = $dealnotes->where('deal_id', $deal_id)->get();

            return response()->json([
                'status'  => 'success',
                'data' => $deal_notes
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function addDealNote(DealNotes $dealnotes, DealsRequest $request, DealsRepository $repository){
 
        try {
            $this->authorize('updateDeals', Deals::class);
            $data = $request->all();
            $dealNote = $repository->addDealNote($data);
            sendDynamicNotification(Deals::where('id',$data['data']['deal_id'])->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[7], 'visible_to_admin' => true, 'visible_to_user' => false ]);        

            return $this->successResponse(
                'misc.Deal note has been added.',
                'deals',
                $dealNote,
                201
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function updateDealNote(DealNotes $dealnotes, DealsRequest $request, DealsRepository $repository){
        try {
            $this->authorize('updateDeals', Deals::class);
            $data = $request->all();
            $dealNote = $repository->updateDealNote($data);
            sendDynamicNotification(Deals::where('id',$data['data']['deal_id'])->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[8], 'visible_to_admin' => true, 'visible_to_user' => false ]);

            return $this->successResponse(
                'misc.Deal note has been updated.',
                'deals',
                $dealNote,
                201
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function updateDealStage(DealsRequest $request, DealsRepository $repository)
    {
        try {
            $this->authorize('updateDeals', Deals::class);
            $data = $request->all();
            $deals = $repository->updateDealStage($request->all());
            sendDynamicNotification(Deals::where('id',$data['id'])->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[27], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            return $this->successResponse(
                'misc.Deal has been updated to '.config('constants.salespipeline.deal_stages.stage')[$request->stage_id],
                'deals',
                $deals,
                201
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }


    public function uploaddealfile($deal_id,DealsRequest $request, DealsRepository $repository)
    {
        try {
            $this->authorize('updateDeals', Deals::class);
            $data = $request->all();
            if(isset($data['fileData_new'])){
                $deals = $repository->uploaddealfiles($deal_id, $data);
                sendDynamicNotification(Deals::where('id',$deal_id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[11], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            }else{
                return response()->json([
                    'status'  => 'error',
                    'message'  => 'Please add new files'
                ],400);
            }

            return $this->successResponse(
                'misc.Deal Person has been updated.',
                'organisation',
                201
            );
            
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function addDealActivity($deal_id,DealsRequest $request, DealsRepository $repository)
    {
        try {
            $this->authorize('updateDeals', Deals::class);
            $deals = $repository->addDealActivity($deal_id, $request->all());
            sendDynamicNotification(Deals::where('id',$deal_id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[12], 'visible_to_admin' => true, 'visible_to_user' => false ]);

            if ($deals['status'] === true) {
                return $this->successResponse(
                    'misc.Deal Activity has been created.',
                    'organisation',
                    201
                );                
            }else{
                return response()->json([
                    'status'  => 'error',
                    'message'  => $deals['message']
                ],400);
            }

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function updateDealActivity($deal_id,DealsRequest $request, DealsRepository $repository)
    {
        try {
            $this->authorize('updateDeals', Deals::class);
            $deals = $repository->updateDealActivity($deal_id, $request->all());
            sendDynamicNotification(Deals::where('id',$deal_id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[13], 'visible_to_admin' => true, 'visible_to_user' => false ]); 

            if ($deals['status'] === true) {
                return $this->successResponse(
                    'misc.Deal Activity has been updated.',
                    'organisation',
                    201
                );
            }else{
                return response()->json([
                    'status'  => 'error',
                    'message'  => $deals['message']
                ],400);
            }
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function deleteDealActivity($deal_id,DealsRequest $request, DealsRepository $repository)
    {
        try {
            $this->authorize('updateDeals', Deals::class);
            $deals = $repository->deleteDealActivity($deal_id, $request->all());
            sendDynamicNotification(Deals::where('id',$deal_id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[14], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            return $this->successResponse(
                'misc.Deal Activity has been Deleted.',
                'organisation',
                201
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function delete_activity($activity_id, DealActivity $dealActivity, DealsRequest $request)
    {
        try {        
            $this->authorize('updateDeals', Deals::class);
            $dealActivity = $dealActivity->find($activity_id);
            $deal_id = $dealActivity->deal_id;
            $dealActivity->delete();
            sendDynamicNotification(Deals::where('id',$deal_id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[14], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.The deal activity has been deleted'),
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function uploaddealinvoice($deal_id,DealsRequest $request, DealsRepository $repository)
    {
        try {
            $deals = Deals::where('id', $deal_id)->first();        
            $this->authorize('updateInovice', [Deals::class,$deals]);
            $data = $request->all();
            $pdf_name = $repository->downloadDealInvoicePdf($deal_id, $data);
            $data['pdf_name']= $pdf_name;
            $deals = $repository->updateDealInvoice($deal_id, $data);
            sendDynamicNotification(Deals::where('id',$deal_id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[28], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            return Response::json(array(
                'status'  => 'success',
                'data'   => $pdf_name
            )); 
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function updateDealInvoiceDefault($deal_id,DealsRequest $request, DealsRepository $repository)
    {
        try {            
            $deals = Deals::where('id', $deal_id)->first();        
            $this->authorize('updateInovice', [Deals::class,$deals]);
            $deals = $repository->updateDealInvoiceDefault($deal_id, $request->all());
            $compactData = $request->all();
            sendDynamicNotification(Deals::where('id',$deal_id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[30], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            return Response::json(array(
                'status'  => $deals,
                'data' => 'Default fields has been updated.',
            )); 
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function dealExistsForProject($project_id, Request $request){
        try {
            
            $project = new Project;
            $projectExists = $project->where('id',$project_id )->first();
            $deal_id = false;            
            
            if (isset($projectExists)) {
                $deal_id = (isset($projectExists->deal_id)) ? $projectExists->deal_id : false;
            }
            
            return response()->json([
                'status'  => 'success',
                'deal_id' => $deal_id
            ]);  
            
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }

    }


    public function DealLostReason($deal_id,DealsRequest $request, DealsRepository $repository)
    {
        try {
            $this->authorize('updateDeals', Deals::class);
            $deals = $repository->updateDealLostReason($deal_id, $request->all());
            return $this->successResponse(
                'misc.Deal Lost Reason has been added.',
                'organisation',
                201
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }


    public function dealpdfinvoice()
    {
          $pdf = PDF::loadView('pdf/statement');
          return $pdf->stream('itsolutionstuff.pdf');
    
    }

    public function emailtrigger()
    {      
        /* auth()->user()->notify(new NotificationTest('working fine', auth()->user())); */
        //  $updatedDeals = DealOrganisation::join('deals','deals.id', '=', 'deal_organisations.deal_id')
        //                                 ->join('users', 'deals.owner_id', '=', 'users.id')
        //                                 ->get(['owner_id','deal_id','deal_color','deal_color_update_datetime','title'])->map(function ($dealOrganisation,$key)  {  
        // if($dealOrganisation->deal_color == config('constants.salespipeline.miscellaneous.dealColors.colors.greenColor')) {            
        //     $deal_color_update_datetime = (int) Carbon::parse($dealOrganisation->deal_color_update_datetime)->addDays(config('constants.salespipeline.miscellaneous.dealColors.changeDealColorNoOfDays.dealReturnToColor'))->timestamp;
        //     $current_date_time = (int) Carbon::now()->timestamp;
        //     if($current_date_time > $deal_color_update_datetime) {
        //             $dealOrganisation->deal_color = config('constants.salespipeline.miscellaneous.dealColors.colors.returnToColor'); // set default values 
        //             $dealOrganisation->deal_color_update_datetime = Carbon::now();                    
        //             $dealOrganisationItem = DealOrganisation::where('deal_id',$dealOrganisation->deal_id)->update([
        //                 'deal_color' => config('constants.salespipeline.miscellaneous.dealColors.colors.returnToColor'),
        //                 'deal_color_update_datetime' => Carbon::now()
        //             ]);
        //             return $dealOrganisation;                    
        //     }   
        // }
        // })->groupBy('owner_id'); 
        // unset($updatedDeals[null]);
        // foreach ($updatedDeals as $ownerId => $ownerDeals) {                
        //         $user = User::where('id',$ownerId)->first(); // notify user
        //         $user->notify(new DealColorUpdated($ownerDeals, $user));
        // }
        



        $stages =  DealOrganisation::join('deals','deals.id', '=', 'deal_organisations.deal_id')
        ->join('stage_details', 'deals.id', '=', 'stage_details.deal_id')
        ->join('users', 'deals.owner_id', '=', 'users.id')
        ->join('deal_email_statuses','deals.id', '=', 'deal_email_statuses.deal_id')
        ->where('users.active','=',true)
        ->where('users.deleted','=',false)
        ->where('deals.status','=',config('constants.salespipeline.deal.status')['Open'])
        // ->where('deal_email_statuses.reminder_status','=',config('constants.salespipeline.deal_email_status.zero'))
        ->where( 'stage_details.updated_at', '<', Carbon::now()->subDays(config('constants.emailTrigger.triggerDays')))
        ->get(['stage_details.deal_id','stage_details.id','users.id','users.username','users.email','deals.owner_id','deal_organisations.title','deal_organisations.organisation','deal_organisations.deal_color','deal_organisations.deal_color_update_datetime','deal_email_statuses.reminder_status']);
        // dd($stages);

        
        $stages = $stages->groupBy(['owner_id','deal_id'])->map(function ($owner,$owner_id) {
            $owner_email = User::where('id',$owner_id)->first()->email;
            $ownwer_deals = [];
            $ownwer_deals = $owner->map(function ($deal,$deal_id) use ($ownwer_deals) {
                if($deal->count() === count(config('constants.salespipeline.deal_stages.stage'))) {                          
                    return $deal->first();
                } 
            });

            if(isset($ownwer_deals) && count($ownwer_deals) > 0) {           
                $ownerDeals = [];
                $ownerDeals = $ownwer_deals->map(function ($deal,$deal_id) use ($ownerDeals)  { 
                            // Update Deal Email Mail Status to true if mail has been send
                            DealEmailStatus::updateOrCreate(
                                ['deal_id' => $deal_id],
                                ['reminder_status' => config('constants.salespipeline.deal_email_status.one')]
                            );

                            $deal->deal_color = config('constants.salespipeline.miscellaneous.dealColors.colors.dealStagnated'); // set default values 
                            $deal->deal_color_update_datetime = null;

                            // Update Deal Color to Red if deak has been stagnated same as email timer that is 14 days                            
                            DealOrganisation::where('deal_id',$deal_id)->first()->update([
                                    'deal_color' => config('constants.salespipeline.miscellaneous.dealColors.colors.dealStagnated'),
                                    'deal_color_update_datetime' => Carbon::now()
                            ]);
                            // $ownerDeals['email_links'] = '<a href="'.url('deal/' . $deal->deal_id).'">'.$deal->title.'</a>';    
                            // $ownerDeals['deal_titles'] = $deal->title;
                            return $deal;

                });                                 
                $user = User::where('id',$ownwer_deals->first()->id)->first(); // notify user
                $user->notify(new DealColorUpdated($ownerDeals, $user));                
                $deal_titles = implode(" , ", array_column($ownerDeals->toArray(),'title'));               
                $data['owner_details']['username'] = $ownwer_deals->first()->username;
                $data['deal_titles'] = $deal_titles;       
                // Mail::to($user->email)->send(new followUpDeal($data)); 
            }
        }); 

        dd('done');
        
       
    }


    public function dealColourUpdate(DealsRepository $repository, Request $request){
        try {
            $this->authorize('updateDeals', Deals::class);
            $data = $request->all();
            sendDynamicNotification(Deals::where('id',$data['deal_id'])->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[31], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            $result = $repository->dealColourUpdate($request->all());
            return response()->json([
                'status'  => 'success',
                'message'  => 'The deal color has been updated'
            ]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function uploadDeals(Request $request,DealsRepository $repository){
        try { 

            $this->authorize('updateDeals', Deals::class);
            $uploadDeals = $repository->uploadDeals($request);
            if($uploadDeals['success'] === true) {

                sendDynamicNotification(null, [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[3], 'visible_to_admin' => true, 'visible_to_user' => false ]);

                return response()->json([
                    'status'  => 'success',
                    'message'  => $uploadDeals['message']
                ]);
            }else{                
                return response()->json([
                    'status'  => 'error',
                    'message'  => $uploadDeals['message']
                ],500);
            }            
          
        } catch (Exception $exception) {
            $result['message'] = 'Please check your import file once, there are some errors in it';                       
            return response()->json([
                'status'  => 'error',
                'message'  => $result['message']
            ],500); 
           
        }

    }

    public function dealsExport(DealsRepository $repository, Request $request){
        try {
            $this->authorize('updateDeals', Deals::class);
            $result = $repository->dealsExport(true,$request->all());
            if ($result['success'] === true) {

                sendDynamicNotification(null, [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[2], 'visible_to_admin' => true, 'visible_to_user' => false ]);

                return Excel::download($result['export'], 'deals.xlsx',null, [\Maatwebsite\Excel\Excel::XLSX]);            
            }else{
                return response()->json([
                    'status'   => 'error',
                    'message'   => 'No Data Available'
                ],400);
            }
         
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
    

    public function getDealsFiltersData(DealsRepository $repository, Request $request){
        try {
            $this->authorize('index', Deals::class); // sames as show deals permisson
            $users = User::whereIn('role_id',config('constants.salespipeline.miscellaneous.rolesAllowed.forSales'))->where('id','>',config('constants.miscellaneous.mainUserId'))->select('id', 'name','role_id','deleted', 'avatar', 'username')->get();
            $dealCategories = DealCategories::all(['id','name','slug']);
            return response()->json(['status'=> 'success','users'=> $users,'categories' => $dealCategories ]);
         
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function dealCategoryUpdate(DealsRepository $repository, Request $request){
        try {
            $this->authorize('updateDeals', Deals::class);
            $data = $request->all();
            sendDynamicNotification(Deals::where('id',$data['deal_id'])->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[32], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            $result = $repository->dealCategoryUpdate($data);
            return response()->json([
                'status'  => 'success',
                'message'  => 'The deal category has been updated'
            ]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function dealCategoryUpdateDB(){
        try {
            $data = array_column(DealOrganisation::where('title','LIKE','%metatown%')->get('deal_id')->toArray(),'deal_id');

            DealCategoriesData::whereIn('deal_id', $data)->update(['category_id'=>2]);

            dd('done');
            

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function deleteMultipleDeals(Deals $deal, StageDetailRepository $stageRepository)
    {
        try { 
            $this->authorize('updateDeals', Deals::class);
            // sendDynamicNotificationNow(Deals::where('id',$deal->id)->first(), [ 'group_type' => config('constants.notifications.group_type')[0], 'action_slug' => config('constants.notifications.action_slug')[24], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            $stageRepository->deleteMultipleDeals();
            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.Deals has been deleted'),
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

}



// dd(file_get_contents($request->file('uploadDeals')));
// $csvAsArray = array_map('str_getcsv', file($request->file('uploadDeals')));
// dd($csvAsArray);