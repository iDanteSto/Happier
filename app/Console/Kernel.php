<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [//class referencing the command [*_*]
       Commands\deleteNotConfUsers::class,
       Commands\recommendationSetter::class,
       Commands\cleanDeleteUser::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   //here you assign the commands to be executed at wich schedule [*_*]
    
        
        $schedule->command('users:delete')->dailyAt('19:00');
        
        //corre daily at 7am to 11 am        
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
