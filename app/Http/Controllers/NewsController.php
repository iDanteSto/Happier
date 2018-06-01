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
->where('status', '=',1)
->latest('date_created')
->get();
if (count($news)) 
{
$author = "Admin";
// in case we only need the news info without author    return $news


//----------protect-----------------

return response()->json(array(
'Autor'=>$author, 
'title' => $news[0]->title , 
'content' => $news[0]->content ,
'image' => $news[0]->image ,
'date_created' => $news[0]->date_created ,
));

}else
{
return 'no hay noticias nuevas';
}

}



}
