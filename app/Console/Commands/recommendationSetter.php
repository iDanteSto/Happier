<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use DateTime;
use Carbon\Carbon;
use App\UserMood;



class recommendationSetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */       //namespace of the command [*_*]
    protected $signature = 'recommendation:setter';

    /**
     * The console command description.
     *
     * @var string
     */             //description of the command [*_*]
    protected $description = 'Checks if the user can receive a new recommendation , if he can it will be assignate a new one';

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
        //logic to do [*_*]----------------------------------------------------------------------------------------------------------------------------------------
 /*
    |--------------------------------------------------------------------------
    | Assign recommendation function
    |--------------------------------------------------------------------------
    |
    | This function assignates recommendations to elegible users
    |
    */        

//<---------------------------------------------------------------------------------Big Monster-------------------------------------------------------------------------->
//select all users that are verified and has
$users = DB::select('SELECT DISTINCT
users.user_Id, frequency.timesAtDay, users.devicetoken
FROM
users,
frequency,
userfrequency
WHERE
userfrequency.fk_frequency_Id = frequency.frequency_Id
AND users.status = ?
AND fk_user_Id = user_Id
AND devicetoken != ?', [2,""]);
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

//dd($choosenRecom[0]->recommendation_Id);
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





}else
{
//Do nothing

}



//Exiting



//<---------------------------------------------------------------------------------Big Monster-------------------------------------------------------------------------->
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
}
