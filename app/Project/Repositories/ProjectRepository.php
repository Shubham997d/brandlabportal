<?php

namespace App\Project\Repositories;

use App\Project\Models\Project;
use App\Project\Models\ProjectCategories;
use App\Project\Models\ProjectCategoriesData;
use App\Project\Models\Asset;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\ProjectUser;
use App\Base\Models\User;
use App\Base\Models\UserRequest;
use Carbon\Carbon;
use App\Project\Models\ProjectCommissionUser;
use Illuminate\Mail\Mailable;
use App\Base\Mail\SendAdminUserRequest;
use Mail;
use App\Project\Repositories\AssetRepository;
use App\SalesPipeline\Repositories\DealsRepository;
use App\SalesPipeline\Models\DealCategoriesData;
use App\SalesPipeline\Models\Deals;
use App\TaskManager\Models\Task;



class ProjectRepository
{
    protected $model;
    public $authUser;

    public function __construct(Project $project)
    {
        $this->model = $project;
        $this->authUser = isset(auth()->user()->id) ? auth()->user() : User::where('email',explode(',', env('MAIL_ADDRESSES'))[0])->first();  // set the first user if auth is not set used for requests that are accepted/denied from mails

    }

    public function getAllProjects()
    {
        return $this->model->get(['name', 'project_manager_id']);
    }

    public function getLatestProjects($total)
    {
        return $this->model->orderBy('created_at', 'desc')->take($total)->get();
    }

    public function storeProject($data,$createEmpty = false)
    {
        return $this->model->create([
                    'name'         => $data['name'],
                    'project_manager_id'  => $data['project_manager_id'] ?? null,
                    'deadline'     => $data['deadline'] ?? null,
                    'cost'         => $data['cost'] ?? config('constants.salespipeline.miscellaneous.currency.default.price'),
                    'office_id'    => $data['office_id'] ?? null,
                    'team_id'      => $data['team_id'] ?? null,
                    'currency_code'     => $data['currency']['code'] ?? config('constants.salespipeline.miscellaneous.currency.default.code'),
                    'deal_id'      => $data['deal_id'] ?? null,
                    'status'       => config('constants.project.status.in_progress'), // Always Set Default To In Progress
                    'owner_id'     => $this->authUser->id,
                    'contact_term' => $data['contact_term'] ?? null,
                    'monthly_usage_fee' => $data['monthly_usage_fee'] ?? config('constants.salespipeline.miscellaneous.currency.default.price'),
                ]); 
    }

    public function updateProject($data)
    {           
        if (isset($data['commission_user_id'])) {
            $isUpdateRequest = ProjectCommissionUser::where('project_id',$data['id'])->exists();
            $this->storeOrUpdateCommissionUserForProject($data['id'],$data['commission_user_id'],$data['commission_user_value'],$isUpdateRequest); 
            $this->addDefaultMemberToProject($data['id'],$data['commission_user_id']);           
        }
        if (isset($data['project_manager_id'])) {
            $this->addUpdateProjectMember($data['id'],$data['project_manager_id']);            
        }
        if (isset($data['project_category']['id'])) {
            $this->createOrUpdateProjectCategory($data);            
        }
        return $this->model->where('id', $data['id'])->update([
                'name'         => $data['name'] ?? null,
                'project_manager_id'  => $data['project_manager_id'] ?? null,
                'deadline'     => $data['deadline'] ?? null,
                'contact_term' => $data['contact_term'] ?? null,
                'monthly_usage_fee' => $data['monthly_usage_fee'] ?? config('constants.salespipeline.miscellaneous.currency.default.price'),
                'cost'         => $data['cost'] ?? config('constants.salespipeline.miscellaneous.currency.default.price'),
                'office_id'    => $data['office_id'] ?? null,
                'team_id'      => $data['team_id'] ?? null,
                'currency_code'=> $data['currency']['code'] ?? config('constants.salespipeline.miscellaneous.currency.default.code'),
                'status'       => $data['status'] ?? config('constants.project.status.in_progress'), // Set Default To In Progress 
                'completed_at' => ($data['status'] == config('constants.project.status.completed')) ? Carbon::now()->format(config('constants.salespipeline.miscellaneous.datetime.format.database')) :  null, // Set Current Date/Time 
        ]);

    }

