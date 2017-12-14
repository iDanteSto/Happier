<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\AdminUser;
use Hash;
use DB;

class ReportsManager extends Controller
{
    
//User reports
public function Show_users_report()
{
if(Auth::guard('admin_user')->user())
            {
			return view('admin-auth.users_report');  		
            }
return redirect('/dashboard');   
}
//Recommendations reports
public function Show_recommendations_report()
{
if(Auth::guard('admin_user')->user())
            {
			return view('admin-auth.recommendations_report');  		
            }
return redirect('/dashboard');   
}
//Metrics reports
public function Show_metrics_report()
{
if(Auth::guard('admin_user')->user())
            {
			return view('admin-auth.metrics_report');  		
            }
return redirect('/dashboard');   
}

}
