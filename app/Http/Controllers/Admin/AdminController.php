<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\AdminUser;
use Hash;
use Input;
use DB;
use Khill\Lavacharts\Lavacharts;
use App\User;
use Carbon\Carbon;

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
    //users per month info         
    $arraymonths = User::select(DB::raw("COUNT(*) as count ,  MONTHNAME(created_at) as month"))
    ->whereYear('created_at', 2017)
    ->orderBy("created_at")
    ->groupBy(DB::raw("month(created_at)"))
    ->get()->toArray();
    $meses = array();
    $usuarios = array();
    foreach($arraymonths as $data){
        array_push($meses, $data['month']);
        array_push($usuarios, $data['count']);
    }
    $data = array();
    $data['meses'] = $meses;
    $data['usuarios'] = $usuarios;
    //-----options for select dropdown------------------------
    $earliest = DB::table('users')
         ->select('created_at')
         ->orderBy('created_at')
         ->first();
    $latest = DB::table('users')
         ->select('created_at')
         ->orderBy('created_at' , 'desc')
         ->first();
    $earliestCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $earliest->created_at)->year;  
    $latestCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $latest->created_at)->year;                    
    $diffinYears =  $latestCarbon - $earliestCarbon ;
    //---------------------------------------------------------
    //counters of cards
    $adminscount = DB::table('admin_users')->where('level', '=', 1)->count();
    $guestcount = DB::table('admin_users')->where('level', '=', 0)->count();
    $userscount = DB::table('users')->count();
    //---------------------------------------------------------
    //table of users
    $availableUsers ['availableUsers'] = DB::table('users')->get();
    //---------------------------------------------------------
    //global recommendation stats
    $RecomStats ['RecomStats']= DB::select('SELECT userRecommendation_Id,
                                    sum(fk_status_Id = ?) as pendiente,
                                    sum(fk_status_Id = ?) as completa,
                                    sum(fk_status_Id = ?) as rechazada,
                                    sum(fk_status_Id = ?) as ignorada,
                                    sum(fk_status_Id = ?) as guardada
                                  FROM userrecommendation' , [2,1,3,4,5]);
    //---------------------------------------------------------
    return view('admin-auth.admin_home', ['data' => $data])
    ->with('diffinYears',$diffinYears)
    ->with('earliestCarbon',$earliestCarbon)
    ->with('adminscount',$adminscount)
    ->with('guestcount',$guestcount)
    ->with('userscount',$userscount)
    ->with($availableUsers)
    ->with($RecomStats)
    ;	
             }else
             {
             	return view('admin-auth.404error');
             }                 
	}

	public function get_metrics_graph(Request $request){
    $year = $request->year;
    $arraymonths = User::select(DB::raw("COUNT(*) as count ,  MONTHNAME(created_at) as month"))
    ->whereYear('created_at', $year)
    ->orderBy("created_at")
    ->groupBy(DB::raw("month(created_at)"))
    ->get()->toArray();

    $meses = array();
    $usuarios = array();

    foreach($arraymonths as $data){
        array_push($meses, $data['month']);
        array_push($usuarios, $data['count']);
    }
    $data = array();
    $data['meses'] = $meses;
    $data['usuarios'] = $usuarios;
    return $data;
}



	
}
