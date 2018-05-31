<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;
use DateTime;


class moodSetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mood:setter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if the user can receive a new mood , if it can creates the holder of the mood';

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
$users = DB::select('SELECT 
        users.user_Id, users.devicetoken , users.displayname
        FROM
        users
        WHERE
        users.status = ?
        AND devicetoken != ?', [2,""]);
//if there is no confirmed users
if (count($users))
{
//for every user , do the logic
    foreach ($users as $usuarios) 
    {
        $userid = $usuarios->user_Id;
        $username = $usuarios->displayname;
        $userDeviceToken = $usuarios->devicetoken;


//$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
//$userid = $user->user_Id;//place id on a variable to use it 
$currentDate = date('Y-m-d');
//result 1 = user has a recom to be seen  ,  0 = user has not a recommendation to be seen
$checkermoodResult;
//------------------------------------------------------------------
//count user moods with status [2] pending 
        $moodCounter = DB::table('usermood')
        ->select(DB::raw('count(userMood_Id) as moodCount'))
        ->where('fk_user_Id', '=', $userid)
        ->where('fk_status', '=', 2)
        //->whereDate('creation_date', $currentDate)
        ->get();
        $moodCount = $moodCounter[0]->moodCount;
//--------------------------------------------------------------

if(!$moodCount == null or !$moodCount == "")
{//if it has atleast one
$checkermoodResult =1;
}else
{//if it doesnt have any of this
DB::table('usermood')->insert(
    ['fk_user_Id' => $userid]
);
//<-------------------Push Notification---------------------------------------------------->
$optionBuilder = new OptionsBuilder();
$optionBuilder->setTimeToLive(60*20);
$notificationBuilder = new PayloadNotificationBuilder('Recuerda decirnos como te has sentido esta semana');
$notificationBuilder->setBody($username.'¿Qué tal ha estado tu semana? Compártenoslo
    La semana está a punto de acabar, no dejes de decirnos como te has
sentido')
      ->setClickAction('ACTIVITY_PROF')
            ->setSound('default');
$dataBuilder = new PayloadDataBuilder();
$dataBuilder->addData(['a_data' => 'my_data']);
$option = $optionBuilder->build();
$notification = $notificationBuilder->build();
$data = $dataBuilder->build();
$token = $userDeviceToken;
$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
$downstreamResponse->numberSuccess();
$downstreamResponse->numberFailure();
$downstreamResponse->numberModification();
//<-------------------Push Notification---------------------------------------------------->
}
}//close foreach
}//close if



    }



    

}
