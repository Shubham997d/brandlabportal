<?php

namespace App\Base\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Base\Console\Commands\PluginInstallCommand;
use App\Base\Console\Commands\PluginDiscoverCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PluginDiscoverCommand::class,
        PluginInstallCommand::class,
        Commands\MailScheduler::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        $schedule->command('deal:stagnation')->twiceDaily(10, 14);
        // $schedule->command('deal:stagnation')->everyTwoMinutes();
        // $schedule->command('deal:stagnation')->dailyAt('08:55');        
        $schedule->command('database:backup')->twiceDaily(4, 17);
        // $schedule->command('database:backup')->dailyAt('23:00');        
        $schedule->command('user:profilestatus')->monthlyOn(1,'15:00');

        $schedule->command('manager:monthly')->monthlyOn(1, '16:00');
       
        $schedule->command('dailyTask:update')->days([1,3,5])->at('16:30');

        // $schedule->command('dailyTask:update')->dailyAt('14:26');

        // 1,3,5 for monday,wednesday and friday
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
        $this->load(__DIR__ . '/Commands');
    }
}
