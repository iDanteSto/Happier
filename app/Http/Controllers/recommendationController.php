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
//result 1 = user can receive a new recommendation   ,  0 = user has not interacted with its last interaction
$checkerResult;
$recomCounter = DB::select('SELECT 
    distinct(userRecommendation_Id)
FROM
    happier.userrecommendation
WHERE
    fk_user_Id = ? AND fk_status_Id = ?;', [$userid, 2]);

if($recomCounter == null or $recomCounter == "")
{
$checkerResult =1;
echo 'Se le puede enviar ';
}else
{
$checkerResult =0;
echo 'No se le puede enviar ';
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
$recommendationID = $request->recommendationID;
DB::update('UPDATE userrecommendation SET fk_status_Id = ? WHERE fk_user_Id = ? and fk_recommendation_Id = ?' , [1,$userid,$recommendationID]);
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
$recommendationID = $request->recommendationID;
DB::update('UPDATE userrecommendation SET fk_status_Id = ? WHERE fk_user_Id = ? and fk_recommendation_Id = ?' , [3,$userid,$recommendationID]);
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
$recommendationID = $request->recommendationID;
DB::update('UPDATE userrecommendation SET fk_status_Id = ? WHERE fk_user_Id = ? and fk_recommendation_Id = ?' , [3,$userid,$recommendationID]);

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
    recommendation.recommendation_Id AS recom_Id,
    recommendation.name AS recom_name,
    recommendation.description AS recom_desc,
    recommendation.image AS recom_image,
    category.description AS cat_desc,
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
  return 'Parece ser que por el momento no hay mas recomendaciones , espera a tu siguiente recomendacion :D';
}
}
}
