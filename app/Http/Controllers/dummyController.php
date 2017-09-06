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


class dummyController extends Controller
{
    





public function dummyFunction(Request $request)
    {     
 
$user = User::where('email', '=', $request->email)->firstOrFail();
$userdeviceToken = $user->devicetoken;
//<-------------------Push Notification---------------------------------------------------->
$optionBuilder = new OptionsBuilder();
$optionBuilder->setTimeToLive(60*20);
$notificationBuilder = new PayloadNotificationBuilder('Tienes una nueva recomendacion!');
$notificationBuilder->setBody('Text holder')
				    ->setSound('default')
				    ->setClickAction('ACTIVITY_REC');
$dataBuilder = new PayloadDataBuilder();
$dataBuilder->addData(['a_data' => 'my_data']);
$option = $optionBuilder->build();
$notification = $notificationBuilder->build();
$data = $dataBuilder->build();
$token = $userdeviceToken;
$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
$downstreamResponse->numberSuccess();
$downstreamResponse->numberFailure();
$downstreamResponse->numberModification();
//<-------------------Push Notification---------------------------------------------------->



//return Array - you must remove all this tokens in your database
//$ekizde = $downstreamResponse->tokensToDelete(); 

//return Array (key : oldToken, value : new token - you must change the token in your database )
//$downstreamResponse->tokensToModify(); 

//return Array - you should try to resend the message to the tokens in the array
//$downstreamResponse->tokensToRetry();



/*


*/
return 'Succes!';
    }

public function dummyFunction2(Request $request)
    {   
//<---------------------------------------------------------------------------------Big Monster-------------------------------------------------------------------------->
//select all users that are verified
    $users = DB::select('SELECT 
        users.user_Id, frequency.timesAtDay, users.devicetoken
        FROM
        users,
        frequency,
        userfrequency
        WHERE
        userfrequency.fk_frequency_Id = frequency.frequency_Id
        AND users.status = ?
        AND fk_user_Id = user_Id
        AND devicetoken != ?', [1,""]);
//if there is no confirmed users
if (count($users))
{
//for every user , do the logic
    foreach ($users as $usuarios) 
    {
        $currentDate = date('Y-m-d');
        $userid = $usuarios->user_Id;
        $userDeviceToken = $usuarios->devicetoken;
        $timesAtDay = $usuarios->timesAtDay;
//count user recommendations within the current day
        $recomCountCollection = DB::table('userrecommendation')
        ->select(DB::raw('count(userRecommendation_Id) as recomCount'))
        ->where('fk_user_Id', '=', $userid)
        ->whereDate('creation_date', $currentDate)
        ->get();
        $recomCount = $recomCountCollection[0]->recomCount;
//compare the recomCount against the user frequency chosen to see if he can receive a new recommendation on the day
        if($recomCount < $timesAtDay)
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
    $currentTime = DateTime::createFromFormat('H:i a',date("h:i:sa"));
//Morning
    $beginMorning = DateTime::createFromFormat('H:i a', "8:00 am");
    $endMorning = DateTime::createFromFormat('H:i a', "12:59 pm");
//Evening
    $beginEvening = DateTime::createFromFormat('H:i a', "1:00 pm");
    $endEvening = DateTime::createFromFormat('H:i a', "5:59 pm");
//Night
    $beginNight = DateTime::createFromFormat('H:i a', "6:00 pm");
    $endNight = DateTime::createFromFormat('H:i a', "9:00 pm");
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
  $assign = DB::select("call recomendationSetter($userid,$timeOfDay)");
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
//<---------------------------------------------------------------------------------Big Monster-------------------------------------------------------------------------->
   



}else
{
	//Do nothing
	return 'nothing';
}



//Exiting




    }




}
