<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DB;

class NewsController extends Controller
{
                                           /*
                                          |--------------------------------------------------------------------------
                                          | News Loader
                                          |--------------------------------------------------------------------------
                                          |
                                          | Loads all the news info of the respective day with active status
                                          |
                                          */    
public function newsLoader(Request $request)
{
$news = DB::table('news')
//->where('date_created', '=',date("Y-m-d"))
->where('status', '=',1)
->latest('date_created')
->get();
if (count($news)) 
{
$author = DB::table('users')
->where('user_Id', '=',$news[0]->fk_user_Id)
->get();
	
	// in case we only need the news info without author    return $news


//----------protect-----------------

	return response()->json(array(
		'Autor'=>$author[0]->nickname , 
		'title' => $news[0]->title , 
		'content' => $news[0]->content ,
		'image' => $news[0]->image ,
		'date_created' => $news[0]->date_created ,
		));
		
//-----------------------------------
//-----test area----------------------
/*
 return response()->json([
    'Autor'=>$author[0]->nickname , 
	'title' => $news[0]->title , 
    'content' => $news[0]->content ,
	'image' => $news[0]->image ,
	'date_created' => $news[0]->date_created ,
]);

*/
//-------------------------------------





}else
{
	return 'no hay noticias nuevas';
}

}



}
