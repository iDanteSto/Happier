<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\AdminUser;
use Hash;
use DB;

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


public function registerCategory(Request $request)
{
        
}


public function editCategory($id)
{
  
}

public function UpdateCategory(Request $request)
{


}

public function DeleteCategory($id)
{
      
}




}
