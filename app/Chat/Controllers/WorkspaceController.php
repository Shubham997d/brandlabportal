<?php
namespace App\Chat\Controllers;

use App\Base\Http\Controllers\Controller;
use App\Chat\Repositories\WorkspaceRepository;
use App\Chat\Requests\StoreCreateWorkspaceRequest;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index(Request $request, WorkspaceRepository $workspaceRepository)
    {
        try {
            // $this->authorize('updateDeals', Deals::class);
            $data = $request->all();
            $workspaces = $workspaceRepository->listAllWorkspaces($data);
            
            return response()->json([
                'status'  => 'success',
                'workspaces' => $workspaces
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(StoreCreateWorkspaceRequest $request, WorkspaceRepository $workspaceRepository)
    {
        try {
            // $this->authorize('updateDeals', Deals::class);
            $data = $request->all();
            $workspace = $workspaceRepository->createWorkspaceResources($data);
            
            return response()->json([
                'status'  => 'success',
                'message'  => localize('misc.A new workspace created'),
                'workspace' => $workspace
            ]);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
        
    }
}
