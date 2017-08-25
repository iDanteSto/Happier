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
//Route::get('/', function(){return view('welcome');});

Route::group(['middleware' => 'cors'], function(){
	//ApiAuthController(Login related)
	Route::get('/user/show', 'ApiAuthController@show');//Dummy check
	Route::post('/user/auth_login', 'ApiAuthController@userAuth');//Login Authorizer--
	Route::post('/user/register', 'ApiAuthController@registeruser');//Register--
	Route::patch('/user/update', 'ApiAuthController@updatepassword');//Hard Update password--
	Route::patch('/user/recoverEmail', 'ApiAuthController@recoverEmail');//Recover email sender--
	Route::patch('/user/createNewPass', 'ApiAuthController@passwordCreate');//Create new pass--
	Route::post('/user/resendVerificationEmail', 'ApiAuthController@resendVerificationEmail');//Resend email verification--
	//Recommendation
	Route::get('/user/recommendationChecker', 'recommendationController@recommendationChecker');//Checker
	Route::post('/user/recommendationSetter', 'recommendationController@recommendationSet');//Setter
	Route::patch('/user/recommendationComplete', 'recommendationController@recommendationComplete');//Completer
	Route::get('/user/recommendationLoader', 'recommendationController@recommendationLoader');//Loader
	Route::patch('/user/recommendationRejecter', 'recommendationController@recommendationRejecter');//Rejecter maybe not?
	Route::patch('/user/recommendationChanger', 'recommendationController@recommendationChanger');//Changer
	Route::patch('/user/recommendationSaver', 'recommendationController@recommendationSaver');//Saver
	//Meditation
	Route::post('/user/meditationSetter', 'meditationController@meditationSet');//Setter
	Route::patch('/user/meditationComplete', 'meditationController@meditationComplete');//Completer
	Route::get('/user/meditationLoader', 'meditationController@meditationLoader');//Loader
	//Configuration.categories and frequency
	Route::get('/user/userCategsAndFrequencyLoader', 'configurationController@userCategsAndFrequencyLoader');//Loader--
	Route::post('/user/userCategsAndFrequencySetter', 'configurationController@userCategsAndFrequencySetter');//Setter--
	//Profile.Avatar
	Route::get('/user/avatarLoader', 'ProfileController@avatarLoader');//Loader--
	Route::patch('/user/avatarSetter', 'ProfileController@avatarSetter');//Setter--
	//Profile.Mood
	Route::post('/user/moodSetter', 'ProfileController@moodSetter');//Setter
	Route::get('/user/moodChecker', 'ProfileController@moodChecker');//Checker
	Route::get('/user/moodLoader', 'ProfileController@moodLoader');//Loader
	Route::patch('/user/moodCompleter', 'ProfileController@moodCompleter');//Completer
	//Profile.Summary
	Route::get('/user/summaryLoader', 'ProfileController@summaryLoader');//Loader
	Route::get('/user/userRecomsHistoryLoader', 'ProfileController@userRecomsHistoryLoader');//Loader
	//DailyNews
	Route::get('/user/newsLoader', 'NewsController@newsLoader');//Loader--
	
});

