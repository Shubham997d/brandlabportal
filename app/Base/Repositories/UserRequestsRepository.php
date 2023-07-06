<?php

namespace App\Base\Repositories;

use App\Project\Models\Project;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\ProjectUser;
use App\Base\Models\User;
use App\Base\Models\UserRequest;
use Carbon\Carbon;
use App\Project\Models\ProjectCommissionUser;
use Illuminate\Mail\Mailable;
use App\Base\Notifications\SendAdminUserRequest;
use App\Base\Notifications\RequestAcceptOrDenied;
use Mail;
use App\Project\Repositories\ProjectRepository;
use App\SalesPipeline\Models\Deals;
use Illuminate\Support\Facades\Notification;



class UserRequestsRepository
{

    protected $resource, $request_type;   

    public function setResourceAndRequest($resource, $request_type){
        $this->resource = $resource;
        $this->request_type = $request_type;
    }

    public function handleUserRequest($resource, $request_type, $data){        
        $this->setResourceAndRequest($resource, $request_type);
        $result = $this->getRequestDataAccordingToRequestType($data);                
        if ($result['success'] == true) {
                $this->sendNotificationToAdmin($result);
        }
        return $result;
    }

    public function checkIfUserCanJoinProject($data){
        $result = array();
        $result['success'] = false;
        $result['actionSlug'] = config('constants.notifications.action_slug')[18];
        $userRequest = UserRequest::where('user_id',auth()->user()->id)->where('request_type',$this->request_type)->where('resource',$this->resource)->where('resource_id',$data['resource_id'])->first();
        $projectUserExists = ProjectUser::where('project_id',$data['resource_id'])->where('user_id',auth()->user()->id)->exists();
        if ($projectUserExists == true) {
            $result['message'] = config('constants.project.requests.messages.toUser.error.userAlreadyInProject');
            return $result;
        }
        
        if (isset($userRequest)) {             
            if ($userRequest->request_approval === config('constants.project.requests.request_approval_status')['denied']) {
                $data['request_approval'] = config('constants.project.requests.request_approval_status')['pending'];
                $result['mailData'] = $this->updateUserRequest($data,$userRequest);
                $result['success'] = true;
            }else{
                $result['success'] = false;
                $result['request_approval'] = array_search ($userRequest->request_approval, config('constants.project.requests.request_approval_status'));
            }
        }else{
            $data['request_approval'] = config('constants.project.requests.request_approval_status')['pending'];
            $result['mailData'] = $this->storeUserRequest($data);            
            $result['success'] = true;
        }         
        if ($result['success'] === true) {
            $project =  Project::where('id',$result['mailData']['resource_id'])->first();
            $result['message'] = config('constants.project.requests.messages.toUser.success.sentForApproval');
            $result['mailData']['subject'] = config('constants.project.requests.email.subjects.project.toAdmin')['join-project'];
            $result['mailData']['middle_content'] = auth()->user()->username.' has requested to join the project <b>' .$project->name.'</b>';
            $result['mailData']['acceptUrl'] = config('app.url').'/data/request/'.$result['mailData']['id'].'/accept/'.$result['mailData']['token'];
            $result['mailData']['denyUrl'] = config('app.url').'/data/request/'.$result['mailData']['id'].'/deny/'.$result['mailData']['token'];
            $result['userRequest'] = $result['mailData'];       
            $result['project'] = $project;            
        }else{
            $result['message'] = config('constants.project.requests.messages.toUser.error.requestPendingToJoinProject');
        }

        return $result;
        
    }


    public function storeUserRequest($data)
    {
         return UserRequest::create([
            'user_id'           => auth()->user()->id,
            'resource'          => $this->resource,
            'resource_id'       => $data['resource_id'] ?? null,
            'request_data'      => $data['request_data'] ?? null,
            'token'             => $data['token'],
            'request_type'      => $this->request_type,
            'request_approval'  => $data['request_approval']           
        ])->toArray();
        
    }

    public function updateUserRequest($data,$userRequest)
    {        
        UserRequest::where('id',$userRequest->id)->update([
            'request_approval'  => $data['request_approval']  
        ]);
        return UserRequest::where('id',$userRequest->id)->first()->toArray();
        
    }

