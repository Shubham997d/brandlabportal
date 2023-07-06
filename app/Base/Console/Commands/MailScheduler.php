<?php

namespace App\Base\Console\Commands;
use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\StageDetails;
use Illuminate\Console\Command;
use App\Base\Mail\followUpDeal;
use App\SalesPipeline\Models\DealEmailStatus;
use Illuminate\Support\Facades\Mail;
use App\SalesPipeline\Models\DealOrganisation;
use App\Base\Models\User;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\SalesPipeline\Notifications\DealColorUpdated;
use Illuminate\Notifications\Notifiable;

class MailScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deal:stagnation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Daily email to all users with a word and its meaning';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Update Deal color to amber if deal color is green for more than 7 days

         $updatedDeals = DealOrganisation::join('deals','deals.id', '=', 'deal_organisations.deal_id')
                                        ->join('users', 'deals.owner_id', '=', 'users.id')
                                        ->get(['owner_id','deal_id','deal_color','deal_color_update_datetime','title'])->map(function ($dealOrganisation,$key)  {  
        if($dealOrganisation->deal_color == config('constants.salespipeline.miscellaneous.dealColors.colors.greenColor')) {            
            $deal_color_update_datetime = (int) Carbon::parse($dealOrganisation->deal_color_update_datetime)->addDays(config('constants.salespipeline.miscellaneous.dealColors.changeDealColorNoOfDays.dealReturnToColor'))->timestamp;
            $current_date_time = (int) Carbon::now()->timestamp;
            if($current_date_time > $deal_color_update_datetime) {
                    $dealOrganisation->deal_color = config('constants.salespipeline.miscellaneous.dealColors.colors.returnToColor'); // set default values 
                    $dealOrganisation->deal_color_update_datetime = Carbon::now();                    
                    $dealOrganisationItem = DealOrganisation::where('deal_id',$dealOrganisation->deal_id)->update([
                        'deal_color' => config('constants.salespipeline.miscellaneous.dealColors.colors.returnToColor'),
                        'deal_color_update_datetime' => Carbon::now()
                    ]);
                    return $dealOrganisation;                    
            }   
        }
        })->groupBy('owner_id'); 
        unset($updatedDeals[null]);
        foreach ($updatedDeals as $ownerId => $ownerDeals) {                
                $user = User::where('id',$ownerId)->first(); // notify user
                $user->notify(new DealColorUpdated($ownerDeals, $user));
        }
        



        $stages =  DealOrganisation::join('deals','deals.id', '=', 'deal_organisations.deal_id')
        ->join('stage_details', 'deals.id', '=', 'stage_details.deal_id')
        ->join('users', 'deals.owner_id', '=', 'users.id')
        ->join('deal_email_statuses','deals.id', '=', 'deal_email_statuses.deal_id')
        ->where('users.active','=',true)
        ->where('users.deleted','=',false)
        ->where('deals.status','=',config('constants.salespipeline.deal.status')['Open'])
        ->where('deal_email_statuses.reminder_status','=',config('constants.salespipeline.deal_email_status.zero'))
        ->where( 'stage_details.updated_at', '<', Carbon::now()->subDays(config('constants.emailTrigger.triggerDays')))
        ->get(['stage_details.deal_id','stage_details.id','users.id','users.username','users.email','deals.owner_id','deal_organisations.title','deal_organisations.organisation','deal_organisations.deal_color','deal_organisations.deal_color_update_datetime','deal_email_statuses.reminder_status']);
        
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
                Mail::to($user->email)->send(new followUpDeal($data)); 
            }
        }); 
      
        // \Log::info("cron is finish");
    }
}
