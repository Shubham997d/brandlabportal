<?php

namespace App\Base\Console\Commands;

use Illuminate\Console\Command;
use App\Base\Mail\TaskUpdateMail;
use Illuminate\Support\Facades\Mail;
use App\Base\Models\User;
use App\Base\Notifications\DailyTaskUpdate as DailyTaskUpdateNotification;

class DailyTaskUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyTask:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        /* Trigger Daily Task Update For Users having in-progress projects and are active and not deleted from the database */     
        $users = User::join('project_user','project_user.user_id','=','users.id')->join('projects','projects.id','=','project_user.project_id')->where('projects.status',config('constants.project.status.in_progress'))->where('active',true)->where('deleted',false)->get(['users.id', 'users.name', 'username', 'avatar','users.email'])->unique();
        $users->map(function ($user,$key) { 
                    $user->notify(new DailyTaskUpdateNotification($user)); 
        });

    }
}
