<?php

namespace App\Base\Http\Controllers;

use App\Project\Models\Project;
use App\Base\Models\UserRequest;
use App\Base\Http\Controllers\Controller;
use App\Project\Requests\StoreProjectRequest;
use App\Project\Repositories\ProjectRepository;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\ProjectCommissionUser;
use App\Project\Models\ProjectUser;
use App\TaskManager\Models\Task;
use App\Base\Models\User;
use App\Base\Models\Role;
use Illuminate\Http\Request;
use App\Base\Mail\SendAdminUserRequest;
use App\Base\Repositories\UserRequestsRepository;



class UserRequestsController extends Controller 
{
    
    public function handleUserRequest($resource, $request_type,Request $request, UserRequestsRepository $repository){
        try {      
            
            $this->authorize('canCreateRequest',UserRequest::class);
            $result = $repository->handleUserRequest($resource, $request_type, $request->all());
            if ($result['success'] == true) {
                return response()->json(['status'   => 'success', 'message' => $result['message']]);                
            }else{
                return response()->json(['status'   => 'error', 'message' => $result['message']],400);                 
            }

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }      
        
    }
    
    public function updateUserDataForRequest($request_id, $status, $token, UserRequestsRepository $repository){
        try { // when admin clicks accept or deny

            $data['request_id'] = (int) $request_id;
            $data['status'] = $status;
            $data['token'] = $token;
            $result = $repository->updateUserDataForRequest($data);
            $message = $result['message'];
            return view('messages.user',compact('message'));

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }      
        
    }

    
}
