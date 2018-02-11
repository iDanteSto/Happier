<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use DateTime;
use Carbon\Carbon;

class recommendationCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recommendation:cleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the status of all the recommendations with status 2 of the user to ignored 4 if it has passed 3 days since its creation';

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
//array of all users
$users = User::where('status', '=', 2)->get();
if (!count($users))
{
//Do nothing because there is no users
}else
{
foreach ($users as $user) 
{
//Declare variable with collection information
$userid = $user[0]->user_Id;
//Compare dates if there is pending status recommendation     
//------------------------------------------------------------------------------
//obtain latest user recommendation with pending status 2
$userRecommendation = DB::table('userrecommendation')
                     ->where('fk_user_Id', '=', $userid)
                     ->where('fk_status_Id', '=', 2)
                     ->orderBy('creation_date', 'asc')
                     ->first();
if(!count($userRecommendation))
{
//Empty collection , there is no pending status recommendations   
//Do nothing  
}else
{    
//parse to carbon format                     
$end = Carbon::parse($userRecommendation->creation_date);
//obtain now date on carbon format
$now = Carbon::now();
//compare date obtained with the current date to obtain the difference on days
$length = $end->diffInDays($now); 
//we want to change the status to ignored if it has 3 days
if(!$length >= 3)
{
//It has less than 3 days so it wont do anything
}else
{
//Update all(for secutiry reasons all , not only the obtained) pending recommendations to status 4 ignored    
DB::table('userrecommendation')
          ->where('fk_status_Id', 2)
          ->where('fk_user_Id', $userid)
          ->update(['fk_status_Id' => 4]);         
}
}                     
//------------------------------------------------------------------------------
}//end for each
}//end if to see if there is users   
    }
}
