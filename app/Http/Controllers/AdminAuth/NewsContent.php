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

class NewsContent extends Controller
{
    
//News
public function ShowNews()
{
if(Auth::guard('admin_user')->user())
            {/*
						$availableCategories = DB::table('avatar_categories')
						->select(DB::raw('SELECT avatar_categories_Id , name , description FROM avatar_categories'))
						->get();
*/

						/*$availableCategories = DB::table('avatar_categories')->get();*/
$availableNews ['availableNews'] = DB::table('news')->get();

//$checkIfEditable ['checkIfEditable'] = DB::select('SELECT distinct(fk_avatar_categories_Id) from avatar');
//$checkIfEditable ['checkIfEditable'] = DB::table('avatar')->distinct('fk_avatar_categories_Id')->get();
//dd($checkIfEditable);
//dd($checkIfEditable);
//test
						//-----------------
						if(count($availableNews)>0)
						{
							return view('admin-auth.news', $availableNews);
						}else
						{
							return view('admin-auth.news');
						}

                		
            }
            return redirect('/dashboard');
}


public function CreateNews(Request $request)
{
$InsertNews = DB::table('news')->insert(
    ['title' => $request->title, 'content' => $request->content , 'image' => "holder" , 'status' => 1]
);
$idOfNews = DB::getPdo()->lastInsertId();
$filename = $request->file;
$publicId = "news_image".$idOfNews;
Cloudder::upload($filename, $publicId);
$arrayOfImageData=Cloudder::getResult();
DB::table('news')
->where('news_Id', $idOfNews)
->update(array('image' => $arrayOfImageData['url']));
return redirect('/news');           
}


public function EditNews($id)
{
$InfoNews ['InfoNews'] = DB::table('news')->where('news_Id', $id)->get();
return view('admin-auth.news_edit', $InfoNews);  
}

public function UpdateNews(Request $request)
{
DB::table('news')
            ->where('news_Id', $request->input('NewsId'))
            ->update(array('title' => $request->input('title'),'content' => $request->input('content')));
return redirect('/news');    
}

public function DeleteNews($id)
{
$NewsInfo = DB::table('news')->where('news_Id', $id)->get();
$slice1 = str_after($NewsInfo[0]->image, 'v');
$slice2 = str_after($slice1, '/');
$firstswap = strrev($slice2);
$slice3 = str_after($firstswap, '.');
$secondswap = strrev($slice3);
$publicId = $secondswap;
Cloudder::delete($publicId);
DB::table('news')->where('news_Id', '=', $id)->delete();
return redirect('/news');        
}




}
