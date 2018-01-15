<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//FCM stuff
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\User;
use DB;
use DateTime;
use Carbon\Carbon;
use App\UserMood;


class dummyController extends Controller
{
//------------------------------------------------------------------------Dummy functions to test facebook login functions-------------------------------------------------------------------------

public function SPManagerAndroidUpdater(Request $request)
{//obtained during session stored info on the app that will be erased after process ends
$userEmail =$request->email;
$check = $request->check;
$provider = $request->provider;
$providerId = $request->providerId;
$token = $request->token;
$devicetoken = $request->devicetoken;
$nickname = $request->nickname;
$date = date("Y-m-d H:i:s");
//.......
//check if exist already
//$check = DB::select('SELECT distinct(id) FROM social_providers where user_id = ? and social_providers.provider = ?', [$userId,$provider]);
//$check = DB::select('SELECT distinct(social_provider_Id) FROM social_provider , users where users.email = ? and social_provider.fk_user_Id = users.user_Id and social_provider.provider = ?', [$userEmail,$provider]);
if ($check == 1)
{
//if there exist************************************ 
DB::table('users')
->where('email', $userEmail)
->update(array( 
"remember_token" => $token,
"devicetoken" => $devicetoken,
));
//**************************************************
}
else
{
//if does not exist*********************************
//create it the user model  
$person = User::firstOrCreate(
['email'=> $userEmail,
'nickname'=> $nickname,
"remember_token" => $token,
'devicetoken' => $devicetoken]
);
$userId = $person->id;
//user creation dependancys to protect the system as if it was registered and confirmed@@@@@@@@@@@@@@@@@@@@@@@@
User::where(['email'=>$userEmail])->update(['status' =>'1','verifyToken'=>NULL]);
DB::table('userfrequency')->insert(
['fk_frequency_Id' => 3, 'fk_user_Id' => $userId]
);
//Asignate every category available to the user as a standard
$categsIds = DB::select('SELECT distinct(category_Id) FROM category;');
foreach ($categsIds as $categ) {
DB::table('preferred_categories')->insert(
['fk_user_Id' => $userId, 'fk_category_Id' => $categ->category_Id]
);
}
//assignate standard avatars-----------------------------------------------------------------------  
$avatarIds = DB::select('SELECT distinct(avatar_Id) FROM avatar where fk_avatar_categories_Id = ?',[1]);
foreach ($avatarIds as $avatars) {
DB::table('avatar_permission')->insert(
['fk_user_Id' => $userId, 'fk_avatar_Id' => $avatars->avatar_Id]
);
}
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//create social provider dependancys
DB::table('social_provider')->insert(
['fk_user_Id' => $userId, 
'provider_Id' => $providerId,
'provider' => $provider]
);
//**************************************************
}  
}

public function SPManagerAndroidChecker(Request $request)
{
$check;
$userEmail =$request->email;
$provider = $request->provider;	
$checks = DB::select('SELECT distinct(social_provider_Id) FROM social_provider , users where users.email = ? and social_provider.fk_user_Id = users.user_Id and social_provider.provider = ?', [$userEmail,$provider]);
if (count($checks))
{
$check = 1;
}else
{
$check = 0;
}
return $check;
}




public function dummyFunctionMoodnotif(Request $request)
{  
//array of all users
$users = User::where('status', '=', 2)->get();
//if there is no confirmed users
if (count($users))
{
//for every user , do the logic
    foreach ($users as $user) 
    {
        $userid = $user->user_Id;
        $userDeviceToken = $user->devicetoken;


//$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
//$userid = $user->user_Id;//place id on a variable to use it 
$currentDate = Carbon::now()->format('Y-m-d');

$checkToSendNotification = UserMood::where('fk_user_Id', '=', $userid)
->where('created_at','=', $currentDate)
->first();
/*
$checkToSendNotification = DB::table('usermood')
         ->where('fk_user_Id', $userid)
         ->where('created_at', $currentDate)
         //->orderBy('created_at','descendant')
         ->pluck('created_at')->first();
*/
//dd($checkToSendNotification);

if (count($checkToSendNotification)) 
{
//return "Did  nothing!";
}
else
{
//<-------------------Push Notification---------------------------------------------------->
$optionBuilder = new OptionsBuilder();
$optionBuilder->setTimeToLive(60*20);
$notificationBuilder = new PayloadNotificationBuilder('Como te sientes hoy?');
$notificationBuilder->setBody('Clickea aqui para ir a la app a ver tu mood')
      ->setClickAction('ACTIVITY_PROF')
            ->setSound('default');
$dataBuilder = new PayloadDataBuilder();
$dataBuilder->addData(['moodOn' => 'yes']);
$option = $optionBuilder->build();
$notification = $notificationBuilder->build();
$data = $dataBuilder->build();
$token = $userDeviceToken;
$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
$downstreamResponse->numberSuccess();
$downstreamResponse->numberFailure();
$downstreamResponse->numberModification();
//<-------------------Push Notification---------------------------------------------------->
//return "Notification Sent!";
} 
}//close foreach
}//close if
}


//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------Dummy functions to test recommendationSetter commands------------------------------------------------------------------------
public function dummyFunction0(Request $request)
{   
//Cleans all the pending recommendations
//DB::table('userrecommendation')->where('fk_status_Id', '=', 2)->delete();
//return 'Success cleaning all pending recommendations';
    $id = $request->id;
        DB::table('usermeditation')->where('fk_user_Id', '=', $id)->delete();
        DB::table('avatar_permission')->where('fk_user_Id', '=', $id)->delete();
        DB::table('preferred_categories')->where('fk_user_Id', '=', $id)->delete();
        DB::table('usermeditation')->where('fk_user_Id', '=', $id)->delete();
        DB::table('userfrequency')->where('fk_user_Id', '=', $id)->delete();
        DB::table('usermood')->where('fk_user_Id', '=', $id)->delete();
        DB::table('userrecommendation')->where('fk_user_Id', '=', $id)->delete();
        DB::table('social_provider')->where('fk_user_Id', '=', $id)->delete();
        DB::table('users')->where('user_Id', '=', $id)->delete();
}




public function dummyFunction(Request $request)
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
}
}//close foreach
}//close if
}