    public function getProjectMiscellaneous($data){
             $project = Project::where('id',$data['project_id'])->first(['currency_code','cost','contact_term']);
             $project_miscellaneous = array();
             $project_miscellaneous['contactTermLength'] = isset($project->contact_term) ? $project->contact_term : 'N/A';
             $project_miscellaneous['totalHours'] = $this->getProjectTotalHours($data);
             $project_miscellaneous['projectUsers'] = $this->getProjectUsers($data);
             $project_miscellaneous['daysSpent'] = $this->getDaysSpent($data);
             $project_miscellaneous['contractValue'] = ['data' => $project['cost'],'currency_code' => $project['currency_code']];
             $project_profit_details = $this->getProjectProfitDetails($data);

             if(isset($project_profit_details['profit_percentage'])){ // get data for loss or profit
                $project_miscellaneous['profitPercentage'] = $project_profit_details['profit_percentage'];
             }else{
                $project_miscellaneous['lossPercentage'] = $project_profit_details['loss_percentage'];
             }
             $project_miscellaneous['commissionPercentage'] = $project_profit_details['commission_percentage'];
             $project_miscellaneous['costPercentage'] = $project_profit_details['cost_percentage'];
             $project_miscellaneous['assetCostPercentage'] = $project_profit_details['asset_cost_percentage'];
             $project_miscellaneous['monthlyUsageFeePercentage'] = $project_profit_details['monthly_usage_fee_percentage'];
             $project_miscellaneous['cost']   = ['data' => $project_profit_details['cost'],'currency_code' => $project['currency_code']];
             $project_miscellaneous['profit'] = ['data' => $project_profit_details['profit'],'currency_code' => $project['currency_code']];
             $project_miscellaneous['commision'] = ['data' => $project_profit_details['user_commision_value'],'currency_code' => $project['currency_code']];
             $project_miscellaneous['totalCost'] = ['data' => $project_profit_details['total_cost'],'currency_code' => $project['currency_code']];
             $project_miscellaneous['assetCost'] = ['data' => $project_profit_details['asset_cost'],'currency_code' => $project['currency_code']];
             $project_miscellaneous['monthlyUsageFee'] = ['data' => $project_profit_details['monthly_usage_fee'],'currency_code' => $project['currency_code']];
             return $project_miscellaneous;             
             
    }

    public function getProjectTotalHours($data){
        $projectUsers = new ProjectUsersDuration;        
        $project_users = $projectUsers->where('project_id',$data['project_id'])->get();
        $collection = array();
        $total_hours = 0;
        foreach ($project_users as $key => $project_user) {
            $total_hours = $total_hours + intval(secondsToTime($project_user['duration'],true));
        }
        return $total_hours;
    }

    public function getProjectUsers($data){
         $projectUsers = new ProjectUser;
         $project_users = $projectUsers->where('project_id',$data['project_id'])->get();
         $count = $projectUsers->where('project_id',$data['project_id'])->count();         
         $project_users->put('usersCount', $count);
         return $project_users;
    }

    public function getDaysSpent($data){
        $project = new Project;
        $project_details = $project->where('id',$data['project_id'])->get(['created_at','completed_at']);
        $created_at = new \DateTime($project_details[0]->created_at);
        $created_at_timestamp = $created_at->getTimestamp();
        $completed_at = new \DateTime($project_details[0]->completed_at);
        $completed_at_timestamp = $completed_at->getTimestamp();
        $days_spent_timestamp = $completed_at_timestamp - $created_at_timestamp;
        $days_spent = secondsToWords($days_spent_timestamp,true);
        return $days_spent;
    }

