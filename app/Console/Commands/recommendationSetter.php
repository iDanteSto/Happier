<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;
class recommendationSetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */       //namespace of the command [*_*]
    protected $signature = 'recommendation:setter';

    /**
     * The console command description.
     *
     * @var string
     */             //description of the command [*_*]
    protected $description = 'Checks if the user interactued with its last recommendation assigned , if he did it will assignate a new one  , if not it will send a push notification to the user';

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
        //logic to do [*_*]----------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------test area---------------------------------------------------------------------------
$user = User::where('email', '=', 'megablastoise@golemico.com')->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 

$frequencyChecker = DB::select('SELECT 
    timesAtDay, description
FROM
    userfrequency,
    frequency
WHERE
    frequency_Id = userfrequency.fk_frequency_Id
        AND userfrequency.fk_user_Id = ?', [$userid]);
$timesAtDay = $frequencyChecker[0]->timesAtDay;


if ($timesAtDay == 1)
{
   return 'fue 1';
}
elseif ($timesAtDay == 3)
{
   return 'fue 3';
}
elseif ($timesAtDay == 5)
{
   return 'fue 5';
}






Log::info("Success");

//------------------------------------------------------------------------------------------------------------------------------------------------------------------
    }
}
