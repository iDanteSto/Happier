<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\AdminUser;
use Hash;
use DB;
use Illuminate\Support\Str;
use JD\Cloudder\Facades\Cloudder;
use View;

class RecommendationContent extends Controller
{
    
//recommendations categs----------------------------------------------------------------------------------------------------------------------------------
public function ShowCategs()
{
if(Auth::guard('admin_user')->user())
{//if there is admin logged in
$availableCategories ['availableCategories'] = DB::table('category')->get();
$checkIfEditable ['checkIfEditable'] = DB::select('SELECT distinct(fk_category_Id) from recommendation');
return view('admin-auth.recommendation_categories',$availableCategories,$checkIfEditable);
}//if there is no admin logged in
return redirect('/dashboard');   
}


public function CreateCateg(Request $request)
{
//insert on cloudinary-------------------------	
	/*
$filename = $request->file;
$randomgen = Str::random(5);
$removeSpaces= str_replace(array(' '), null,$request->description.$randomgen);
$publicId = $removeSpaces;
Cloudder::upload($filename, $publicId);
$arrayOfImageData=Cloudder::getResult();*/
//Function to format the url and remove ""
//$urlFormatted = str_replace(array('"'), null,$arrayOfImageData['url']);
//dd($urlFormatted);
//--------------------------------------------
//insert on DB--------------------------------
$InsertCateg = DB::table('category')->insert(
    ['description' => $request->description,'image' => "holder"]
);
$idOfCateg = DB::getPdo()->lastInsertId();
$filename = $request->file;
$publicId = "categ_image".$idOfCateg;
Cloudder::upload($filename, $publicId);
$arrayOfImageData=Cloudder::getResult();
DB::table('category')
->where('category_Id', $idOfCateg)
->update(array('description' => $request->description,'image' => $arrayOfImageData['url']));
//--------------------------------------------
return redirect('/recommendation_categories');
}
public function DeleteCateg($id)
{
$categoryInfo = DB::table('category')->where('category_Id', $id)->get();
$slice1 = str_after($categoryInfo[0]->image, 'v');
$slice2 = str_after($slice1, '/');
$firstswap = strrev($slice2);
$slice3 = str_after($firstswap, '.');
$secondswap = strrev($slice3);
$publicId = $secondswap;
Cloudder::delete($publicId);
DB::table('category')->where('category_Id', '=', $id)->delete();
return redirect('/recommendation_categories');   	
}
public function EditCateg($id)
{
$InfoCateg ['InfoCateg'] = DB::table('category')->where('category_Id', $id)->get();
//$InfoCateg = DB::table('avatar_categories')->where('avatar_categories_Id', $id)->get();
//dd($id);
return view('admin-auth.recommendation_categories_edit', $InfoCateg);	
}
public function UpdateCateg(Request $request)
{/*
$categoryInfo = DB::table('category')->where('category_Id', $request->id)->get();
$slice1 = str_after($categoryInfo[0]->image, 'v');
$slice2 = str_after($slice1, '/');
$firstswap = strrev($slice2);
$slice3 = str_after($firstswap, '.');
$secondswap = strrev($slice3);
$publicId = $secondswap;
$randomgen = Str::random(5);
$removeSpaces= str_replace(array(' '), null,$request->description.$randomgen);
$toPublicId = $removeSpaces;
Cloudder::rename($publicId, $toPublicId);
*/
DB::table('category')
->where('category_Id', $request->id)
->update(array('description' => $request->description));
//----------------------------------------------------------
return redirect('/recommendation_categories');  	
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------

//recommendations----------------------------------------------------------------------------------------------------------------------------------------
public function ShowRecommendations()
{
if(Auth::guard('admin_user')->user())
{//if there is admin logged in
$availableRecommendations ['availableRecommendations'] = DB::table('recommendation')->get();
$availableCategories ['availableCategories'] = DB::table('category')->get();
$availableTimesofDay ['availableTimesofDay'] = DB::table('timesofday')->get();
//$checkIfEditable ['checkIfEditable'] = DB::select('SELECT distinct(fk_category_Id) from recommendation');
//return view('admin-auth.recommendations',$availableRecommendations,$availableCategories,$availableFrequencies);
return View::make('admin-auth.recommendations')->with($availableRecommendations)->with($availableCategories)->with($availableTimesofDay);
}//if there is no admin logged in
return redirect('/dashboard');   
}

public function CreateRecommendation(Request $request)
{
//insert on cloudinary-------------------------	
	/*
$filename = $request->file;
$randomgen = Str::random(5);
$removeSpaces= str_replace(array(' '), null,$request->description.$randomgen);
$publicId = $removeSpaces;
Cloudder::upload($filename, $publicId);
$arrayOfImageData=Cloudder::getResult();*/
//Function to format the url and remove ""
//$urlFormatted = str_replace(array('"'), null,$arrayOfImageData['url']);
//dd($urlFormatted);
//--------------------------------------------
//insert on DB--------------------------------
$InsertRecomm = DB::table('recommendation')->insert(
    ['name' => $request->name,'description' => $request->description,'fk_category_Id' => $request->fkCategId,'image' => "holder",'timeofday' => $request->TimesofDay_Id]
);
$idOfRecomm = DB::getPdo()->lastInsertId();
$filename = $request->file;
$publicId = "recomm_image".$idOfRecomm;
Cloudder::upload($filename, $publicId);
$arrayOfImageData=Cloudder::getResult();
DB::table('recommendation')
->where('recommendation_Id', $idOfRecomm)
->update(array('image' => $arrayOfImageData['url']));
//--------------------------------------------
return redirect('/recommendations');
}
public function DeleteRecommendation($id)
{
$RecommendationInfo = DB::table('recommendation')->where('recommendation_Id', $id)->get();
$slice1 = str_after($RecommendationInfo[0]->image, 'v');
$slice2 = str_after($slice1, '/');
$firstswap = strrev($slice2);
$slice3 = str_after($firstswap, '.');
$secondswap = strrev($slice3);
$publicId = $secondswap;
Cloudder::delete($publicId);
DB::table('recommendation')->where('recommendation_Id', '=', $id)->delete();
return redirect('/recommendations');   	
}
public function EditRecommendation($id)
{
$availableCategories ['availableCategories'] = DB::table('category')->get();	
$recommendationInfo ['recommendationInfo'] = DB::table('recommendation')->where('recommendation_Id', $id)->get();
return view('admin-auth.recommendations_edit', $availableCategories,$recommendationInfo);
}
public function UpdateRecommendation(Request $request)
{/*
//--------------------Delete from cloudinary-----------------
/*	
$AvatarInfo = DB::table('avatar')->where('avatar_Id', $request->avatar_id)->get();
$slice1 = str_after($AvatarInfo[0]->link, 'v');
$slice2 = str_after($slice1, '/');
$firstswap = strrev($slice2);
$slice3 = str_after($firstswap, '.');
$secondswap = strrev($slice3);
$publicId = $secondswap;
Cloudder::delete($publicId);
//-----------------------------------------------------------
*/
/*
//--------------------upload to cloudinary-------------------
$filename = $request->file;
$randomgen = Str::random(5);
$publicId = $request->name.$randomgen;
Cloudder::upload($filename, $publicId);
//obtain info of cloudinary img-----------------------------
$arrayOfImageData=Cloudder::getResult();
//----------------------------------------------------------
*/
//--------------------update on DB--------------------------
DB::table('recommendation')
->where('recommendation_Id', $request->recommendation_Id)
->update(array('name' => $request->name,'description' => $request->description,'fk_category_Id' => $request->fkCategId));
//----------------------------------------------------------
return redirect('/recommendations');  
}





//-------------------------------------------------------------------------------------------------------------------------------------------------------
}