public function dummyFunction2(Request $request)
{   
//<---------------------------------------------------------------------------------Big Monster-------------------------------------------------------------------------->
//select all users that are verified and has

//if there is no confirmed users

//for every user , do the logic

$currentDate = date('Y-m-d');
$userid = $request->userid;
$userDeviceToken = $request->devicetoken;
$timesAtDay = $request->timesAtDay;
//count user recommendations within the current day
$recomCountCollection = DB::table('userrecommendation')
->select(DB::raw('count(userRecommendation_Id) as recomCount'))
->where('fk_user_Id', '=', $userid)
->whereDate('creation_date', $currentDate)
->get();
$recomCount = $recomCountCollection[0]->recomCount;
//compare the recomCount against the user frequency chosen to see if he can receive a new recommendation on the day
if($recomCount <= $timesAtDay)
{
//retrieve the number of recommendations of the user that he has not interacted with 
$recomReadyCollection = DB::table('userrecommendation')
->select(DB::raw('count(userRecommendation_Id) as recomCount2'))
->where('fk_user_Id', '=', $userid)
->where('fk_status_Id', '=', 2)
->whereDate('creation_date', $currentDate)
->get(); 
//place the number of recommendations of the user that he has not interacted with on a variable to use it                 
$recomReady = $recomReadyCollection[0]->recomCount2;

//check if the user has a pending recommendation to interact with and if is eligible to get a new one
if($recomReady == 0)
{//the user is eligible to get a new recommendation
//*****'The user is ready to receive a recommendation *****
//--***********************************recommendation process***************------------------------------------------------
$timeOfDay =0;
//date("h:i:sa")
//Current server time
$currentTime = DateTime::createFromFormat('H\h i\m s\s',date('H\h i\m s\s'));
//Morning
$beginMorning = DateTime::createFromFormat('H\h i\m s\s', "08h 00m 00s");
$endMorning = DateTime::createFromFormat('H\h i\m s\s', "12h 59m 00s");
//Evening
$beginEvening = DateTime::createFromFormat('H\h i\m s\s', "13h 00m 00s");
$endEvening = DateTime::createFromFormat('H\h i\m s\s', "17h 00m 00s");
//Night
$beginNight = DateTime::createFromFormat('H\h i\m s\s', "18h 00m 00s");
$endNight = DateTime::createFromFormat('H\h i\m s\s', "23h 00m 00s");
if ($currentTime >= $beginMorning && $currentTime <= $endMorning)
{
//Morning
$timeOfDay = 1;   
}else if($currentTime >= $beginEvening && $currentTime <= $endEvening)
{ 
//Evening
$timeOfDay =2;
}else if($currentTime >= $beginNight && $currentTime <= $endNight)
{
//Night
$timeOfDay =3;
}
//$assign = DB::select("call recomendationSetter($userid,$timeOfDay)");
//------------store procedure replacement------------------------------


$choosenRecom = DB::select('SELECT DISTINCT
    (recommendation_Id) 
FROM
    recommendation
WHERE
    recommendation.fk_category_Id IN (SELECT 
            fk_category_Id
        FROM
            happier.preferred_categories
        WHERE
            fk_user_Id = ?)
        AND recommendation.timeofday = ?  ORDER BY RAND() LIMIT 0,1', [$userid,$timeOfDay]);

dd($choosenRecom[0]->recommendation_Id);
DB::table('userrecommendation')->insert(
    ['fk_user_Id' => $userid, 'fk_recommendation_Id' => $choosenRecom[0]->recommendation_Id]
);


//DB::insert('insert into userrecommendation (fk_user_Id, fk_recommendation_Id) values (?, ?)', [$userid, $choosenRecom[0]->recommendation_Id]);
//---------------------------------------------------------------------
//*****Succes ! a new recommendation has been assigned *****
//|****************************************|
//New one assigned Notificacion logic here
//<-------------------Push Notification---------------------------------------------------->
$optionBuilder = new OptionsBuilder();
$optionBuilder->setTimeToLive(60*20);
$notificationBuilder = new PayloadNotificationBuilder('Tienes una nueva recomendacion!');
$notificationBuilder->setBody('Text holder')
->setClickAction('ACTIVITY_REC')
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
//|****************************************|
//------------------------------------------------------------------------------------------------------------------------
}else
{//the user its not eligible to get a new recommendation
//*****'The user has not interacted with its last recommendation of the day *****
//|***********************************************************************|
//Interact with your current recommendation please Notificacion logic here
//<-------------------Push Notification---------------------------------------------------->
$optionBuilder = new OptionsBuilder();
$optionBuilder->setTimeToLive(60*20);
$notificationBuilder = new PayloadNotificationBuilder('Aun tienes que interactuar con una recomendacion pendiente!');
$notificationBuilder->setBody('Text holder')
->setClickAction('ACTIVITY_REC')
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
//|***********************************************************************|
}
}
else
{//the user has reached the limit of recommendations that he can get on the day
//*****'The user ran out of recommendations for the day' *****
}  
}
//return nothing




//Exiting



//<---------------------------------------------------------------------------------Big Monster-------------------------------------------------------------------------->

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



}
