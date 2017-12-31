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

class AvatarContent extends Controller
{
    

//Avatars_categories content------------------------------------------------------------------------------------------------------------
public function showAvatarContent()
    {
            if(Auth::guard('admin_user')->user())
            {/*
						$availableCategories = DB::table('avatar_categories')
						->select(DB::raw('SELECT avatar_categories_Id , name , description FROM avatar_categories'))
						->get();
*/

						/*$availableCategories = DB::table('avatar_categories')->get();*/
$availableCategories ['availableCategories'] = DB::table('avatar_categories')->get();

$checkIfEditable ['checkIfEditable'] = DB::select('SELECT distinct(fk_avatar_categories_Id) from avatar');
//$checkIfEditable ['checkIfEditable'] = DB::table('avatar')->distinct('fk_avatar_categories_Id')->get();
//dd($checkIfEditable);
//dd($checkIfEditable);
//test
						//-----------------
						if(count($availableCategories)>0)
						{
							return view('admin-auth.avatar_categories', $availableCategories,$checkIfEditable);
						}else
						{
							return view('admin-auth.avatar_categories');
						}

                		
            }
            return redirect('/dashboard');

    }


public function registerCategory(Request $request)
{
DB::table('avatar_categories')->insert(
['name' => $request->input('name'), 
'description' => $request->input('description')]
);
return redirect('/avatar_categories');           
}


public function editCategory($id)
{
	/*
DB::table('avatar_categories')
            ->where('id', 1)
            ->update(['votes' => 1]);
return redirect('/avatar_categories');    
*/
//$idtest = $categs->avatar_categories_Id;
$InfoCateg ['InfoCateg'] = DB::table('avatar_categories')->where('avatar_categories_Id', $id)->get();
//$InfoCateg = DB::table('avatar_categories')->where('avatar_categories_Id', $id)->get();
//dd($id);
return view('admin-auth.avatar_categories_edit', $InfoCateg);
//return view::make('admin-auth.avatar_categories_edit')->with('categs', $idtest);      
}

public function UpdateCategory(Request $request)
{


//dd($request->input('description'));	
DB::table('avatar_categories')
            ->where('avatar_categories_Id', $request->input('id'))
            ->update(array('name' => $request->input('name'),'description' => $request->input('description')));
return redirect('/avatar_categories');
//return view::make('admin-auth.avatar_categories_edit')->with('categs', $idtest);      
}

public function DeleteCategory($id)
{
//dd("hola");
DB::table('avatar_categories')->where('avatar_categories_Id', '=', $id)->delete();
return redirect('/avatar_categories');           
}







//Avatars content--------------------------------------------------------------------------------------------------------------------------------------------
public function ShowAvatars()
{
$availableCategories ['availableCategories'] = DB::table('avatar_categories')->get();	
//-
$existingAvatars ['existingAvatars'] = DB::table('avatar')->get();
//-
if(Auth::guard('admin_user')->user())
            {
			return view('admin-auth.avatars',$availableCategories,$existingAvatars);
            }
return redirect('/dashboard');
           
}

public function CreateAvatar(Request $request)
{
//insert on cloudinary-------------------------	
/*$filename = $request->file;
//dd($filename);
$randomgen = Str::random(5);
$removeSpaces = str_replace(array(' '), null,$request->name.$randomgen);
$publicId = $removeSpaces;
Cloudder::upload($filename, $publicId);
$arrayOfImageData=Cloudder::getResult();
//Function to format the url and remove ""
//$urlFormatted = str_replace(array('"'), null,$arrayOfImageData['url']);
//dd($urlFormatted);
//--------------------------------------------
//insert on DB--------------------------------
DB::table('avatar')->insert(
    ['name' => $request->name, 'description' => $request->description , 'link' => $arrayOfImageData['url'] , 'fk_avatar_categories_Id' => $request->fkCategId]
);
//--------------------------------------------
return redirect('/avatars');
*/
$InsertAvatar = DB::table('avatar')->insert(
    ['name' => $request->name, 'description' => $request->description , 'link' => "holder" , 'fk_avatar_categories_Id' => $request->fkCategId]
);
$idOfAvatar = DB::getPdo()->lastInsertId();
$filename = $request->file;
$publicId = "avatar_image".$idOfAvatar;
Cloudder::upload($filename, $publicId);
$arrayOfImageData=Cloudder::getResult();
DB::table('avatar')
->where('avatar_Id', $idOfAvatar)
->update(array('link' => $arrayOfImageData['url']));
//--------------------------------------------
return redirect('/avatars');
}

public function EditAvatar($id)
{
$availableCategories ['availableCategories'] = DB::table('avatar_categories')->get();	
$avatarInfo ['avatarInfo'] = DB::table('avatar')->where('avatar_Id', $id)->get();
return view('admin-auth.avatar_edit', $availableCategories,$avatarInfo);
}

public function UpdateAvatar(Request $request)
{
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
DB::table('avatar')
->where('avatar_Id', $request->avatar_id)
->update(array('name' => $request->name,'description' => $request->description,'fk_avatar_categories_Id' => $request->fkCategId));
//----------------------------------------------------------
return redirect('/avatars');  
}

public function DeleteAvatar($id)
{
$AvatarInfo = DB::table('avatar')->where('avatar_Id', $id)->get();
$slice1 = str_after($AvatarInfo[0]->link, 'v');
$slice2 = str_after($slice1, '/');
$firstswap = strrev($slice2);
$slice3 = str_after($firstswap, '.');
$secondswap = strrev($slice3);
$publicId = $secondswap;
Cloudder::delete($publicId);
DB::table('avatar')->where('avatar_Id', '=', $id)->delete();
return redirect('/avatars');   
}

}
