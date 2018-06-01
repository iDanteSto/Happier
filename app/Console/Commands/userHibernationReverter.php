<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use DateTime;
use Carbon\Carbon;

class userHibernationReverter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userHibernation:reverter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checks all users on hibernation status 3 , to revert those who hast its time expired';

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
$users = User::where('status', '=', 3)->get();
if (!count($users))
{
//Do nothing because there is no users
}else
{
foreach ($users as $user) 
{
//Declare variable with collection information
$userid = $user->user_Id;  
//Compare dates to check if its time to revert the hibernation  
//------------------------------------------------------------------------------
//obtain hibernation information
$userHibernationstate = DB::table('userhibernation')
                     ->where('fk_user_Id', '=', $userid)
                     ->first();
if(!count($userHibernationstate))
{
//Empty collection , there is no hibernation info on DB
//Do nothing  
}else
{    
//parse to carbon format                     
$end = Carbon::parse($userHibernationstate->creation_date);
//obtain now date on carbon format
$now = Carbon::now();
//compare date obtained with the current date to obtain the difference on days
$length = $end->diffInDays($now); 

if($length >= $userHibernationstate->duration)
{ 
//delete userhibernation on DB
DB::table('userhibernation')->where('fk_user_Id', '=', $userid)->delete();
//update status to 2 on DB
DB::table('users')
->where('user_Id', $userid)
->update(['status' => 2]);    
}else
{
//It has less than the expiration days so it wont do anything
}
}                     
//------------------------------------------------------------------------------
}//end for each
}//end if to see if there is users   
    }
}
