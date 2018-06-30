<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class AdminController extends Controller
{
    /*
	public function _construct()
	{
		$this->middleware('admin');
	}
	*/

	public function dashboard()
	{

		if (Auth::guard('admin_user')->user())
             {
				return view('admin-auth.admin_home');
				
             }else
             {
             	return view('admin-auth.404error');
             }                 
	}




	
}
