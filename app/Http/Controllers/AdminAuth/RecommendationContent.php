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
$filename = $request->file;
$randomgen = Str::random(5);
$publicId = $request->description.$randomgen;
Cloudder::upload($filename, $publicId);
$arrayOfImageData=Cloudder::getResult();
//Function to format the url and remove ""
//$urlFormatted = str_replace(array('"'), null,$arrayOfImageData['url']);
//dd($urlFormatted);
//--------------------------------------------
//insert on DB--------------------------------
DB::table('category')->insert(
    ['description' => $request->description,'image' => $arrayOfImageData['url']]
);
//--------------------------------------------
return redirect('/recommendation_categories');
}
public function DeleteCateg($id)
{
	
}
public function EditCateg($id)
{
	
}
public function UpdateCateg($id)
{
	
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
