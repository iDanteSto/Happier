<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'cors'], function(){
//Dummy-----------------------------------------------------------------------------------------------------------------------------------------------|
Route::get('/user/dummyFunction0', 'dummyController@dummyFunction0');//Dummy check	
Route::get('/user/dummyFunction', 'dummyController@dummyFunction');//Dummy check															          |
Route::get('/user/dummyFunction2', 'dummyController@dummyFunction2');//Dummy check	
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//ApiAuthController(Login related)--------------------------------------------------------------------------------------------------------------------|
Route::get('/user/userCheckExistence', 'ApiAuthController@userCheckExistence');//Check existence --*								  				  |
Route::post('/user/auth_login', 'ApiAuthController@userAuth');//Login Authorizer--*																	  |
Route::post('/user/register', 'ApiAuthController@registeruser');//Register--*																		  |
Route::patch('/user/update', 'ApiAuthController@updatepassword');//Hard Update password--*															  |
Route::patch('/user/recoverEmail', 'ApiAuthController@recoverEmail');//Recover email sender--*														  |
Route::patch('/user/createNewPass', 'ApiAuthController@passwordCreate');//Create new pass from the app after being redirected by the email--*	      |
Route::post('/user/resendVerificationEmail', 'ApiAuthController@resendVerificationEmail');//Resend email verification--*
//after first login tutorial
Route::patch('/user/upgradeStatus', 'ApiAuthController@upgradeStatus');//Changes the user status to [2]: confirmed and not first time logging in and assign security data
//SP 																																				  |
Route::patch('/user/SPManagerAndroidChecker', 'ApiAuthController@SPManagerAndroidChecker');//android SP checker  
Route::patch('/user/SPManagerAndroidUpdater', 'ApiAuthController@SPManagerAndroidUpdater');//android SP updater	                                          |
//Route::get('/user/SPManagerAndroidExistence', 'ApiAuthController@SPManagerAndroidExistence');//Dummy android SP checker                                   
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Recommendation--------------------------------------------------------------------------------------------------------------------------------------|
Route::get('/user/recommendationChecker', 'recommendationController@recommendationChecker');//Front-Checker to see if button appears--				  |
Route::post('/user/recommendationSetter', 'recommendationController@recommendationSet');//************Timer-Setter not gonna be used this <----       |
Route::patch('/user/recommendationComplete', 'recommendationController@recommendationComplete');//front-Completer--*                                  |
Route::get('/user/recommendationLoader', 'recommendationController@recommendationLoader');//front-Loader--*											  |
Route::patch('/user/recommendationRejecter', 'recommendationController@recommendationRejecter');//front-Rejecter--*									  |
Route::patch('/user/recommendationChanger', 'recommendationController@recommendationChanger');//front-Changer maybe not gonna be used <----			  |
Route::patch('/user/recommendationSaver', 'recommendationController@recommendationSaver');//Saver maybe not gonna be used <----						  |
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Meditation------------pending ----------------------------------------------------------------------------------------------------------------------|
Route::post('/user/meditationSetter', 'meditationController@meditationSet');//timer-Setter not gonna be used  <----									  |
Route::patch('/user/meditationComplete', 'meditationController@meditationComplete');//front-Completer												  |
Route::get('/user/meditationLoader', 'meditationController@meditationLoader');//front-Loader													      |
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Configuration.categories and frequency--------------------------------------------------------------------------------------------------------------|
Route::get('/user/userCategsAndFrequencyLoader', 'configurationController@userCategsAndFrequencyLoader');//Loader--*								  |
Route::post('/user/userCategsAndFrequencySetter', 'configurationController@userCategsAndFrequencySetter');//Setter--*	
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Profile.Avatar--------------------------------------------------------------------------------------------------------------------------------------|
Route::get('/user/avatarLoader', 'ProfileController@avatarLoader');//Loader--not gonna be used 													      |
Route::patch('/user/avatarSetter', 'ProfileController@avatarSetter');//Setter--	*														              |
//----------------------------------------------------------------------------------------------------------------------------------------------------|
//Profile.Summary-------------------------------------------------------------------------------------------------------------------------------------|
Route::get('/user/summaryLoader', 'ProfileController@summaryLoader');//Loader--*																	  |
Route::get('/user/userRecomsHistoryLoader', 'ProfileController@userRecomsHistoryLoader');//Loader--*	
Route::get('/user/userRecomsHistoryAllLoader', 'ProfileController@userRecomsHistoryAllLoader');//Loader												  
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Mood------------------------------------------------------------------------------------------------------------------------------------------------|
Route::post('/user/moodSetter', 'moodController@moodSetter');//timer-Setter not gonna be used <----											          |
Route::get('/user/moodChecker', 'moodController@moodChecker');//front checker 																		  |
Route::get('/user/moodLoader', 'moodController@moodLoader');//front-Loader																			  |
Route::patch('/user/moodCompleter', 'moodController@moodCompleter');//front-Completer																  |
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//DailyNews-------------------------------------------------------------------------------------------------------------------------------------------|
Route::get('/user/newsLoader', 'NewsController@newsLoader');//Loader--*																			      |
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Firebase.Notifications -----------------------------------------------------------------------------------------------------------------------------|
Route::patch('/user/refreshtokenDB', 'notificationController@refreshtokenDB');//refresh--*															  |
//----------------------------------------------------------------------------------------------------------------------------------------------------|
});

