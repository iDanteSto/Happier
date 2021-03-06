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
//Route::post('/user/recommendationSetter', 'recommendationController@recommendationSet');//************Timer-Setter not gonna be used this <----       |
Route::patch('/user/recommendationComplete', 'recommendationController@recommendationComplete');//front-Completer--*                                  |
Route::get('/user/recommendationLoader', 'recommendationController@recommendationLoader');//front-Loader--*											  |
Route::patch('/user/recommendationRejecter', 'recommendationController@recommendationRejecter');//front-Rejecter--*		
Route::patch('/user/recommendationSaver', 'recommendationController@recommendationSaver');//front-change to on save with a date to schedule--*		
Route::patch('/user/recommendationChanger', 'recommendationController@recommendationChanger');//front-Changer maybe not gonna be used <----			  |	                  |
Route::get('/user/recommendationSmallHistory', 'recommendationController@recommendationSmallHistory');//retrieves last 50 recoms for a small peek   
//Route::post('/user/recommendationSetter2', 'recommendationController@recommendationSetter2');//************Timer-Setter not gonna be used this <----  
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Meditation------------pending ----------------------------------------------------------------------------------------------------------------------|
Route::post('/user/meditationSetter', 'meditationController@meditationSet');//timer-Setter not gonna be used  <----									  |
Route::patch('/user/meditationComplete', 'meditationController@meditationComplete');//front-Completer												  |
Route::get('/user/meditationLoader', 'meditationController@meditationLoader');//front-Loader													      |
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Configuration.categories and frequency--------------------------------------------------------------------------------------------------------------|
Route::get('/user/userCategsAndFrequencyLoader', 'configurationController@userCategsAndFrequencyLoader');//Loader--*								  |
Route::post('/user/userCategsAndFrequencySetter', 'configurationController@userCategsAndFrequencySetter');//Setter--*	
Route::post('/user/hibernateUser', 'configurationController@hibernateUser');//
Route::get('/user/hibernateCancel', 'configurationController@hibernateCancel');//
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Profile.Nickname------------------------------------------------------------------------------------------------------------------------------------!									
Route::patch('/user/ChangeDisplayName', 'ProfileController@ChangeDisplayName');//Setter--	*														              |
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Profile.Avatar--------------------------------------------------------------------------------------------------------------------------------------|
Route::get('/user/avatarLoader', 'ProfileController@avatarLoader');//Loader--not gonna be used 													      |
Route::patch('/user/avatarSetter', 'ProfileController@avatarSetter');//Setter--	*														              |
//----------------------------------------------------------------------------------------------------------------------------------------------------|
//Profile.Summary Recommendations-------------------------------------------------------------------------------------------------------------------------------------|
Route::get('/user/summaryLoader', 'ProfileController@summaryLoader');//Loader--*																	  |
Route::get('/user/userRecomsHistoryLoader', 'ProfileController@userRecomsHistoryLoader');//Loader--*	
Route::get('/user/userRecomsHistoryAllLoader', 'ProfileController@userRecomsHistoryAllLoader');//Loader	
Route::get('/user/userScheduledRecomsHistoryLoader', 'ProfileController@userScheduledRecomsHistoryLoader');//Loader	scheduled recommendations											  
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Mood------------------------------------------------------------------------------------------------------------------------------------------------|
//Route::post('/user/moodSetter', 'moodController@moodSetter');//timer-Setter not gonna be used <----											          |
//Route::get('/user/moodChecker', 'moodController@moodChecker');//front checker 																		  |
Route::get('/user/moodLoader', 'moodController@moodLoader');//																	  |
Route::get('/user/moodCheckerNotifier', 'moodController@moodCheckerNotifier');//not used , used by cron on kernel
Route::patch('/user/moodCreateorUpdater', 'moodController@moodCreateorUpdater');//	
Route::get('/user/moodAverageLoader', 'moodController@moodAverageLoader');//		
//dummy test
Route::get('/user/dummyFunctionMoodnotif', 'dummyController@dummyFunctionMoodnotif');//Dummy test notifs	
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//DailyNews-------------------------------------------------------------------------------------------------------------------------------------------|
Route::get('/user/newsLoader', 'NewsController@newsLoader');//Loader--*																			      |
//----------------------------------------------------------------------------------------------------------------------------------------------------|

//Firebase.Notifications -----------------------------------------------------------------------------------------------------------------------------|
Route::patch('/user/refreshtokenDB', 'notificationController@refreshtokenDB');//refresh--*															  |
//----------------------------------------------------------------------------------------------------------------------------------------------------|
});

