<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Hash;
use Validator;
use Illuminate\Support\Str;
use JD\Cloudder\Facades\Cloudder;

class meditationController extends Controller
{   
/*
|--------------------------------------------------------------------------
| Meditation Setter
|--------------------------------------------------------------------------
|
| This function calls an stored procedure to set a random meditation to the user
|
*/
public function meditationSet(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
//$max = DB::table('recommendation')->count(DB::raw('recommendation_Id'));//counts all the existing recommendations on the database to get the max
//$holder = rand(1, $max); //function of randomize numbers from 1 to max(max number of recommendations on the DB)
$result = DB::select("call meditationSetter($userid,@NOMBRE,@DESCRIPCION,@ID,@MEDIA)");
$results = DB::select('select @NOMBRE as NOMBRE, @DESCRIPCION as DESCRIPCION,@ID as ID,@MEDIA as MEDIA');
return response()->json(array('ID'=>$results[0]->ID,'NOMBRE'=>$results[0]->NOMBRE ,'DESCRIPCION'=>$results[0]->DESCRIPCION,'MEDIA'=>$results[0]->MEDIA));
}
/*
|--------------------------------------------------------------------------
| Meditation Completer
|--------------------------------------------------------------------------
|
| This function change the current meditation to "Completada" status 1
|
*/
public function meditationComplete(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$meditationID = $request->meditationID;
DB::update('UPDATE usermeditation SET fk_status_Id = ? WHERE fk_user_Id = ? and fk_meditation_Id = ?' , [1,$userid,$meditationID]);
}
/*
|--------------------------------------------------------------------------
| Meditation Loader
|--------------------------------------------------------------------------
|
| This function Loads meditation of status 2
|
*/
public function meditationLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$meditationInfo = DB::select('SELECT 
meditation.meditation_Id,
meditation.name,
meditation.description,
meditation.media
FROM
meditation,
usermeditation
WHERE
meditation.meditation_Id = usermeditation.fk_meditation_Id
AND usermeditation.fk_user_Id = ?
AND usermeditation.fk_status_Id = ? ORDER BY RAND() limit 0,1', [$userid, 2]);//retrieve peferred_categories from the user
if (count($meditationInfo)) {
return response()->json(array('meditation_Info'=>$meditationInfo));
}else
{
return 'Parece ser que por el momento no hay mas meditaciones , espera a tu siguiente meditacion :D';
}
}
}
