<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;
use DateTime;
use Carbon\Carbon;
use App\UserMood;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class moodNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mood:notifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if the user has an entry on the day , if he doesnt it will send a notification to remember him to do it';

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
        users.user_Id, users.devicetoken
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
        $userDeviceToken = $usuarios->devicetoken;


//$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
//$userid = $user->user_Id;//place id on a variable to use it 
$currentDate = Carbon::now()->format('Y-m-d');
$checkToSendNotification = DB::table('usermood')
         ->where('fk_user_Id', $userid)
         ->where('created_at', $currentDate)
         //->orderBy('created_at','descendant')
         ->pluck('created_at')->first();
if (count($checkToSendNotification)) 
{
return "Did  nothing!";
}
else
{
//<-------------------Push Notification---------------------------------------------------->
$optionBuilder = new OptionsBuilder();
$optionBuilder->setTimeToLive(60*20);
$notificationBuilder = new PayloadNotificationBuilder('Como te sientes esta semana?');
$notificationBuilder->setBody('Text holder')
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
return "Notification Sent!";
} 
}//close foreach
}//close if



    }  

    }
}