    public function getProjectUsersDurationChart($data){

        try {
        
        $projectUsers = new ProjectUser;
        $project = new Project;
        $project_details = $project->where('id',$data['project_id'])->with('members')->first();
        $project_users = array();
        $project_users['users_ids'] = $projectUsers->where('project_id',$data['project_id'])->pluck('user_id')->toArray();        

        foreach ($project_users['users_ids'] as $user_id) {
            $user = new User;
            $user = $user->where('id',$user_id)->get(['username','colour']);            
            $project_users['user_names'][] = $user[0]->username;
            $projectUsersDuration = new ProjectUsersDuration;
            $project_user_duration = $projectUsersDuration->where('project_id',$data['project_id'])->where('user_id',$user_id)->pluck('duration')->toArray();

            $project_users['colour'][] = $user[0]->colour;

            if (count($project_user_duration) > 0) {
                    // Get In hours Only
                    $project_users['project_user_duration'][] = secondsToTime($project_user_duration[0],true);
            }else{
                    $project_users['project_user_duration'][] = 0;
            }
            
        }
        $chartData = array();    
        $datasets =  array();
        $datasets[0]['backgroundColor'] = $project_users['colour'];
        $datasets[0]['data'] = $project_users['project_user_duration'];
        $chartData['labels'] = $project_users['user_names'];            
        $chartData['datasets'] = $datasets;

        return $chartData;

        } catch (Exception $exception) {

            return $this->errorResponse($exception->getMessage());
        }
    }


