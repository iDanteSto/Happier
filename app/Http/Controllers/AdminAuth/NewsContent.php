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
            {
			return view('admin-auth.news');  		
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
