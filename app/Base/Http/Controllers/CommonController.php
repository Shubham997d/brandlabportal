<?php

namespace App\Base\Http\Controllers;

use Exception;
use DateTimeZone;
use App\Base\Models\User;
use App\Base\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Base\Repositories\UserRepository;
use App\Project\Repositories\ProjectRepository;
use App\SalesPipeline\Repositories\DealsRepository;
use App\Base\Mail\UserRegistered;
use App\Base\Mail\SendInvitationToRegister;
use App\Base\Models\Role;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\ProjectUser;
use App\TaskManager\Models\Task;
use App\SalesPipeline\Models\Deals;
use App\Base\Http\Controllers\UserController;
use Illuminate\Support\Facades\Notification;
use App\SalesPipeline\Notifications\DealTransferOwnership;
use App\SalesPipeline\Models\DealOrganisation;
use App\Chat\Repositories\WorkspaceRepository;

class CommonController extends Controller
{
    
    public function getCommonData(UserRepository $userRepository,ProjectRepository $projectRepository, WorkspaceRepository $workspaceRepository, Request $request){
        try {
            $users = $userRepository->getAvailableUsers();
            $project_categories = $projectRepository->getProjectCategories();
            $workspaces = $workspaceRepository->listAllWorkspaces($request->all());
            
            
            return response()->json([
                'status'  => 'success',
                'users'   => $users,
                'project_categories'  => $project_categories,
                'workspaces' => $workspaces
            ]);              

        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }  

    }    

    public function transfertDataOfProjectsAndDealsThenDeleteUser(ProjectRepository $projectRepository,DealsRepository $dealsRepository, UserRepository $userRepository, Request $request){
        try {            
            
            $this->authorize('deleteAccount',[User::class,$request->tranferFromUser['id']]);
            $status['tranferProjectManagers'] = $projectRepository->tranferProjectManagers($request);
            $status['tranferDeals'] = $dealsRepository->tranferDeals($request);            
            $status['userDeleted'] = $userRepository->softDeleteUser($request->tranferFromUser['id']);
            if (isset($status['userDeleted'])) {
                sendDynamicNotification($status['userDeleted'], [ 'group_type' => config('constants.notifications.group_type')[4], 'action_slug' => config('constants.notifications.action_slug')[43], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            }
            if (isset($status['tranferDeals'])) {
                $newOwner = User::where('id',$request->tranferDealsTo)->first();
                $deals = DealOrganisation::whereIn('deal_id',$status['tranferDeals'])->get(['deal_id','title']);
                $oldOwner = User::where('id',$request->tranferFromUser['id'])->first();                                
                Notification::send($newOwner, new DealTransferOwnership($deals,auth()->user(), $oldOwner, $newOwner,true));
            }

            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.User deleted Successully.')
            ]);              

        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }  

    }

    
}
