<?php
namespace App\Project\Controllers;

use App\Project\Models\Project;
use App\Base\Http\Controllers\Controller;
use App\Project\Models\ProjectCategories;
use App\Project\Requests\StoreProjectRequest;
use App\Project\Repositories\ProjectRepository;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\ProjectCommissionUser;
use App\Project\Models\ProjectUser;
use App\Project\Models\ProjectCategoriesData;
use App\TaskManager\Models\Task;
use App\Base\Models\User;
use App\Base\Models\Role;
use Illuminate\Http\Request;
use App\Base\Mail\SendAdminUserRequest;
use App\SalesPipeline\Models\Deals;

class ProjectController extends Controller
{
    public function index(ProjectRepository $repository){    
        try {    
        $this->authorize('index', Project::class);
        $request =  new Request;
        $category = ProjectCategories::where('slug',request()->category)->exists();
        if ($category == true || request()->category == config('constants.project.miscellaneous.defaultValues.common_slug')) { /* allow only for existing categories otherwise show 404 error, existed status are handled from vue js route file */

                if (auth()->user()->isFreelancer() == false)  {      
                    $ids = $repository->getProjectIdsOfParticularCategories($request);     /* filter ids */           
                    $projects = Project::whereIn('id',$ids)
                                        ->where('projects.status', request()->status)
                                        ->with('members')
                                        ->with('durations')
                                        ->with('projectCategories')
                                        ->with('commissionUsers')
                                        ->orderBy('deadline', 'asc')->paginate(config('constants.project.pagination'));
                }else{
                    
                    if(request()->status == config('constants.project.status.in_progress')){  // Allow only projects in which user is a member of 
                        $projects = auth()->user()->projects()
                                    ->where('projects.status', request()->status)
                                    ->with('members')
                                    ->with('projectCategories')
                                    ->with('commissionUsers')
                                    ->with('durations')->paginate(config('constants.project.pagination'));
                 }else{
                    return response()->json([
                        'status'   => 'error',
                        'message' => config('constants.miscellaneous.permissions')[403],
                    ],403);
                 }       
            }
        
            if (request()->expectsJson()) {
                return response()->json([
                    'status'   => 'success',
                    'projects' => $projects,
                ]);
            }
        }else{
            return response()->json([
                'status'   => 'error',
                'message' => config('constants.miscellaneous.permissions')[404],
            ],404);
        }
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show(Project $project)
    {     
        try {
            $this->authorize('view', $project);      
        
            $project->load('members:user_id,username,avatar,name', 'settings', 'tags:tag_id,label','durations','commissionUsers','projectCategories');
            
            if (request()->expectsJson()) {
                return response()->json([
                    'status'        => 'success',
                    'project'       => $project,
                    'current_cycle' => $project->current_cycle,
                ]);
            }

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
       
    }

    public function store(StoreProjectRequest $request, ProjectRepository $repository )
    {
        try {
            $data = $request->all();
            $this->authorize('create', Project::class);     

            $deal = Deals::where('id',$data['deal_id'])->first();
            if (isset($deal)) {
                $this->authorize('updateDealStatus', [Deals::class,$deal]);                
            }
            $result = $repository->createProjectAndResources($data);
            $project = isset($result['project']) ? $result['project'] : null;
            $project = isset($project) ? Project::where('id',$project->id)->with('commissionUsers')->with('members:user_id,username,avatar,name')->with('projectCategories')->first() : null;
            if (isset($project) && !isset($deal)) {
                sendDynamicNotification($project, [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[36], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            }
            
            return response()->json([
                'status'        => 'success',
                'message'       => $result['message'],
                'project'       => $project,
            ]);
            
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update($id,ProjectRepository $repository, Request $request)
    {
        try {
            $project = Project::where('id',$id)->first();            
            $this->authorize('update', $project);
            $data = $request->all();            
            $repository->updateProject($data);
            $project_data = Project::where('id',$id)->with('commissionUsers')->with('members:user_id,username,avatar,name','projectCategories')->first();
            sendDynamicNotification($project, [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[37], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            return $this->successResponse(
                'misc.Project updated',
                'project',
                $project_data,
                201
            );

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function delete(Project $project,ProjectRepository $repository)
    {
        try {
            $this->authorize('delete', $project);
            sendDynamicNotificationNow(Project::where('id',$project->id)->first(), [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[38], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            $repository->deleteProjectAndResources($project->id);            
            
            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.The project has been deleted'),
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    private function formatRedirect()
    {
        $url = '?group_type=project&group_id=' . request()->segment(2) . '&tool=' . request()->query('tool') . '&id=' . request()->query('id');

        return redirect($url);
    }

    public function getUserDurationChartData(Request $request,ProjectRepository $repository){
        try {

            $this->authorize('viewProjectDetails', Project::class);
            $chartData =  $repository->getProjectUsersDurationChart($request);
            return response()->json(['status'   => 'success', 'chartData' => $chartData ]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }

    }


    public function getProjectDetails(Request $request, ProjectRepository $repository){
        try { 
                   
            $this->authorize('viewProjectDetails', Project::class);
            $projectMiscellaneous = $repository->getProjectMiscellaneous($request);
            $projectUserChartData =  $repository->getProjectUsersDurationChart($request);
            $projectProfitAndCostChartData = $repository->getProjectProfitAndCostChartData($request);
            return response()->json(['status'   => 'success', 'projectMiscellaneous' => $projectMiscellaneous ,'projectUserChartData' => $projectUserChartData, 'projectProfitAndCostChartData' => $projectProfitAndCostChartData ]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
       
        
    }


    public function getStatus(Request $request,Project $project){
        try {

            $project_status =  $project->where('id',$request->project_id)->first()->status;
            return response()->json(['status'   => 'success', 'projectStatus' => $project_status]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }  

    public function checkIfProjectDealExists(Request $request){
        try {
            $deal = Deals::where('id',$request->data['deal_id'])->first();
            $this->authorize('updateDealStatus', [Deals::class,$deal]);
            $project_deal =  Project::where('deal_id',$request->data['deal_id'])->exists();
            return response()->json(['status' => 'success','project_deal' => $project_deal]);         

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }  
    



}
