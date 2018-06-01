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
        dd("test");
    }



}
