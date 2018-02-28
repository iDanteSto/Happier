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
Route::get('/test', function () {
    return view('test');
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
Route::get('admin_login','AdminAuth\LoginController@showLoginForm')->name('admin_login');   //             
  
Route::post('admin_login','AdminAuth\LoginController@login')->name('admin_login');         //                
  
Route::post('admin_logout','AdminAuth\LoginController@logout')->name('admin_logout');     //                 
  
Route::post('admin_password/email','AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('admin_password/email');   
  
Route::get('admin_password/reset','AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('admin_password/reset');  
 
Route::post('admin_password/reset','AdminAuth\ResetPasswordController@reset')->name('admin_password/reset');                  
  
Route::get('admin_password/reset/{token}','AdminAuth\ResetPasswordController@showResetForm')->name('admin_password/reset/{token}');        

 
  
         

//[Admin panel*********************************************************************************************************************]
//dashboard
Route::get('/dashboard','Admin\AdminController@dashboard')->name('dashboard');

//Admins------------------------------------------------------------------------------------------------------------------------
Route::get('adminspage','AdminAuth\AdminController@showRegistrationForm')->name('adminspage');        
Route::post('admin_create','AdminAuth\AdminController@createAdmin')->name('admin_create');  
Route::get('admin_delete/{id}','AdminAuth\AdminController@DeleteAdmin')->name('admin_delete');   
     
//------------------------------------------------------------------------------------------------------------------------------


//App Users---------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------

//Content-----------------------------------------------------------------------------------------------------------------------
//avatar_categories routes--------------------------------------------------------------------------------------------------
Route::get('avatar_categories','AdminAuth\AvatarContent@showAvatarContent')->name('avatar_categories'); 
Route::post('avatar_categ_register','AdminAuth\AvatarContent@registerCategory')->name('avatar_categ_register');   
Route::get('avatar_categ_edit/{id}','AdminAuth\AvatarContent@editCategory')->name('avatar_categ_edit');  
Route::post('avatar_categ_update','AdminAuth\AvatarContent@UpdateCategory')->name('avatar_categ_update');   
Route::get('avatar_categories_delete/{id}','AdminAuth\AvatarContent@DeleteCategory')->name('avatar_categories_delete'); 
///avatars routes-----------------------------------------------------------------------------------------------------------
Route::get('avatars','AdminAuth\AvatarContent@ShowAvatars')->name('avatars'); //*

Route::post('CreateAvatar','AdminAuth\AvatarContent@CreateAvatar')->name('CreateAvatar');  
Route::get('avatar_delete/{id}','AdminAuth\AvatarContent@DeleteAvatar')->name('avatar_delete'); 
Route::get('avatar_edit/{id}','AdminAuth\AvatarContent@EditAvatar')->name('avatar_edit');  
Route::post('avatar_update','AdminAuth\AvatarContent@UpdateAvatar')->name('avatar_update'); 
///Recommendation_categories routes-----------------------------------------------------------------------------------------
Route::get('recommendation_categories','AdminAuth\RecommendationContent@ShowCategs')->name('recommendation_categories'); //*

Route::post('categ_create','AdminAuth\RecommendationContent@CreateCateg')->name('categ_create');  
Route::get('categ_delete/{id}','AdminAuth\RecommendationContent@DeleteCateg')->name('categ_delete'); 
Route::get('categ_edit/{id}','AdminAuth\RecommendationContent@EditCateg')->name('categ_edit');  
Route::post('categ_update','AdminAuth\RecommendationContent@UpdateCateg')->name('categ_update'); 
///Recommendations routes---------------------------------------------------------------------------------------------------
Route::get('recommendations','AdminAuth\RecommendationContent@ShowRecommendations')->name('recommendations'); //*

Route::post('recommendation_create','AdminAuth\RecommendationContent@CreateRecommendation')->name('recommendation_create');  
Route::get('recommendation_delete/{id}','AdminAuth\RecommendationContent@DeleteRecommendation')->name('recommendation_delete'); 
Route::get('recommendation_edit/{id}','AdminAuth\RecommendationContent@EditRecommendation')->name('recommendation_edit');  
Route::post('recommendation_update','AdminAuth\RecommendationContent@UpdateRecommendation')->name('recommendation_update'); 
///News routes--------------------------------------------------------------------------------------------------------------
Route::get('news','AdminAuth\NewsContent@ShowNews')->name('news'); 

Route::post('news_create','AdminAuth\NewsContent@CreateNews')->name('news_create');  
Route::get('news_delete/{id}','AdminAuth\NewsContent@DeleteNews')->name('news_delete'); 
Route::get('news_edit/{id}','AdminAuth\NewsContent@EditNews')->name('news_edit');  
Route::post('news_update','AdminAuth\NewsContent@UpdateNews')->name('news_update'); 
//------------------------------------------------------------------------------------------------------------------------------


//Reports-----------------------------------------------------------------------------------------------------------------------
//Users routes--------------------------------------------------------------------------------------------------
Route::get('users_report','AdminAuth\ReportsManager@Show_users_report')->name('users_report'); 
//Recommendations routes--------------------------------------------------------------------------------------------------
Route::get('recommendations_report','AdminAuth\ReportsManager@Show_recommendations_report')->name('recommendations_report'); 
//Metrics routes--------------------------------------------------------------------------------------------------
Route::get('metrics_report','AdminAuth\ReportsManager@Show_metrics_report')->name('metrics_report'); 
Route::get('metrics_report/{year}','AdminAuth\ReportsManager@Show_metrics_report')->name('metrics_report');  
//------------------------------------------------------------------------------------------------------------------------------
//[Admin panel*********************************************************************************************************************]