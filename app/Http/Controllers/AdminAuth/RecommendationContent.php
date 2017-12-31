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








//recommendations
public function ShowRecommendations()
{
if(Auth::guard('admin_user')->user())
            {
			return view('admin-auth.recommendations');  		
            }
return redirect('/dashboard');   
}


}
