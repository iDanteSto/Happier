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
  if has fk_status_Id = 2 pending 
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
//->where('fk_status_Id', '=', 5)
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
| Recommendation Saver
|--------------------------------------------------------------------------
|
| This function change the current Recommendation to "Guardada" status 6 and saves the new schedule date
|
*/

    public function recommendationSaver(Request $request)
    { 
        
        $user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
        $userid = $user->user_Id;
        $userRecommendationID = $request->userRecommendationID;
        $days = $request->days;

        //update info date
        $DatetoUpdate = date("Y/m/d");
        //schedule date
        $mytime = Carbon::now();
        $ScheduleDate = $mytime->addDays($days)->toDateTimeString();


        if($userRecommendationID == null or $userRecommendationID  == "")
        {
                return 'Error!';
        }else
        {
                DB::table('userrecommendation')
                ->where('fk_user_Id', $userid)
                ->where('fk_recommendation_Id', $userRecommendationID)
                ->update(['fk_status_Id' => 5, 'schedule_date' => $ScheduleDate,'updated_at' => $DatetoUpdate]);
            
                return 'Exito!';
        }
        //DB::update('UPDATE userrecommendation SET fk_status_Id = ? WHERE fk_user_Id = ? and fk_recommendation_Id = ?' , [6,$userid,$recommendationID]);
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
    FK_TYPE AS recomm_type,
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
