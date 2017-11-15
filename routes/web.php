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





//admin panel test-----------------------------------------------

Route::get('/login','Adminauth\AuthController@showLoginForm')->name('login');//login normal


Route::group(['middleware'=>['web']],function()
{
	
	Route::post('login','Adminauth\AuthController@login');//login post button
	Route::get('logout','Adminauth\AuthController@logout');//logout button
	//Route::get('/dashboard','Admin\AdminController@dashboard')->name('dashboard');//ruta a el dashboard
});
//admin panel test-----------------------------------------------







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

//Auth::routes();
Route::get('admin_login','AdminAuth\LoginController@showLoginForm')->name('admin_login');                
  
Route::post('admin_login','AdminAuth\LoginController@login')->name('admin_login');                         
  
Route::post('admin_logout','AdminAuth\LoginController@logout')->name('admin_logout');                      
  
Route::post('admin_password/email','AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('admin_password/email');   
  
Route::get('admin_password/reset','AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('admin_password/reset');  
 
Route::post('admin_password/reset','AdminAuth\ResetPasswordController@reset')->name('admin_password/reset');                  
  
Route::get('admin_password/reset/{token}','AdminAuth\ResetPasswordController@showResetForm')->name('admin_password/reset/{token}');         
  
Route::get('admin_register','AdminAuth\RegisterController@showRegistrationForm')->name('admin_register');        
  
Route::post('admin_register','AdminAuth\RegisterController@register')->name('admin_register');                



Route::get('/dashboard','Admin\AdminController@dashboard')->name('dashboard');
//Route::get('/home', 'HomeController@index')->name('home');
