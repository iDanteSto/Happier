<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use DateTime;
use Carbon\Carbon;
use App\UserMood;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class moodController extends Controller
{
public function moodLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$defaultNumber = 5;
$LatestMoodLoader = DB::table('usermood')
         ->where('fk_user_Id', $userid)
         ->orderBy('created_at','descendant')
         ->pluck('mood')->first();
if (!count($LatestMoodLoader)) 
{
	return $defaultNumber;
}
else
{
	return $LatestMoodLoader;
}         
}
/*
|--------------------------------------------------------------------------
| Mood Average Loader
|--------------------------------------------------------------------------
|
| Loads  the user moods averages of the last 8 weeks (counting from his begining)
|
*/
public function moodAverageLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;

$weekAverages = DB::select
(
'SELECT 
avg(mood) as Average 
FROM usermood 
WHERE created_at >    DATE_SUB(NOW(), INTERVAL 8 WEEK) and fk_user_Id = ?
GROUP BY WEEK(created_at)
ORDER BY created_at desc limit 8',[$userid]
);
if($weekAverages)
{
//if it exist redirect to the app route with the parameters

//reverse for fitting purposes
$reversed = array_reverse($weekAverages);


return $reversed;
}
else
    {
        return "No hay informacion";
    } 
  
}
/*
|--------------------------------------------------------------------------
| Mood moodCreateorUpdater
|--------------------------------------------------------------------------
|
| Creates and updates the Mood of the day obtained from laravel server time zone on config\app.php
|
*/
public function moodCreateorUpdater(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$moodValoration =$request->moodValoration;
$currentTime = DateTime::createFromFormat('H:i a',date("h:i:sa"));
$currentDate = Carbon::now()->format('Y-m-d');
$currentDatefull = Carbon::now();
//Currently we just see with the Date(Y-m-d), in a future we could change this to obtain the exact hours and minutes of Updates  but we would have to change the DB Structure to enable timestamps and here on laravel to stop ignoring timestamps on the model .
$Mood = UserMood::updateOrCreate([
    'fk_user_Id'   => $userid,
    'created_at'   => $currentDate,
],[
   // 'user_id'   => Auth::user()->id,
    'mood'     => $moodValoration,
   // 'fk_status' => '1'
    'created_at' => $currentDate
]);
return "Succes Updating";
}


/*
|--------------------------------------------------------------------------
| Mood Checker Notifier
|--------------------------------------------------------------------------
|
| Checks if the user has an entry on the day , if he doesnt it will send a notification to remember him to do it
|
*/
public function moodCheckerNotifier(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
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

}


}