    public function updateUserDataForRequest($data){
        $userRequest = UserRequest::where('id',$data['request_id'])->first();
        
        if (isset($userRequest) && $userRequest->token === $data['token'] ) {                        
                $this->setResourceAndRequest($userRequest->resource, $userRequest->request_type);
                $result = $this->updateUserDataAccordingToRequest($data,$userRequest);
        }else{
                $result['success'] = false;
                $token = isset($userRequest->token) ? $userRequest->token : null;
                if (isset($token)) {
                    if ($token === $data['token']) {
                        $result['message']['heading'] = config('constants.project.requests.messages.error.other')['heading'];                    
                    }else{
                        $result['message']['heading'] = config('constants.project.requests.messages.error.tokenNotMatched')['heading'];
                    }                    
                }else{
                    $result['message']['heading'] = config('constants.project.requests.messages.error.other')['heading'];
                }
        }
        return $result;
    }

    public function updateUserDataAccordingToRequest($data,$userRequest){     
            $result = [];
            
            if ($data['status'] == 'accept' && config('constants.project.requests.request_approval_status')['approved'] != $userRequest->request_approval) {
                if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][0] == $userRequest->request_type ) {
                        $result = $this->acceptUserJoinProjectRequest($data,$userRequest, new ProjectRepository(new Project));                
                }
                if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][1] == $userRequest->request_type ) {                        
                        $result = $this->acceptUserProjectCreateRequest($data,$userRequest, new ProjectRepository(new Project));
                }
                if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][2] == $userRequest->request_type ) {
                        $result = $this->acceptUserProjectCreateRequestOfDeal($data,$userRequest, new ProjectRepository(new Project));
                }
            }else{
                if ($data['status'] == 'accept') {
                    $result['success'] = false;
                    $result['message']['heading'] = config('constants.project.requests.messages.error.alreadyAccepted')['heading'];
                }
            }
            if ($data['status'] == 'deny' && config('constants.project.requests.request_approval_status')['denied'] != $userRequest->request_approval) {
                if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][0] == $userRequest->request_type ) {
                        $result = $this->rejectUserJoinProjectRequest($data,$userRequest, new ProjectRepository(new Project));      
                }
                if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][1] == $userRequest->request_type ) {
                        $result = $this->rejectUserProjectCreateRequest($data,$userRequest, new ProjectRepository(new Project));                    
                }
                if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][2] == $userRequest->request_type ) {
                        $result = $this->rejectUserProjectCreateRequestOfDeal($data,$userRequest, new ProjectRepository(new Project));
                }
            }else{
                if ($data['status'] == 'deny') {
                    $result['success'] = false;
                    $result['message']['heading'] = config('constants.project.requests.messages.error.alreadyDenied')['heading'];
                }
            }
            return $result;
    }

    public function acceptUserJoinProjectRequest($data,$userRequest, ProjectRepository $repository){          
           $result = $this->sendMailToUserAccordingToRequestApproval($data,$userRequest);
           if ($result['success'] == true) {
               $repository->addDefaultMemberToProject($userRequest->resource_id,$userRequest->user_id);
               UserRequest::where('id',$userRequest->id)->update([ 'request_approval'  => config('constants.project.requests.request_approval_status')['approved'] ]);               
           }
            return $result;  
          
    }

    public function acceptUserProjectCreateRequest($data,$userRequest, ProjectRepository $repository){          
        $result = $this->sendMailToUserAccordingToRequestApproval($data,$userRequest);
        if ($result['success'] == true) {
            
            $data = $repository->createProjectAndResources(['name' => $userRequest->request_data['name']]);            
            $repository->addDefaultMemberToProject($data['project']->id,$userRequest->user_id);
            UserRequest::where('id',$userRequest->id)->update([ 'resource_id'  => $data['project']->id, 'request_approval'  => config('constants.project.requests.request_approval_status')['approved'] ]);
         }
         return $result;
       
    }

    public function acceptUserProjectCreateRequestOfDeal($data,$userRequest, ProjectRepository $repository){          
        $result = $this->sendMailToUserAccordingToRequestApproval($data,$userRequest);
        if ($result['success'] == true) {            
            $data = $repository->createProjectAndResources($userRequest->request_data);
            UserRequest::where('id',$userRequest->id)->update([ 'resource_id'  => $data['project']->id, 'request_approval'  => config('constants.project.requests.request_approval_status')['approved'] ]);
        }
         return $result; 
       
    }    

    public function rejectUserJoinProjectRequest($data,$userRequest, ProjectRepository $repository){    
        
        $result = $this->sendMailToUserAccordingToRequestApproval($data,$userRequest);
        if ($result['success'] == true) {            
            $repository->deleteUserFromProject($userRequest->resource_id,$userRequest->user_id);
            UserRequest::where('id',$userRequest->id)->update([ 'request_approval'  => config('constants.project.requests.request_approval_status')['denied'] ]);
        }
        return $result;
    }
    
    public function rejectUserProjectCreateRequest($data,$userRequest,ProjectRepository $repository){    
        
        $result = $this->sendMailToUserAccordingToRequestApproval($data,$userRequest);
        if ($result['success'] == true) {     
            $repository->deleteProjectAndResources($userRequest->resource_id);
            UserRequest::where('id',$userRequest->id)->update([ 'request_approval'  => config('constants.project.requests.request_approval_status')['denied'] ]);
        }
        return $result;
    }

    public function rejectUserProjectCreateRequestOfDeal($data,$userRequest,ProjectRepository $repository){    
        
        $result = $this->sendMailToUserAccordingToRequestApproval($data,$userRequest);
        if ($result['success'] == true) {             
            $repository->deleteProjectAndResources($userRequest->resource_id);           
            UserRequest::where('id',$userRequest->id)->update([ 'request_approval'  => config('constants.project.requests.request_approval_status')['denied'] ]);
        }
        return $result;
    }


    public function sendMailToUserAccordingToRequestApproval($data,$userRequest){
        $result = [];
        $user = User::where('id',$userRequest->user_id)->first();
        $project = Project::where('id',$userRequest->resource_id)->first();        
        
        if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][1] == $userRequest->request_type ) { 
            $project = true;
        }   
        if (isset($user)) {
                $result['mailData']['subject'] = config('constants.project.requests.email.subjects')[$this->resource]['toUser'][$data['status']][$this->request_type]; 
                $result['mailData']['username'] = $user->username;
                $result['message']['heading'] = config('constants.project.requests.messages')[$data['status']]['heading'];
                $result['message']['content'] = config('constants.project.requests.messages')[$data['status']]['content'];
            
                if ($data['status'] == 'accept') {  
                    
                    if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][0] == $userRequest->request_type ) { // for join project request            
                            $result['mailData']['middle_content'] = 'You request to join project <b>' .$project->name. '</b> has been accepted';                    
                            $actionSlug = config('constants.notifications.action_slug')[19];
                    }
                    if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][1] == $userRequest->request_type ) { // for create project request            
                            $result['mailData']['middle_content'] = 'You request to create a new project <b>' .$userRequest->request_data['name']. '</b> has been accepted';
                            $actionSlug = config('constants.notifications.action_slug')[16];
                    }
                    if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][2] == $userRequest->request_type ) { // for create project request of deal
                            $result['mailData']['middle_content'] = 'You request to create a new project <b>' .$userRequest->request_data['name']. '</b> of deal (<a href="'.config('app.url').'/deal/'.$userRequest->request_data['deal']['id'].'" target="_blank"><strong>'.$userRequest->request_data['deal']['deal_name'].'</strong></a>) has been accepted';
                            $actionSlug = config('constants.notifications.action_slug')[22];
                    }
                }
                if ($data['status'] == 'deny') { 
                    if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][0] == $userRequest->request_type ) { // for join project request            
                            $result['mailData']['middle_content'] = 'You request to join project <b>' .$project->name. '</b> has been denied';
                            $actionSlug = config('constants.notifications.action_slug')[20];
                    }
                    if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][1] == $userRequest->request_type ) { // for create project request            
                            $result['mailData']['middle_content'] = 'You request to create a new project <b>' .$userRequest->request_data['name']. '</b> has been denied';
                            $actionSlug = config('constants.notifications.action_slug')[17];
                    }
                    if (config('constants.project.requests.resource')[0]  == $userRequest->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][2] == $userRequest->request_type ) { // for create project request of deal                    
                            $result['mailData']['middle_content'] = 'You request to create a new project <b>' .$userRequest->request_data['name']. '</b> of deal (<a href="'.config('app.url').'/deal/'.$userRequest->request_data['deal']['id'].'" target="_blank"><strong>'.$userRequest->request_data['deal']['deal_name'].'</strong></a>) has been denied';
                            $actionSlug = config('constants.notifications.action_slug')[23];
                    }
                }      
                $user->notify(new RequestAcceptOrDenied($user, ['actionSlug' => $actionSlug ,'mailData' => $result['mailData']],['project' => $project, 'userRequest'=> $userRequest]));
                $result['success'] = true;
                
        }else{
            $result['success'] = false;
            $result['message']['heading'] = config('constants.project.requests.messages.error.other')['heading'];
        }
        return $result;
    }


    public function getRequestDataAccordingToRequestType($data){
            if (config('constants.project.requests.resource')[0]  == $this->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][0] == $this->request_type ) {
                        $result = $this->checkIfUserCanJoinProject($data);        
            }
            if (config('constants.project.requests.resource')[0]  == $this->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][1] == $this->request_type ) {                        
                        $result = $this->checkIfUserCanCreateProject($data);        
            }
            if (config('constants.project.requests.resource')[0]  == $this->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][2] == $this->request_type ) {                        
                        $result = $this->checkIfUserCanCreateProjectForDeal($data);        
            }
            return $result;
    }

    
    public function checkIfUserCanCreateProject($data){
        $result = array();
        $result['success'] = false; 
        $result['actionSlug'] = config('constants.notifications.action_slug')[15];   
        $userRequest = UserRequest::where('user_id',auth()->user()->id)->where('request_type',$this->request_type)->where('resource',$this->resource)->where('request_data->name',$data['request_data']['name'])->first();
        $projectExists = Project::where('name',$data['request_data']['name'])->exists();
        if ($projectExists == true) {
            $result['message'] = config('constants.project.requests.messages.toUser.error.projectAlreadyExists');
            return $result;
        }        
        if (isset($userRequest)) {    
            if ($userRequest->request_approval === config('constants.project.requests.request_approval_status')['denied'] || ($projectExists == false && $userRequest->request_approval == config('constants.project.requests.request_approval_status')['approved']) ) {
                $data['request_approval'] = config('constants.project.requests.request_approval_status')['pending'];
                $result['mailData'] = $this->updateUserRequest($data,$userRequest);
                $result['success'] = true;
            }else{
                $result['success'] = false;
                $result['request_approval'] = array_search ($userRequest->request_approval, config('constants.project.requests.request_approval_status'));
            }
        }else{
            $data['request_approval'] = config('constants.project.requests.request_approval_status')['pending'];
            $result['mailData'] = $this->storeUserRequest($data);            
            $result['success'] = true;
        }        
        
        if ($result['success'] === true) {
            $result['message'] = config('constants.project.requests.messages.toUser.success.sentForApproval');
            $result['mailData']['subject'] = config('constants.project.requests.email.subjects.project.toAdmin')['create-project'];
            $result['mailData']['middle_content'] = auth()->user()->username.' has requested to create a new project called <b>' .$data['request_data']['name'].'</b>';
            $result['mailData']['acceptUrl'] = config('app.url').'/data/request/'.$result['mailData']['id'].'/accept/'.$result['mailData']['token'];
            $result['mailData']['denyUrl'] = config('app.url').'/data/request/'.$result['mailData']['id'].'/deny/'.$result['mailData']['token'];
            $result['project'] = $project ?? null;
            $result['userRequest'] = $result['mailData'];
        }else{
            $result['message'] = config('constants.project.requests.messages.toUser.error.requestPendingToCreateProject');
        }
        return $result;
        
    }


    public function checkIfUserCanCreateProjectForDeal($data){
        $result = array();
        $result['success'] = false; 
        $result['actionSlug'] = config('constants.notifications.action_slug')[21];
        $userRequest = UserRequest::where('user_id',auth()->user()->id)->where('request_type',$this->request_type)->where('resource',$this->resource)->where('request_data->deal_id',$data['request_data']['deal_id'])->first();
        $deal = Deals::where('id',$data['request_data']['deal_id'])->first();
        $deal_name = $deal->deal_name;
        $data['request_data']['deal'] = $deal->toArray() + ['deal_name' => $deal->deal_name];        
        $projectExists = Project::where('name',$data['request_data']['name'])->exists();
        if ($projectExists == true) {
            $result['message'] = config('constants.project.requests.messages.toUser.error.projectAlreadyExists');
            return $result;
        }        
        if (isset($userRequest)) {  
            if ($userRequest->request_approval === config('constants.project.requests.request_approval_status')['denied'] || ($projectExists == false && $userRequest->request_approval == config('constants.project.requests.request_approval_status')['approved']) ) {
                $data['request_approval'] = config('constants.project.requests.request_approval_status')['pending'];
                $result['mailData'] = $this->updateUserRequest($data,$userRequest);
                $result['success'] = true;
            }else{
                $result['success'] = false;
                $result['request_approval'] = array_search ($userRequest->request_approval, config('constants.project.requests.request_approval_status'));
            }
        }else{
            $data['request_approval'] = config('constants.project.requests.request_approval_status')['pending'];
            $result['mailData'] = $this->storeUserRequest($data);            
            $result['success'] = true;
        }                
        
        if ($result['success'] === true) {
            $q = "'";
            $result['message'] = config('constants.project.requests.messages.toUser.success.sentForApproval');
            $result['mailData']['subject'] = config('constants.project.requests.email.subjects.project.toAdmin')['create-project-of-deal'];
            $result['mailData']['middle_content'] = auth()->user()->username.''.$q.'s deal (<a href="'.config('app.url').'/deal/'.$data['request_data']['deal']['id'].'" target="_blank"><strong>'.$data['request_data']['deal']['deal_name'].'</strong></a>) has been marked as won and has requested to create a new project (<strong>' .$data['request_data']['name'].'</strong>)';
            $result['mailData']['acceptUrl'] = config('app.url').'/data/request/'.$result['mailData']['id'].'/accept/'.$result['mailData']['token'];
            $result['mailData']['denyUrl'] = config('app.url').'/data/request/'.$result['mailData']['id'].'/deny/'.$result['mailData']['token'];
            $result['project'] = $project ?? null;
            $result['userRequest'] = $result['mailData'];
        }else{
            $result['message'] = config('constants.project.requests.messages.toUser.error.requestPendingToCreateProjectOfDeal');
        }
        return $result;
        
    }

    public function sendNotificationToAdmin($result){
        $mail_addresses =  (config('constants.project.requests.resource')[0]  == $this->resource && config('constants.project.requests.request')[config('constants.project.requests.resource')[0]][2] == $this->request_type) ? explode(',', env('MAIL_ADDRESSES_FPCR')) : explode(',', env('MAIL_ADDRESSES'));
                                                    
            foreach ($mail_addresses as $email) { // send notifications to multiple admins according to client in some cases and other to scott only
                $admin = User::where('email',$email)->first();                
                $notificationOfUser = User::where('id',$result['userRequest']['user_id'])->first();
                // Notification::sendNow($admin, new SendAdminUserRequest($notificationOfUser, ['actionSlug' => $result['actionSlug'] ,'mailData' => $result['mailData']],['project' => $result['project'], 'userRequest'=> $result['userRequest'] ,'admin' => $admin]));
                Notification::send($admin, new SendAdminUserRequest($notificationOfUser, ['actionSlug' => $result['actionSlug'] ,'mailData' => $result['mailData']],['project' => $result['project'], 'userRequest'=> $result['userRequest'] ,'admin' => $admin]));                
            
            }       
     
    }

}
