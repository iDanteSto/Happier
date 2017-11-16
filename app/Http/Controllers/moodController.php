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
//
/*
|--------------------------------------------------------------------------
| Mood Checker
|--------------------------------------------------------------------------
|
| Checks if the user has interacted with his last mood assigned
|
*/
/*
public function moodChecker(Request $request)
{     
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$currentDate = date('Y-m-d');
//result 1 = user has a recom to be seen  ,  0 = user has not a recommendation to be seen
$checkermoodResult;
//------------------------------------------------------------------
//count user recommendations within the current day 
$moodCounter = DB::table('usermood')
->select(DB::raw('count(userMood_Id) as moodCount'))
->where('fk_user_Id', '=', $userid)
->where('fk_status', '=', 2)
//->whereDate('creation_date', $currentDate)
->get();
$moodCount = $moodCounter[0]->moodCount;
//--------------------------------------------------------------
if(!$moodCount == null or !$moodCount == "")
{
$checkermoodResult =1;

//echo 'Despliega boton';
}else
{
$checkermoodResult =0;

//echo 'No Despliegues boton';
}
return $checkermoodResult;
//return 'Succes holder creado!';
}
*/
/*
|--------------------------------------------------------------------------
| Mood Setter
|--------------------------------------------------------------------------
|
| Assign  a mood holder 
|

public function moodSetter(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
DB::table('usermood')->insert(
['fk_user_Id' => $userid]
);
return response()->json(['succes'=> 'Se creo el holder!'], 200);
}
*/
/*
|--------------------------------------------------------------------------
| Mood Loader
|--------------------------------------------------------------------------
|
| Loads  user last value of the mood in order to update the mood bar on the front end
|
*/
public function moodLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$defaultNumber = 5;
/*
$moodInfo = DB::select('SELECT 
userMood_Id AS moodId
FROM
usermood
WHERE
fk_user_Id = ? AND fk_status = ?
ORDER BY RAND()
LIMIT 0 , 1;', [$userid, 2]);//retrieve peferred_categories from the user
if (count($moodInfo)) {
return $moodInfo;
}else
{
return 'No hay!';
}
//return response()->json(['succes'=> 'Hello World!'], 200);
*/
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
| Mood Completer
|--------------------------------------------------------------------------
|
| Updates the designated holder with the value of valoration given by the user
|

public function moodCompleter(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$moodId = $request->moodId;
$moodValoration =$request->moodValoration;

DB::table('usermood')
->where('userMood_Id', $moodId)
->update(array('mood' => $moodValoration , 'fk_status' => 1));
return response()->json(['succes'=> 'Update Succesful!'], 200);
}
*/
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
//$moodId = $request->moodId;
$moodValoration =$request->moodValoration;
/*
DB::table('usermood')
->where('userMood_Id', $moodId)
->update(array('mood' => $moodValoration , 'fk_status' => 1));
*/
$currentTime = DateTime::createFromFormat('H:i a',date("h:i:sa"));
//$ekizde = date("h:i:sa");
$currentDate = Carbon::now()->format('Y-m-d');
$currentDatefull = Carbon::now();
//echo $mytime->toDateTimeString();

/*
$Mood = App\UserMood::updateOrCreate(
    ['date' => $currentDate, 'date' => $currentDate,'fk_user_Id' => $userid, 'date' => $currentDate],

    ['price' => 99]
);
fk_user_Id
mood
fk_status
date
'2017-12-15'
*/
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
/*
$Mood = App\UserMood::updateOrCreate(
    ['date' => $currentDate, 'date' => $currentDate,'fk_user_Id' => $userid, 'date' => $currentDate],

    ['price' => 99]
);
fk_user_Id
mood
fk_status
date
'2017-12-15'
*/

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

/*

*/
}


}
