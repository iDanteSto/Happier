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





    public function dummyFunction0(Request $request)
    {      
        //array of all users that has schedulables recommendations
        $users = DB::select('
        SELECT 
            user_Id, devicetoken, displayname
        FROM
            users
        WHERE
            status = ?
        AND user_Id IN (SELECT 
            fk_user_Id
        FROM
            userrecommendation
        WHERE
            fk_status_Id = ?)', [2,5]);
        //$users = User::where('status', '=', 2)->get();
        if (count($users))
        {
            //for every user , do the logic
            foreach ($users as $user) 
            {
                $userid = 451;
                $userDeviceToken = $user->devicetoken;
                $username = $user->displayname;
                $todayDate = Carbon::now();
                $scheduledDate = DB::table('userrecommendation')
                ->where('fk_user_Id', $userid)
                ->where('fk_status_Id', 5)
                ->where('schedule_date', $todayDate)
                ->pluck('schedule_date')->first();
                dd($scheduledDate);
                if (count($scheduledDate)) 
                {   
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60*20);
                    $notificationBuilder = new PayloadNotificationBuilder('Aviso Fecha Agendada Recomendacion');
                    $notificationBuilder->setBody($username.'Estas listo para completar esta recomendacion?')
                    ->setClickAction('ACTIVITY_REC')
                    ->setSound('default');
                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['scheduleRecomOn' => 'yes']);
                    $option = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data = $dataBuilder->build();
                    $token = $userDeviceToken;
                    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();
                    $downstreamResponse->numberModification();
                }
                else
                {
                    //return "Did  nothing!";
                } 
            }//close foreach
        }//close if   
    }

}
