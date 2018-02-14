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
use Tymon\JWTAuth\Facades\JWTAuth;
use Hash;
use Validator;
use Illuminate\Support\Str;
use Mail;
use App\Mail\verifyEmail;
use App\Mail\recoveryEmail;
use App\Http\Controllers\Config;
//use App\Http\Controllers\Socialite;
use Socialite;
use App\socialProvider;
use App\socialProviders;
use Illuminate\Support\Facades\Route;
use View;

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
//$user = User::where('user_Id', '=', 326)->get();
$user = User::where('user_Id', '=', 326)->firstOrFail();
//if there is no confirmed users


//for every user , do the logic
  
$userid = $user->user_Id;
$userDeviceToken = $user->devicetoken;

//$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
//$userid = $user->user_Id;//place id on a variable to use it 
$minDate = Carbon::now()->startOfWeek()->format('Y-m-d');
$maxDate = Carbon::now()->endOfWeek()->format('Y-m-d');
//$currentDate        = "2018-03-28";
//$currentDate = Carbon::now()->format('Y-m-d');

//return "Fecha ahora " .$currentDate . " El inicio de semana es " .$minDate . "El fin de la semana es " . $maxDate; 

$checkToSendNotification = UserMood::where('fk_user_Id', '=', $userid)
->whereBetween('created_at', [$minDate, $maxDate])
->first();

//dd($checkToSendNotification);

if (count($checkToSendNotification)) 
{
//return "Did  nothing!";
    return 'no';
}
else
{
//return "Notification Sent!";
    return 'si';
} 


    } 



//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------Dummy functions to test recommendationSetter commands------------------------------------------------------------------------
public function dummyFunction0(Request $request)
{   
//array of all users
$users = User::where('status', '=', 2)
->orWhere('status','=',3)
->get();

if (!count($users))
{
//Do nothing because there is no users
}else
{
foreach ($users as $user) 
{
//Declare variable with collection information
$userid = $user->user_Id;
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
$length = $end->diffInDays($now)+1; 
dd($length);
//we want to change the status to ignored if it has 3 days
if($length >= 3)
{
//Update all(for secutiry reasons all , not only the obtained) pending recommendations to status 4 ignored        
DB::table('userrecommendation')
          ->where('fk_status_Id', 2)
          ->where('fk_user_Id', $userid)
          ->update(['fk_status_Id' => 4]);  
}else
{
//It has less than 3 days so it wont do anything    
}
}                     
//------------------------------------------------------------------------------
}//end for each
}//end if to see if there is users     
}

public static function getWeekDates($date, $start_date, $end_date)
{
    $week =  date('W', strtotime($date));
    $year =  date('Y', strtotime($date));
    $from = date("Y-m-d", strtotime("{$year}-W{$week}+1"));
    if($from < $start_date) $from = $start_date;

    $to = date("Y-m-d", strtotime("{$year}-W{$week}-6")); 
    if($to > $end_date) $to = $end_date;

$array1 = array(
        "ssdate" => $from,
        "eedate" => $to,
);

return $array1;

   //echo "Start Date-->".$from."End Date -->".$to;
   


}



public function dummyFunction(Request $request)
{   
//array of all users
$users = User::where('status', '=', 2)
->orWhere('status','=',3)
->get();

return $users;
if (!count($users))
{
//Do nothing because there is no users
}else
{
foreach ($users as $user) 
{
//Declare variable with collection information
$userid = $user->user_Id;
//return $users;
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
if($length >= 3)
{
//Update all(for secutiry reasons all , not only the obtained) pending recommendations to status 4 ignored        
DB::table('userrecommendation')
          ->where('fk_status_Id', 2)
          ->where('fk_user_Id', $userid)
          ->update(['fk_status_Id' => 4]);  
}else
{
//It has less than 3 days so it wont do anything    
}
}                     
//------------------------------------------------------------------------------
}//end for each
}//end if to see if there is users   
    
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
$timeOfDay =3;
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

//dd($currentTime);
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
