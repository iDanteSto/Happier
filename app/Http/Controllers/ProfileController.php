<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Hash;
use Validator;
use Illuminate\Support\Str;
use JD\Cloudder\Facades\Cloudder;

class ProfileController extends Controller
{
    
public function avatarSetter(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$newImagelink = $request->imagelink;


DB::table('users')
            ->where('user_Id', $userid)
            ->update(array('imagelink' => $newImagelink));

return response()->json(['succes!'=> 'Se cambio el avatar!'], 200);

/*  --------------------------------NOT GONNA BE USED-------------------------------------------------------------------------------------
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;
$oldImgUrl = $user->userImage;
$filename = $request->filename;
$randomgen = Str::random(5);
$publicId = $userid . 'profilepic' .$randomgen;
$finder = substr(strrchr($oldImgUrl, '/'), 1 );
$fmfinder = strstr($finder, '.', true); 

list($width, $height, $type, $attributes) = getimagesize($filename);
$weight = filesize ($filename);

Cloudder::upload($filename, $publicId);
$si = Cloudder::getResult();

if($fmfinder != 'defaultPerson_fuyg1y' )
{
Cloudder::destroyImage($fmfinder);
Cloudder::delete($fmfinder);
}

DB::table('users')
            ->where('email', $request->email)
            ->update(['userImage' => $si['url']]);

$user = User::where('email', '=', $request->email)->firstOrFail();

 
return response()->json(array('usuario'=>$user->nickname,'imagen'=>$user->userImage,'width'=>$width,'height'=>$height,'weight'=>$weight));
--------------------------------NOT GONNA BE USED-------------------------------------------------------------------------------------*/
}
                                           /*
                                          |--------------------------------------------------------------------------
                                          | avatar Loader
                                          |--------------------------------------------------------------------------
                                          |
                                          | Loads all the possibles avatars that  the user can have
                                          |
                                          */
public function avatarLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;


/*
			DB::table('')
            ->where('email', $request->email)
            ->update(['userImage' => $si['url']]);
            */
 $avatarsInfo = DB::select('SELECT 
    avatar.avatar_Id,
    avatar.name,
    avatar.link
FROM
    avatar,
    avatar_permission
WHERE
    avatar_permission.fk_user_Id = ?
        AND fk_avatar_Id = avatar_Id',[$userid]);

return $avatarsInfo;

//return response()->json(array('id'=>$avatarsInfo->avatar_Id,'name'=>$avatarsInfo->name,'link'=>$avatarsInfo->link,));


}


/*test area -------------------------------------------------------------------------------------------------------------------*/
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
//result 1 = user can receive a new recommendation   ,  0 = user has not interacted with its last interaction
$moodCheckerResult;
$moodChecker = DB::select('SELECT distinct(userMood_Id) from usermood where fk_user_Id = ? and fk_status = ?;', [$userid, 2]);

if($moodChecker == null or $moodChecker == "")
{
$moodCheckerResult =1;
echo 'Se le puede enviar ';
}else
{
$moodCheckerResult =0;
echo 'No se le puede enviar ';
}
return $moodCheckerResult;

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

/*test area -------------------------------------------------------------------------------------------------------------------*/


                                           /*
                                          |--------------------------------------------------------------------------
                                          | Individual Summary Loader
                                          |--------------------------------------------------------------------------
                                          |
                                          | Loads the data of user recommendations 
                                          |
                                          */
public function summaryLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//needed parameter obtained with user email hidden
$category =$request->category;//needed parameter
//Assigned recommendations
$asignadas = DB::select('SELECT 
    count(fk_recommendation_Id) as Contador
FROM
    userrecommendation,
    recommendation
WHERE
    fk_user_Id = ?
        AND fk_recommendation_Id = recommendation.recommendation_Id
        AND recommendation.fk_category_Id = ?' , [$userid,$category]);
//Assigned recommendations to a local variable
$asign = $asignadas[0]->Contador;
//if there is no assigns or the category does not exist it will return a null
if($asign>0){
//Completed recommendations
$completas = DB::select('SELECT 
    count(fk_recommendation_Id) as Contador2
FROM
    userrecommendation,
    recommendation
WHERE
    fk_user_Id = ?
        AND fk_recommendation_Id = recommendation.recommendation_Id
        AND recommendation.fk_category_Id = ?
        AND fk_status_Id = ?' , [$userid,$category,1]);
//Completed recommendations to a local variable
$compl = $completas[0]->Contador2;
//Percentage obtained with a formula 
$porcentaje = ($compl * 100)/$asign ;
//transform to int
$porcint = (int)$porcentaje;
//return all the data
return response()->json(array('asignadas'=>$asign,'completas'=>$compl,'porcentaje' =>$porcint));
}else
{ 
 return response()->json(array('asignadas'=>0,'completas'=>0,'porcentaje' =>0));
}//if there is nothing , return nothing
}



public function userRecomsHistoryLoader(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//needed parameter obtained with user email hidden
$category =$request->category;//needed parameter


$allRecoms = DB::select('SELECT 
    fk_recommendation_Id,
    recommendation.name,
    recommendation.description,
    recommendation.image,
    creation_date,
    status.description as status
FROM
    userrecommendation,
    recommendation,
    status
WHERE
    fk_user_Id = ?
        AND fk_recommendation_Id = recommendation.recommendation_Id
        AND recommendation.fk_category_Id = ?
        AND fk_status_Id = status_Id order by creation_date desc' , [$userid,$category]);

if (count($allRecoms)) 
{
  return $allRecoms;
}else
{
  return response()->json(['error'=> 'There was an error']);
}


}







}
