<?php

namespace App\Chat\Repositories;

use App\Chat\Models\Workspace;
use App\Chat\Models\WorkspaceMember;


class WorkspaceRepository
{
   

    public function __construct(Workspace $workspace)
    {
        $this->model = $workspace;
    }

    public function createWorkspaceResources($data)
    {
        $workspace = $this->createWorkspace($data);
        $this->addWorkspaceMembers($data,$workspace->id);
        $result = $this->getWorkspace($workspace->id);
        return $result;
    }

    public function addWorkspaceMembers($data,$workspaceId){
        $members = array();
        if ($data['selectedUsers']) {
            foreach ($data['selectedUsers'] as $selectedUser) {
                $exists = WorkspaceMember::where('workspace_id',$workspaceId)->where('user_id',$selectedUser['value'])->exists();
                if ($exists == false) {
                    WorkspaceMember::create([
                        'user_id' => $selectedUser['value'],
                        'workspace_id' => $workspaceId                    
                    ]);
                }
            }
        }
        return $members;
    }

    public function createWorkspace($data)
    {
        return Workspace::create([
            'name' => $data['name'],
            'slug' => formatString($data['name'],false,false,true),
            'meta' => $data['meta'] ?? null
        ]);
    }

    public function listAllWorkspaces($data = null){
            return Workspace::with('members:id,user_id,workspace_id')->get();
    }

    public function getWorkspace($workspaceId) {
            return  Workspace::where('id',$workspaceId)->with('members:id,user_id,workspace_id')->get();
    }
}