    public function getProjectProfitDetails($data){
        try {        
            $project = new Project;
            $project_details = $project->where('id',$data['project_id'])->with('members')->with('durations')->get();
            $total_users_salary = 0;

        foreach ($project_details[0]->members as $member) {
            $user_duration = $this->getUserDuration($data['project_id'],$member['id'],true);            
            $user_salary = $this->getUserSalaryAccordingToDuration($user_duration,$member['salary'],true);            
            $total_users_salary = $user_salary + $total_users_salary;            
        }

        $project = $project->where('id',$data['project_id'])->first();
        $project_cost = $project->cost;
        // if (!isset($project_cost) || $project_cost == 0.0 || $project_cost == 0 ){ // return if project cost is 0
        //         $data['profit_percentage'] = 0;
        //         $data['cost_percentage']   = 0;
        //         $data['commission_percentage']   = 0;
        //         $data['profit']   = 0;
        //         $data['cost']   = 0;
        //         $data['asset_cost_percentage']   = 0;
        //         $data['asset_cost']   = 0;
        //         return $data;
        // }else{
        //     $data = $this->calculateProfitAndCost($total_users_salary,$project_cost,$data['project_id']);
        // }
        $data = $this->calculateProfitAndCost($total_users_salary,$project_cost,$data['project_id']);

        return $data;


        } catch (Exception $exception) {

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function getUserDuration($project_id,$user_id,$only_hours){
       
            $projectUsersDuration = new ProjectUsersDuration;
            $project_user_duration = $projectUsersDuration->where('project_id',$project_id)->where('user_id',$user_id)->first();
            if ($project_user_duration) {
                $user_duration = $project_user_duration->duration;
            }else{
                $user_duration = 0;
            }

            if ($only_hours) {
                $duration = secondsToTime($user_duration,true);            
            }
      
        return $duration;
    }

    public function getUserSalaryAccordingToDuration($duration,$user_salary,$in_hours){
        (empty($duration) || $duration == '' ) ? $duration = 0 : $duration;   
        if ($in_hours) {
            $total_salary = $user_salary * $duration;
        }
        return $total_salary;

    }

    public function calculateProfitAndCost($total_users_salary,$project_cost,$project_id) {            
            
            $data = array();
            $user_commision_value = 0;
            $assetRepository = new AssetRepository(new Asset);  
            $asset_cost = $assetRepository->getTotalAssetAmount($project_id); // get assets total
            $monthly_usage_fee = floatval(number_format((float)$this->calculateMonthlyUsageFee($project_id), 2, '.', ''));
            $projectCommissionUsers = ProjectCommissionUser::where('project_id',$project_id)->get(); // right now only for one user commission
            if (isset($projectCommissionUsers) && count($projectCommissionUsers) > 0) {  
                $user_commision_percentage =  $projectCommissionUsers[0]->commission_value;
                $user_commision_value = $user_commision_percentage/100 * $project_cost;
                $data['commission_percentage'] = floatval(number_format((float)$user_commision_percentage, 2, '.', ''));
                $profit = $project_cost - ($user_commision_value + $total_users_salary + $asset_cost + $monthly_usage_fee);
                $data['commission_percentage'] = floatval(number_format((float)$user_commision_percentage, 2, '.', ''));
                $data['profit'] = floatval(number_format((float)$profit, 2, '.', ''));
                $hasCommissionPercentage = true;
            }else{
                $profit = $project_cost - ($total_users_salary + $asset_cost + $monthly_usage_fee);
                $data['profit'] = floatval(number_format((float)$profit, 2, '.', ''));
                $hasCommissionPercentage = true;
            }  
            $data['user_commision_value'] = floatval(number_format((float)$user_commision_value, 2, '.', ''));            
            $asset_cost_percentage = ($asset_cost >= 0 && $project_cost > 0) ? $asset_cost/$project_cost * 100 : (($project_cost == 0 && $asset_cost == 0) ? 0 : 100);
            $data['asset_cost_percentage'] = floatval(number_format((float)$asset_cost_percentage, 2, '.', ''));
            $data['asset_cost'] = floatval(number_format((float)$asset_cost, 2, '.', ''));
            $data['commission_percentage'] = isset($data['commission_percentage']) ? $data['commission_percentage'] : 0; //default to 0
            $data['monthly_usage_fee'] = ($monthly_usage_fee > 0) ? floatval(number_format((float)$monthly_usage_fee, 2, '.', '')) : $monthly_usage_fee;
            $data['monthly_usage_fee_percentage'] = ($monthly_usage_fee >= 0 && $project_cost > 0) ? floatval(number_format((float)$monthly_usage_fee/$project_cost * 100, 2, '.', '')) : (($project_cost == 0 && $monthly_usage_fee == 0) ? 0 : 100);

            if ($profit > 0){
                $profit_percentage = $profit/$project_cost * 100;
                $data['profit_percentage'] = floatval(number_format((float)$profit_percentage, 2, '.', ''));
                if ($data['profit_percentage'] > 0) {
                    $cost_percentage = 100.00 - ($data['profit_percentage'] + $data['commission_percentage'] + $data['asset_cost_percentage'] + $data['monthly_usage_fee_percentage']);
                    $data['cost'] = floatval(number_format((float)$total_users_salary, 2, '.', ''));
                    $data['cost_percentage'] = floatval(number_format((float)$cost_percentage, 2, '.', ''));
                    $data['total_cost_percentage'] = $data['profit_percentage'] + $data['commission_percentage'];
                    $data['total_cost'] = $user_commision_value + $data['cost'] + $data['asset_cost'];
                    $data['total_cost'] = floatval(number_format((float)$data['total_cost'], 2, '.', ''));
                }
            }
            // for loss
            if ($profit <= 0){                             
                $loss_percentage = ($project_cost == 0) ? 0 : $profit/$project_cost * 100;                
                $data['loss_percentage'] = floatval(number_format((float)$loss_percentage, 2, '.', ''));
                if ($data['loss_percentage'] <= 0) {
                    $cost_percentage = ($data['asset_cost_percentage'] == 100) ? 100 : 100.00 - ($data['loss_percentage'] + $data['commission_percentage'] + $data['asset_cost_percentage'] + $data['monthly_usage_fee_percentage']);
                    $data['cost'] = floatval(number_format((float)$total_users_salary, 2, '.', ''));
                    $data['cost_percentage'] = floatval(number_format((float)$cost_percentage, 2, '.', ''));                    
                    $data['total_cost'] = $user_commision_value + $data['cost'] + $data['asset_cost'];
                    $data['total_cost'] = floatval(number_format((float)$data['total_cost'], 2, '.', ''));
                    $data['loss_percentage'] = ($data['loss_percentage'] == 0) ? 100 : $data['loss_percentage'];
                }
            }
            return $data;                

    }

    public function getProjectProfitAndCostChartData($data){

        $project_profit_details = $this->getProjectProfitDetails($data);                
        $chartData = array();    
        $datasets =  array();
        if(isset($project_profit_details['profit_percentage'])) {
            $datasets[0]['backgroundColor'] = [config('constants.project.project_profit_chart.colours.profit'),config('constants.project.project_profit_chart.colours.cost'),config('constants.project.project_profit_chart.colours.user_commission'),config('constants.project.project_profit_chart.colours.asset_cost'),config('constants.project.project_profit_chart.colours.monthly_usage')];
            $datasets[0]['data'] = [$project_profit_details['profit_percentage'],$project_profit_details['cost_percentage'],$project_profit_details['commission_percentage'],$project_profit_details['asset_cost_percentage'],$project_profit_details['monthly_usage_fee_percentage']];
            $chartData['labels'] = [config('constants.project.project_profit_chart.labels.profit'),config('constants.project.project_profit_chart.labels.cost'),config('constants.project.project_profit_chart.labels.user_commission'),config('constants.project.project_profit_chart.labels.asset_cost'),config('constants.project.project_profit_chart.labels.monthly_usage')];
        }else{
            $datasets[0]['backgroundColor'] = [config('constants.project.project_profit_chart.colours.loss'),config('constants.project.project_profit_chart.colours.cost'),config('constants.project.project_profit_chart.colours.user_commission'),config('constants.project.project_profit_chart.colours.asset_cost'),config('constants.project.project_profit_chart.colours.monthly_usage')];
            $datasets[0]['data'] = [$project_profit_details['loss_percentage'],$project_profit_details['cost_percentage'],$project_profit_details['commission_percentage'],$project_profit_details['asset_cost_percentage'],$project_profit_details['monthly_usage_fee_percentage']];
            $chartData['labels'] = [config('constants.project.project_profit_chart.labels.loss'),config('constants.project.project_profit_chart.labels.cost'),config('constants.project.project_profit_chart.labels.user_commission'),config('constants.project.project_profit_chart.labels.asset_cost'),config('constants.project.project_profit_chart.labels.monthly_usage')];
        }
        $chartData['datasets'] = $datasets;       

        return $chartData;
    }

    public function addDefaultMemberToProject($project_id,$user_id,$duration = 0){

        $projectUser = new ProjectUser;
        $projectUsersDuration = new ProjectUsersDuration;
        if ($projectUser->where('project_id',$project_id)->where('user_id',$user_id)->exists() == false) {
            $projectUser->insert(['project_id' => $project_id, 'user_id' => $user_id]);            
        }
              
        if ($projectUsersDuration->where('project_id',$project_id)->where('user_id',$user_id)->exists() == false) {
            $projectUsersDuration->insert(['project_id' => $project_id, 'user_id' => $user_id,'duration' => $duration]);
        }
        
    }

    public function storeOrUpdateCommissionUserForProject($project_id,$user_id,$commission_value,$update = false){

        if ($update == true) {
            ProjectCommissionUser::where('project_id',$project_id)->update(['user_id' => $user_id,'commission_value' => $commission_value]);
        }else{
            ProjectCommissionUser::insert(['project_id' => $project_id, 'user_id' => $user_id,'commission_value' => $commission_value]);     
        }
        return true;
    }

    public function addUpdateProjectMember($project_id,$user_id,$duration = 0,$forProjectManager = false){
        $projectUserExists = ProjectUser::where('project_id',$project_id)->where('user_id',$user_id)->exists();    
        if ($projectUserExists == false) {
            $projectUser = new ProjectUser;
            $projectUser->insert(['project_id' => $project_id, 'user_id' => $user_id]);
            $projectUsersDuration = new ProjectUsersDuration;        
            $projectUsersDuration->insert(['project_id' => $project_id, 'user_id' => $user_id,'duration' => $duration]);            
        }       
    }

    public function getProjectCategories(){        
        return ProjectCategories::get(['id','name','slug']);
    }

    public function createOrUpdateProjectCategory($data,$extraData = null){            
        if(ProjectCategoriesData::where('project_id',$data['id'])->exists()){
            ProjectCategoriesData::where('project_id',$data['id'])->update([
                'category_id' => $data['project_category']['id'] ?? config('constants.project.miscellaneous.defaultValues.category')
            ]);
        }
        
    }

    public function createProjectCategory($id,$category_id = null){            
        return ProjectCategoriesData::create([
                'project_id' => $id,
                'category_id' => $category_id ?? config('constants.project.miscellaneous.defaultValues.category'),
            ]);
        
    }

    public function calculateMonthlyUsageFee($project_id){                    
        $monthly_usage_fee = 0;
        $project = Project::where('id',$project_id)->first();
        if(isset($project->monthly_usage_fee) && $project->monthly_usage_fee > config('constants.salespipeline.miscellaneous.currency.default.price')) {
            $monthly_usage_fee = $project->monthly_usage_fee * $project->contact_term;
        }
        return $monthly_usage_fee;
    }

    public function getProjectIdsOfParticularCategories($request){
         $query = Project::join('project_user', 'projects.id', '=', 'project_user.project_id')
                  ->join('project_categories_data', 'projects.id', '=', 'project_categories_data.project_id')
                  ->join('project_categories', 'project_categories_data.category_id', '=', 'project_categories.id');
         $query = (request()->category == 'all-projects') ? $query :  $query->where('project_categories.slug', request()->category);
         $projects = $query->get(['projects.id'])->toArray();
          
        return array_column($projects,'id');
    }


    public function createProjectAndResources($data){
            $result = $this->checkIfUserCanCreateProjectOfDeal($data['deal_id'] ?? null);
            if ($result['success'] == true) {
                   
                    $project = $this->storeProject($data);
                    $project->members()->save($this->authUser);           
                    
                    ProjectUsersDuration::insert(['project_id' => $project->id, 'user_id' => $this->authUser->id,'duration' => 0]);   //Add Duration Of Auth User
        
                    if (isset($data['commission_user_id'])) {
                        $this->storeOrUpdateCommissionUserForProject($project->id,$data['commission_user_id'],$data['commission_user_value']);
                        $this->addDefaultMemberToProject($project->id,$data['commission_user_id']);
                    }
        
                    if (isset($data['project_manager_id'])) { // Create User For Project Manager
                        $this->addDefaultMemberToProject($project->id,$data['project_manager_id']);
                    }
                                
                    $this->createProjectCategory($project->id,$data['project_category']['id'] ?? null); // create project category          
                   
                    $result['project'] = $project;
            }else{
                    $result['project'] = null;
            }

            return $result;

   }



   public function checkIfUserCanCreateProjectOfDeal($deal_id){
       if (isset($deal_id)) {                    
            $project = Project::where('deal_id',$deal_id)->first();

            if(isset($project)){ // check to make it more secure
                $deal_status = Deals::where('id', $project->deal_id)->first()['status'];
                    if ($deal_status == config('constants.salespipeline.deal.status')['Won']) {                   
                        $result['success'] = false;
                        $result['message'] = 'Deal status is already set to won and project ('.$project->name.') already exists for this deal';
                    }
            }else{ 
                    $result['success'] = true;
                    $dealsRepository = new DealsRepository(new Deals);                    
                    $dealsRepository->updateDealStatus([ 'data' => [ 'deal_id' => $deal_id, 'status' => config('constants.salespipeline.deal.status')['Won'] ] ]);
                    $result['message'] = 'Project created and deal status updated';                    
            }
       }else{           
            $result['success'] = true;
            $result['message'] = 'New project has been created';
       }    
       return $result;
   }

   public function deleteProjectAndResources($project_id){ // delete project and its resources
        if (isset($project_id)) {
                Project::where('id',$project_id)->delete();
                ProjectUsersDuration::where('project_id',$project_id)->delete();
                Task::where(['taskable_type' => 'project','taskable_id' => $project_id])->delete();
                ProjectCategoriesData::where('project_id',$project_id)->delete();
                ProjectCommissionUser::where('project_id',$project_id)->delete(); 
                ProjectUser::where('project_id',$project_id)->delete();
                return true;
            }
            return false;
    }


    public function deleteUserFromProject($project_id,$user_id){
        if (isset($project_id) && isset($user_id)) {
                ProjectUsersDuration::where('project_id',$project_id)->where('user_id',$user_id)->delete();
                Task::where(['taskable_type' => 'project','taskable_id' => $project_id,'assigned_to' => $user_id])->delete();
                ProjectCommissionUser::where('project_id',$project_id)->where('user_id',$user_id)->delete(); 
                ProjectUsersDuration::where('project_id',$project_id)->where('user_id',$user_id)->delete();
                return true;
        }
        return false;
    }


    public function tranferProjectManagers($request){     
          $tranferProjectManagers = false;  
            if (isset($request->tranferProjectManagerTo) && count($request->tranferProjectManagerTo) > 0) {

                foreach ($request->tranferProjectManagerTo as $key => $project) {                    
                            if ($request->tranferFromUser['id'] != $project['project_manager_id'] ) {
                                Project::where('id',$project['project_id'])->update(['project_manager_id' => $project['project_manager_id'] ]);
                                $tranferProjectManagers = true;
                            }
                    
                }
            }
            return $tranferProjectManagers;
    }

}
