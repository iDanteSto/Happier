<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Hash;
use Validator;
use Illuminate\Support\Str;
use JD\Cloudder\Facades\Cloudder;
use DateTime;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Carbon\Carbon;
use App\UserMood;

class recommendationController extends Controller
{

/*
|--------------------------------------------------------------------------
| Recommendation Checker
|--------------------------------------------------------------------------
|
| Checks if the user has interacted with his last recommendation assigned
|
*/
public function recommendationChecker(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$currentDate = date('Y-m-d');
//result 1 = user has a recom to be seen  ,  0 = user has not a recommendation to be seen
$checkerResult;
//------------------------------------------------------------------
//count user recommendations within the current day 
$recomCounter = DB::table('userrecommendation')
->select(DB::raw('count(userRecommendation_Id) as recomCount'))
->where('fk_user_Id', '=', $userid)
->where('fk_status_Id', '=', 2)
//->whereDate('creation_date', $currentDate)
->get();
$recomCount = $recomCounter[0]->recomCount;
//--------------------------------------------------------------

if(!$recomCount == null or !$recomCount == "")
{
$checkerResult =1;

//echo 'Despliega boton';
}else
{
$checkerResult =0;

//echo 'No Despliegues boton';
}
return $checkerResult;
}

/*
|--------------------------------------------------------------------------
| Recommendation Setter
|--------------------------------------------------------------------------
|
| Asignate and retrieve a recomendation to the user by calling an stored procedure
|
*/
public function recommendationSet(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
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
//Mañana
$timeOfDay = 1;   
}else if($currentTime >= $beginEvening && $currentTime <= $endEvening)
{ 
//Tarde
$timeOfDay =2;
}else if($currentTime >= $beginNight && $currentTime <= $endNight)
{
//Noche
$timeOfDay =3;
}
$assign = DB::select("call recomendationSetter($userid,$timeOfDay)");
return response()->json(['succes'=> 'Succes!, the time of the day was '.$timeOfDay], 200);
}

public function recommendationSetter2(Request $request)
{
 /*
    |--------------------------------------------------------------------------
    | Assign recommendation function
    |--------------------------------------------------------------------------
    |
    | This function assignates recommendations to elegible users
    |
    */        
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
if($recomCount < $timesAtDay)
{
//retrieve the number of recommendations of the user that he has not interacted with 
$recomReadyCollection = DB::table('userrecommendation')
->select(DB::raw('count(userRecommendation_Id) as recomCount2'))
->where('fk_user_Id', '=', $userid)
->where('fk_status_Id', '=', 2)
//->whereDate('creation_date', $currentDate)
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
}//Exiting
}


/*
|--------------------------------------------------------------------------
| Recommendation Completer
|--------------------------------------------------------------------------
|
| This function change the current Recommendation to "Completada" status 1
|
*/
public function recommendationComplete(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$userRecommendationID = $request->userRecommendationID;
if($userRecommendationID == null or $userRecommendationID  == "")
{
return 'Error!';
}else
{
DB::update('UPDATE userrecommendation SET fk_status_Id = ? WHERE fk_user_Id = ? and userRecommendation_Id = ?' , [1,$userid,$userRecommendationID]);
return 'Exito!';
}


}
/*
|--------------------------------------------------------------------------
| Recommendation Rejecter
|--------------------------------------------------------------------------
|
| This function change the current Recommendation to "Rechazada" status 3
|
*/
public function recommendationRejecter(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$userRecommendationID = $request->userRecommendationID;
if($userRecommendationID == null or $userRecommendationID  == "")
{
return 'Error!';
}else
{
DB::update('UPDATE userrecommendation SET fk_status_Id = ? WHERE fk_user_Id = ? and userRecommendation_Id = ?' , [3,$userid,$userRecommendationID]);
return 'Exito!';
}

}
/*
|--------------------------------------------------------------------------
| Recommendation Changer
|--------------------------------------------------------------------------
|
| This function change the current Recommendation to "Rechazada" status 3 and assing a new one
|
*/
public function recommendationChanger(Request $request)
{ 
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$userRecommendationID = $request->userRecommendationID;
DB::update('UPDATE userrecommendation SET fk_status_Id = ? WHERE fk_user_Id = ? and userRecommendation_Id = ?' , [3,$userid,$userRecommendationID]);

$result = DB::select("call recomendationSetter($userid,@NOMBRE,@DESCRIPCION,@ID,@CATEGORIA,@DESCIMAGE,@CATIMAGE)");


}

/*
|--------------------------------------------------------------------------
| Recommendation Saver
|--------------------------------------------------------------------------
|
| This function change the current Recommendation to "Guardada" status 4 
|
*/
public function recommendationSaver(Request $request)
{ 
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$recommendationID = $request->recommendationID;
DB::update('UPDATE userrecommendation SET fk_status_Id = ? WHERE fk_user_Id = ? and fk_recommendation_Id = ?' , [4,$userid,$recommendationID]);
}


/*
|--------------------------------------------------------------------------
| Load Recommendation
|--------------------------------------------------------------------------
|
| This function loads  the recommendation
|
*/  
public function recommendationLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 

$recommendationInfo = DB::select('SELECT 
userrecommendation.userRecommendation_Id AS userrecom_Id,
recommendation.name AS recom_name,
recommendation.description AS recom_desc,
recommendation.image AS recom_image,
category.description AS cat_desc,
category.category_Id AS category_Id,
category.image AS cat_image
FROM
recommendation,
userrecommendation,
category
WHERE
userrecommendation.fk_user_Id = ?
AND userrecommendation.fk_recommendation_Id = recommendation.recommendation_Id
AND category.category_Id = recommendation.fk_category_Id
AND userrecommendation.fk_status_Id = ? ORDER BY RAND() limit 0,1', [$userid, 2]);//retrieve peferred_categories from the user


if (count($recommendationInfo)) {
return response()->json(array('recommendation_Info'=>$recommendationInfo));

}else
{
return 'Parece ser que por el momento no hay mas recomendaciones , espera a tu siguiente recomendacion';
}
}
/*
|--------------------------------------------------------------------------
| Load recommendationSmallHistory
|--------------------------------------------------------------------------
|
| This function loads  the last 50 recommendations completed or rejected on the given category
|
*/  
public function recommendationSmallHistory(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();
$userid = $user->user_Id;
$category_Id = $request->category_Id;

$LastRecomms = DB::select('SELECT 
    fk_recommendation_Id,
    recommendation.name,
    recommendation.description,
    recommendation.image,
    creation_date,
    status.description AS status
FROM
    userrecommendation,
    recommendation,
    status
WHERE
    fk_user_Id = ?
        AND fk_recommendation_Id = recommendation.recommendation_Id
        AND recommendation.fk_category_Id = ?
        AND userrecommendation.fk_status_Id IN (1 , 3)
        AND fk_status_Id = status_Id
ORDER BY creation_date DESC
LIMIT 50' , [$userid, $category_Id]);
if (count($LastRecomms)) 
{
return $LastRecomms;
}else
{
return response()->json(['error'=> 'There is no recommendations!']);
}

}


}
