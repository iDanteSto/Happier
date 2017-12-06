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
						//-----------------
$checkIfEditable ['checkIfEditable'] = DB::select('SELECT distinct(fk_avatar_categories_Id) from avatar');
//$checkIfEditable ['checkIfEditable'] = DB::table('avatar')->distinct('fk_avatar_categories_Id')->get();
//dd($checkIfEditable);
//dd($checkIfEditable);
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





















}