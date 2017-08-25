<?php
//use App\Http\Controllers\Auth\ApiAuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Mail;



Route::get('/', function () {
    return view('welcome');
});
//Route::get('verifyEmailFirst', 'ApiAuthController@verifyEmailFirst')->name('verifyEmailFirst');
Route::get('verify/{email}/{verifyToken}', 'ApiAuthController@sendEmailDone')->name('sendEmailDone');
Route::get('recovery/{email}/{changeToken}', 'ApiAuthController@sendRecoveryEmail')->name('sendRecoveryEmail');
//recover password trought app
Route::get('newPassword?/{email}/{changeToken}')->name('routeToApp');
//maybe v route to app with user pref categs
//Route::get('newPassword?/{email}/{changeToken}')->name('routeToApp');
/*
Route::get('/email',function(){

Mail::send('emails.test', ['name' => 'novica' ] ,function($message)

http://192.168.1.74:8000/newPassword?email=email,changeToken=changeToken
{
	$message->to("kike@ciqno.com", 'some guy')->from('kikeciqno@gmail.com')->subject('welcome');
});


});

*/

//Route::get('/basicemail', 'mailController@basic_email');


//Route::group(['middleware' => ['web']], function () {


    // your routes here
//});
