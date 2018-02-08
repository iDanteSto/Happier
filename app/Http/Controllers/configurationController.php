<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Hash;
use Validator;
use Illuminate\Support\Str;
use JD\Cloudder\Facades\Cloudder;
use App\UserHibernation;
use Carbon\Carbon;

class configurationController extends Controller
{
/*
|--------------------------------------------------------------------------
| Load Categories and frequency
|--------------------------------------------------------------------------
|
| This function loads all the preferred categories and frequency from the user
|
*/  
public function userCategsAndFrequencyLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$userPrefcategs = DB::select('SELECT fk_category_Id AS PREFCATEG FROM happier.preferred_categories where fk_user_Id = ?', [$userid]);//retrieve peferred_categories from the user
$userfrequency = DB::select('SELECT timesAtDay, description from frequency , userfrequency where fk_user_Id = ? and fk_frequency_Id = frequency_Id', [$userid]);//retrieve frequency.timesatday of user 
return response()->json(array('CategoriasPreferidas'=>$userPrefcategs,'freqDesc'=>$userfrequency[0]->description));
//maybe v 
//return redirect()->route('routeToApp' ,["email" =>$email,"changeToken" => $changeToken]);
}
/*
|--------------------------------------------------------------------------
| Set the user preferred categories and frequency
|--------------------------------------------------------------------------
|
| This function sets the choosen preferred categories  and frequency retrieved from the app
|
*/
public function userCategsAndFrequencySetter(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$arrayLenght = (count($request->categs))-1;
DB::delete('DELETE FROM preferred_categories WHERE fk_user_Id = ?' , [$userid]);
//echo 'Succes!';
//$result = DB::select("call preferredCategoriesSetter($userid,$arrayComplete,$arrayLenght)");
for($x=0;$x<=$arrayLenght;$x++)
{
DB::insert('insert into preferred_categories (fk_user_Id, fk_category_Id) values (?, ?)', [$userid, $request->categs[$x]]);
//echo 'la categoria '.$x.' es '.$request->categs[$x] ;
}
DB::table('userfrequency')
->where('fk_user_Id', $userid)
->update(['fk_frequency_Id' => $request->frequency_Id]);
}
/*
|--------------------------------------------------------------------------
| Creates hibernation state
|--------------------------------------------------------------------------
|
| This function puts the user on hibernation state 3
|
*/
public function hibernateUser(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
$durationName = $request->durationName;
$durationNumber = $request->durationNumber;
$currentDate = Carbon::now()->format('Y-m-d');;

//create or update hibernation for user
$createhibernateStatus = UserHibernation::updateOrCreate([
	//where
    'fk_user_Id'   => $userid,]
,
[
	//data to be updated or inserted
    'name'     => $durationName,
    'duration' => $durationNumber,
    'creation_date'     => $currentDate
]);
//update status hibernation on users table
DB::table('users')
->where('user_Id', $userid)
->update(['status' => 3]);



return $userid;
}
/*
|--------------------------------------------------------------------------
| Cancel hibernation state
|--------------------------------------------------------------------------
|
| This function puts the user on normal 2 state
|
*/
public function hibernateCancel(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 

//delete userhibernation on DB
DB::table('userhibernation')->where('fk_user_Id', '=', $userid)->delete();
//update status to 2 on DB
DB::table('users')
->where('user_Id', $userid)
->update(['status' => 2]);

}



}
