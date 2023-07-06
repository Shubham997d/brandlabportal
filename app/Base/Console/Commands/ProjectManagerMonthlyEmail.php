<?php

namespace App\Base\Console\Commands;

use Illuminate\Console\Command;
use App\Base\Mail\projectManagerMail;
use Illuminate\Support\Facades\Mail;
use DB;
use Carbon\Carbon;

class ProjectManagerMonthlyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manager:monthly';

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
        // \Log::info("Cron is working fine!");
        $project = DB::table('projects')
        ->join('users','projects.project_manager_id','=','users.id')
        ->where('users.active','=',true)
        ->where('users.deleted','=',false)
        ->where('status',config('constants.project.status.completed'))
        ->whereBetween('completed_at', [Carbon::now()->subDays(config('constants.emailTrigger.monthlyMail')), Carbon::now()])
        ->get(['projects.id','projects.name','projects.project_manager_id','users.email','users.username'])
        ->groupBy('project_manager_id')->map(function ($items,$key) {
            $project_names = array_column($items->toArray(), 'name');
            $item['name'] = implode(', ', $project_names);
            $item['username'] = $items->first()->username;
            if (count($project_names) == 1) {
                $item['count'] = "project";
              }else {
                  $item['count'] = "projects";
              }
         Mail::to($items->first()->email)->send(new projectManagerMail($item)); 
        });
        // \Log::info("Cron work is completedddddddddd!");

    }
}
