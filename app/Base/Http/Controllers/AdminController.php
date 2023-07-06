<?php

namespace App\Base\Http\Controllers;

use App\Base\Repositories\UserRepository;
use App\Project\Models\ProjectUsersDuration;
use App\Project\Models\Project;

class AdminController extends Controller
{
    public function index(UserRepository $userRepository)
    {       
        // dd('test');
        // $users = $userRepository->getAllUsers([
        //     'id', 'name', 'username', 'email', 'timezone', 'avatar', 'role_id','colour','salary',
        // ]);

        // // Add Total Duration And Project Name 

        // foreach ($users as $user) {
        // 	$projectUsersDuration = new ProjectUsersDuration;
        //     $project = new Project;
        //     foreach ($user->userProjectDuration as $value) {
        //             $project_name = $project->where('id', $value['project_id'])->value('name');
        //             $value->project_name = $project_name ?? '';                    
                  
        //     }
        //     $total_duration = $projectUsersDuration ->where('user_id', $user->id)->sum('duration'); 
    	// 	$user->total_duration = $total_duration ?? '';

    	// }
        

        // auth()->user()->setAppends(['unread_direct_messages']);

        // return view('admin.index', ['users' => $users]);
    }

    

}
