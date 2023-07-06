<?php

namespace App\Base\Http\Controllers;

use App\Base\Models\Permission;
use App\Project\Models\Project;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()->groupBy('resource');

        return response()->json([
            'status'       => 'success',
            'permissions'  => $permissions,
        ]);
    }

    public function checkUserHasPermmission($group,$action, $group_id)  /* Dynamic permissions */
    { 
        if ($group == 'project' &&  $action == 'update') {
            $project = Project::where('id',$group_id)->first();            
            $this->authorize('update', $project);
        }

        return response()->json([
            'status'       => 'success',
            'message'  => 'User allowed'
        ]);        
    }
}
