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
       Commands\testingcommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   //here you assign the commands to be executed at wich schedule [*_*]
    
        //schedules for recommendations
       // $schedule->command('recommendation:setter')->dailyAt('8:00');
       // $schedule->command('recommendation:setter')->dailyAt('11:00');
       // $schedule->command('recommendation:setter')->dailyAt('13:00');
      //  $schedule->command('recommendation:setter')->dailyAt('16:00');
      //  $schedule->command('recommendation:setter')->dailyAt('18:00');

      
       //$schedule->command('user:test')->everyMinute();      
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
