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
       Commands\cleanDeleteAllUsers::class,
       Commands\testingcommand::class,
       Commands\moodNotifier::class,
       Commands\recommendationCleaner::class,
       Commands\userHibernationReverter::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   //here you assign the commands to be executed at wich schedule [*_*]
    /*
      //<-------------Schedule for recommendations----------------------------------->
       $schedule->command('recommendation:setter')->dailyAt('08:00');
       $schedule->command('recommendation:setter')->dailyAt('11:00');
       $schedule->command('recommendation:setter')->dailyAt('13:00');
       //$schedule->command('recommendation:setter')->dailyAt('14:31');//for test
       $schedule->command('recommendation:setter')->dailyAt('18:00');
       $schedule->command('recommendation:setter')->dailyAt('21:00');
      //
      // notes :important to keep the system running stable
      //<---------------------------------------------------------------------------->

      //<-------------clean for recommendations-------------------------------------->
       $schedule->command('recommendation:cleaner')->dailyAt('23:59');
      //
      // notes :important to keep the system running stable
      //<---------------------------------------------------------------------------->

      //<-------------revert expired hibernations ----------------------------------->
      // $schedule->command('userHibernation:reverter')->dailyAt('23:59');
      //
      // notes :important to keep the system running stable
      //<----------------------------------------------------------------------------> 

      //<-------------Schedule for moods--------------------------------------------->
       $schedule->command('mood:notifier')->dailyAt('14:00');
      //
      // notes :important to keep the system running stable
      //<---------------------------------------------------------------------------->
*/
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
