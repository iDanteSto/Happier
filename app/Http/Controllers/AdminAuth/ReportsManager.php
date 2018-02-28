<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\AdminUser;
use Hash;
use DB;
use Khill\Lavacharts\Lavacharts;
use App\User;
use Carbon\Carbon;

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
public function Show_metrics_report($year)
{
if(Auth::guard('admin_user')->user())
            {
//-----obtain valid years
$earliest = DB::table('userrecommendation')
                     ->select('creation_date')
					 ->orderBy('creation_date')
                     ->first();
$latest = DB::table('userrecommendation')
                     ->select('creation_date')
					 ->orderBy('creation_date' ,'desc')
                     ->first();

$earliestCarbon = Carbon::parse($earliest->creation_date);
$latestCarbon = Carbon::parse($latest->creation_date);
//$year = Carbon::createFromFormat('Y-m-d H:i:s', $earliest->creation_date)->year;
//dd($year);       
//$yearend = Carbon::createFromFormat('Y-m-d H:i:s', $endate)->year;
$diffinYears =  $latestCarbon->diffInYears($earliestCarbon);
//-----
$earliestyear = DB::table('users')
                     ->select('created_at')
					 ->orderBy('created_at')
                     ->first();
$earliestyearOnlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $earliestyear->created_at)->year;                     


if($year != 0)
{
$lava = new Lavacharts; // See note below for Laravel

$population = \Lava::DataTable();


$arraymonths = User::select(DB::raw("COUNT(*) as count ,  MONTHNAME(created_at) as month"))
	->whereYear('created_at', $year)
    ->orderBy("created_at")
    ->groupBy(DB::raw("month(created_at)"))
    ->get()->toArray();

 $chart_array = array();
    foreach($arraymonths as $data){
        $n_data = [];
        array_push($n_data,$data['month'],$data['count']);
        array_push($chart_array,$n_data);
    }
    $lava = new Lavacharts; 
    $popularity = $lava->DataTable();
    $popularity->addStringColumn('Mes')
               ->addNumberColumn('Registrados')
               ->addRows($chart_array);
    \Lava::LineChart('demochart', $popularity, [
            'title' => 'Usuarios Registrados en el año '.$year,
            'animation' => [
            'startup' => true,
            'easing' => 'inAndOut'
        ],
        'colors' => ['blue', '#F4C1D8']
    ]);	



$ignoradas = DB::table('userrecommendation')
                     ->select(DB::raw('count(*) as ignoradas'))
                     ->where('fk_status_Id', '=', 4)
                     ->get();
$completas = DB::table('userrecommendation')
                     ->select(DB::raw('count(*) as completas'))
                     ->where('fk_status_Id', '=', 1)
                     ->get();
$rechazadas = DB::table('userrecommendation')
                     ->select(DB::raw('count(*) as rechazadas'))
                     ->where('fk_status_Id', '=', 3)
                     ->get();    
$pendientes = DB::table('userrecommendation')
                     ->select(DB::raw('count(*) as pendientes'))
                     ->where('fk_status_Id', '=', 2)
                     ->get();                                  
//---------
$lavaround = new Lavacharts; 
$reasons = \Lava::DataTable();

$reasons->addStringColumn('Recomendaciones')
        ->addNumberColumn('Porcentaje')
        ->addRow(['Completadas', $completas[0]->completas])
        ->addRow(['Rechazadas', $rechazadas[0]->rechazadas])
        ->addRow(['Pendientes', $pendientes[0]->pendientes])
        ->addRow(['Ignoradas', $ignoradas[0]->ignoradas]);
        

\Lava::DonutChart('IMDB', $reasons, [
    'title' => 'Recomendaciones'
    
]);        
//---------


return view('admin-auth.metrics_report',["lava"=>$lava] , ["year"=>$year] , ["lavaround"=>$lavaround])->with('diffinYears',$diffinYears)->with('earliestyearOnlyYear',$earliestyearOnlyYear);     
}
return view('admin-auth.metrics_report' , ["year"=>$year]);
//-------------------------------------------------------------

//---------------------------------------------------------------
//---------------------------------------------------------------


//---------------------------------------------------------------
			 	
            }
return redirect('/dashboard');   
}






}
