<?php

namespace App\Base\Console\Commands;
use Illuminate\Console\Command;
use App\Base\Mail\userStatusMail;
use Illuminate\Support\Facades\Mail;
use App\Base\Models\User;

class UserProfileStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:profilestatus';

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
        $users = User::where('active',true)->where('deleted',false)->get(['username','email']);
        $users->map(function ($item,$key) {
                Mail::to($item->email)->send(new userStatusMail($item)); 
        });
    }
}
