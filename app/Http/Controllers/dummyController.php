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
				    ->setSound('default');
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




return 'Succes!';
    }






}
