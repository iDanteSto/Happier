<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\AdminUser;
use Hash;
use DB;

class AvatarContent extends Controller
{
    








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
						if(count($availableCategories)>0)
						{
							return view('admin-auth.avatar_categories', $availableCategories);
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


public function editCategory(Request $request)
{
	/*
DB::table('avatar_categories')
            ->where('id', 1)
            ->update(['votes' => 1]);
return redirect('/avatar_categories');    
*/
//$idtest = $categs->avatar_categories_Id;
return view('admin-auth.avatar_categories_edit',$data);
//return view::make('admin-auth.avatar_categories_edit')->with('categs', $idtest);      
}
 /*   
public function showavatar_categories
{
$availableCategories = DB::table('avatar_categories')
->select(DB::raw('SELECT avatar_categories_Id , name , description FROM happier.avatar_categories'))
->get();
}     


*/





















}
