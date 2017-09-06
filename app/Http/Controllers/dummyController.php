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

$optionBuilder = new OptionsBuilder();
$optionBuilder->setTimeToLive(60*20);

$notificationBuilder = new PayloadNotificationBuilder('Laravel say hi!');
$notificationBuilder->setBody('Hello world')
				    ->setSound('default');
				    
$dataBuilder = new PayloadDataBuilder();
$dataBuilder->addData(['a_data' => 'my_data']);

$option = $optionBuilder->build();
$notification = $notificationBuilder->build();
$data = $dataBuilder->build();

$token = "d5xhIvkF6Bw:APA91bFPdRyVa5fdCf0VUpdCF4QKMelPuNZSCoSyfTsCM79NsiOhXiozKhLOOZ9i2QlID2S8Q40D1vSSt6gMseCfCzsta5rnId_aPr9n2N4LHF4zk9sOuR44p1kMJvIZSBw1OXh2MiHd";

$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

$downstreamResponse->numberSuccess();
$downstreamResponse->numberFailure();
$downstreamResponse->numberModification();

//return Array - you must remove all this tokens in your database
$downstreamResponse->tokensToDelete(); 

//return Array (key : oldToken, value : new token - you must change the token in your database )
//$downstreamResponse->tokensToModify(); 

//return Array - you should try to resend the message to the tokens in the array
//$downstreamResponse->tokensToRetry();





return 'dummy function :D';
    }






}
