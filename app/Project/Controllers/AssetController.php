<?php

namespace App\Project\Controllers;

use Exception;
use App\Project\Models\Task;
use App\Base\Utilities\GroupTrait;
use App\Base\Http\Controllers\Controller;
use App\Base\Repositories\MentionRepository;
use App\Project\Requests\UpdateTaskRequest;
use App\Project\Repositories\AssetRepository;
use App\Project\Models\ProjectUser;
use App\Project\Models\Project;
use App\Project\Models\Asset;
use App\Base\Models\User;
use Illuminate\Http\Request;
use App\Project\Requests\UpdateAssetRequest;

class AssetController extends Controller
{
    use GroupTrait;

    public function store(Request $request, AssetRepository $repository)
    {
        try {
            $this->authorize('create', Asset::class);
            $data = $request->all();
            $asset = $repository->create($data);            
            sendDynamicNotification(['project' => Project::where('id',$data['project_id'])->first(), 'asset' => $asset ], [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[33], 'visible_to_admin' => true, 'visible_to_user' => false ]);
            return response()->json([
                'status'  => 'success',
                'message' => localize('misc.New Asset has been created'),
                'asset'    => $asset,
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
   

    public function index(Request $request, AssetRepository $repository)
    {
            $this->authorize('index', [Asset::class, new Project]);
            $asset = $repository->getAssets($request->all());
    
            return response()->json([
                'status'   => 'success',
                'assets'    => $asset,
            ]);
       
    }

    public function delete(Request $request, AssetRepository $repository)
    {
        $this->authorize('delete', Asset::class);
        $data = $request->all();        
        sendDynamicNotificationNow(['project' => Project::where('id',$data['project_id'])->first(), 'asset' => Asset::where('id',$data['id'])->first() ], [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[35], 'visible_to_admin' => true, 'visible_to_user' => false ]);
        $repository->delete($data);        
        return response()->json([
            'status'  => 'success',
            'message' => localize('misc.The Asset has been deleted'),
        ]);
    }

    public function update(UpdateAssetRequest $request, AssetRepository $repository)
    {
        $this->authorize('update',Asset::class);
        $data = $request->all();
        $asset = $repository->update($data);        
        sendDynamicNotification(['project' => Project::where('id',$data['project_id'])->first(), 'asset' => $asset ], [ 'group_type' => config('constants.notifications.group_type')[3], 'action_slug' => config('constants.notifications.action_slug')[34], 'visible_to_admin' => true, 'visible_to_user' => false ]);
        return response()->json([
            'status'  => 'success',
            'message' => localize('misc.Asset has been updated')            
        ]);
    }
}
