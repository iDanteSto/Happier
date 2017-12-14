<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\AdminUser;
use Hash;
use DB;

class RecommendationContent extends Controller
{
    
//recommendations categs
public function ShowCategs()
{
if(Auth::guard('admin_user')->user())
            {
			return view('admin-auth.recommendation_categories');  		
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
