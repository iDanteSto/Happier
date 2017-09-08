<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use DateTime;

class moodController extends Controller
{
//
/*
|--------------------------------------------------------------------------
| Mood Checker
|--------------------------------------------------------------------------
|
| Checks if the user has interacted with his last mood assigned
|
*/
public function moodChecker(Request $request)
{     
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$currentDate = date('Y-m-d');
//result 1 = user has a recom to be seen  ,  0 = user has not a recommendation to be seen
$checkermoodResult;
//------------------------------------------------------------------
//count user recommendations within the current day 
$moodCounter = DB::table('usermood')
->select(DB::raw('count(userMood_Id) as moodCount'))
->where('fk_user_Id', '=', $userid)
->where('fk_status', '=', 2)
//->whereDate('creation_date', $currentDate)
->get();
$moodCount = $moodCounter[0]->moodCount;
//--------------------------------------------------------------
if(!$moodCount == null or !$moodCount == "")
{
$checkermoodResult =1;

//echo 'Despliega boton';
}else
{
$checkermoodResult =0;

//echo 'No Despliegues boton';
}
return $checkermoodResult;
//return 'Succes holder creado!';
}
/*
|--------------------------------------------------------------------------
| Mood Setter
|--------------------------------------------------------------------------
|
| Assign  a mood holder 
|
*/
public function moodSetter(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;


DB::table('usermood')->insert(
['fk_user_Id' => $userid]
);
return response()->json(['succes'=> 'Se creo el holder!'], 200);
}
/*
|--------------------------------------------------------------------------
| Mood Loader
|--------------------------------------------------------------------------
|
| Loads  user last assigned mood to be modified
|
*/
public function moodLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;

$moodInfo = DB::select('SELECT 
userMood_Id AS moodId
FROM
usermood
WHERE
fk_user_Id = ? AND fk_status = ?
ORDER BY RAND()
LIMIT 0 , 1;', [$userid, 2]);//retrieve peferred_categories from the user


if (count($moodInfo)) {
return $moodInfo;

}else
{
return 'No hay!';
}
//return response()->json(['succes'=> 'Hello World!'], 200);
}
/*
|--------------------------------------------------------------------------
| Mood Completer
|--------------------------------------------------------------------------
|
| Updates the designated holder with the value of valoration given by the user
|
*/
public function moodCompleter(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$moodId = $request->moodId;
$moodValoration =$request->moodValoration;

DB::table('usermood')
->where('userMood_Id', $moodId)
->update(array('mood' => $moodValoration , 'fk_status' => 1));
return response()->json(['succes'=> 'Update Succesful!'], 200);
}

}
