<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use DB;
use Hash;
use Validator;
use Illuminate\Support\Str;
use Mail;
use App\Mail\verifyEmail;
use App\Mail\recoveryEmail;
use App\Http\Controllers\Config;
//use App\Http\Controllers\Socialite;
use Socialite;
use App\socialProvider;
use App\socialProviders;

use Illuminate\Support\Facades\Route;
use View;
use DateTime;
//use Laravel\Socialite\Facades\Socialite;
//use Requests\userInfoRequest;
//use App\http\Requests\rulesregister;
//use App/Http/Controllers/User;

class ApiAuthController extends Controller
{
/*
|--------------------------------------------------------------------------
| user check existence Function
|--------------------------------------------------------------------------
|
| This function verify if the user email exist on the DB , 
if exist it returns a 1   , if not it returns a 0
| 
|
*/
public function userCheckExistence(Request $request)
{//get the info of the user to check
$user = User::where('email', '=', $request->email)->get();
/*
$userExist = DB::table('users')
->select(DB::raw('count(user_Id)as exist'))
->where('email', '=', $request->email)
->get();
return $userExist;  */ 
if (count($user))//if it exist records 
{
//dd($user[0]->remember_token);
			if($request->remember_token != $user[0]->remember_token)//compare remember token from db to given by the app
			{
			return 	0;//Force logout
			}else
			{
			return 1;//All Fine
			}
}else//if doesn't exist records 
{
return 0;//Force logout	
} 
}
/*
|--------------------------------------------------------------------------
| user upgrade status 
|--------------------------------------------------------------------------
|
| Changes the user status to [2]: confirmed and not first time logging in 
| 
| 
|
*/
public function upgradeStatus(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 
//user creation dependancys to protect the system as if it was registered and confirmed@@@@@@@@@@@@@@@@@@@@@@@@
User::where(['email'=>$request->email])->update(['status' =>'2','verifyToken'=>NULL]);


DB::table('userfrequency')->insert(
['fk_frequency_Id' => 3, 'fk_user_Id' => $userid]
);

//Asignate every category available to the user as a standard---------------------------------------------------
$categsIds = DB::select('SELECT distinct(category_Id) FROM category;');
foreach ($categsIds as $categ) {
DB::table('preferred_categories')->insert(
['fk_user_Id' => $userid, 'fk_category_Id' => $categ->category_Id]
);
}
//assignate standard avatars-----------------------------------------------------------------------  
$avatarIds = DB::select('SELECT distinct(avatar_Id) FROM avatar where fk_avatar_categories_Id = ?',[1]);
foreach ($avatarIds as $avatars) {
DB::table('avatar_permission')->insert(
['fk_user_Id' => $userid, 'fk_avatar_Id' => $avatars->avatar_Id]
);
}
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
/*
DB::table('users')
->where('user_Id', $userid)
->update(array('status' => 2));
*/            
}
/*
|--------------------------------------------------------------------------
| UserAuth Function
|--------------------------------------------------------------------------
|
| This function verify if the user email exist , 
| then verify if the user its confirmed then
| authenticate the user and generates a token
|
*/
public function userAuth(Request $request)
{  //Gets the email and password that the user inputs
$credentials = $request->only('email', 'password');
// if [1] : checks if email already exist on the database , if not it sends an arror
if (User::where('email', '=', $request->email)->exists())
{  //query to retrieve a model of users
$userd = User::where('email', '=', $request->email)->firstOrFail();
//starting value of token
$token = null;
// if [2]  : checks if user status is 1:confirmed but new to the app or 0:default,not confirmed, if not sends an error  and [2] confirmed and not new to the app
if($userd->status == 1 or $userd->status == 2)
{
try
{//method attempt from jtwauth library , checks model for User.php(check on the database if the user and password match)
if(!$token = JWTAuth::attempt($credentials))
{//if error during login by credentials  , it will send this exception
return response()->json(['error'=> 'Invalid Credentials']);
}//if error during loging in , it will send an exception
}catch(JWTException $ex){
return response()->json(['error'=> 'Something_went_wrong'], 500);
}
DB::table('users')
->where('email', $request->email)
->update(['remember_token' => $token]);
//echo 'Login succesful';
return response()->json(array('token'=>$token,'nickname'=>$userd->nickname,'image'=>$userd->imagelink,'status'=>$userd->status));
}else
{//error from if [2]
return response()->json(['error'=> 'This email its not Confirmed , Please check your email']);
}
}else 
{//error from if [1]
return response()->json(['error'=> '2 This email Does not exist']);
}
}
/*
|--------------------------------------------------------------------------
| Register User Function
|--------------------------------------------------------------------------
|
| This function register the user on the database and sends a confirmation email
|
*/
public function registeruser(Request $request)
{
//method to asignate and validate rules of validation
$reqv = Validator::make($request->all(), [
'nickname' => 'required|unique:users|max:18|alpha_num|min:6',
'email' => 'required|email|unique:users|max:50|min:8',
'password' => 'required|min:8|alpha_num|max:18',
]);
//if fails to succes one of the rules , display errors
if ($reqv->fails())
{
return $reqv->errors();
}
$user = new User([
//fields to be taken from the post and placed on the DB
'nickname' => $request->input('nickname'),
'email' => $request->input('email'),
'password' => Hash::make($request->input('password')),
'verifyToken' => Str::random(40),
'imagelink' => "http://res.cloudinary.com/storagefeed/image/upload/v1510772763/Avatars/ico2.png",
]);
$user->save();//if success will throw a succes message 
Mail::to($user['email'])->send(new verifyEmail($user));
}
/*
|--------------------------------------------------------------------------
| Resend verification Email
|--------------------------------------------------------------------------
|
| This function resend the verification email to the user 
|
*/
public function resendVerificationEmail(Request $request)
{
try
{
$user = User::where('email', '=', $request->email)->firstOrFail();  
}catch(\Exception $e)
{
return null;
}   
if($user->verifyToken == "" or $user->verifyToken == null )  
{
return null;
}else
{
Mail::to($user['email'])->send(new verifyEmail($user));
}
}
/*
|--------------------------------------------------------------------------
| Functions to send the email and receive a response
|--------------------------------------------------------------------------
|
| 
|
*/
public function verifyEmailFirst()
{
return view("Mail.verifyEmail");
}
public function sendEmailDone($email, $verifyToken)
{
$user = User::where(['email' =>$email,'verifyToken'=>$verifyToken])->first();
if($user)
{
User::where(['email'=>$email,'verifyToken'=>$verifyToken])->update(['status' =>'1','verifyToken'=>NULL]);
DB::table('userfrequency')->insert(
['fk_frequency_Id' => 3, 'fk_user_Id' => $user->user_Id]
);
//assignate standard avatars-----------------------------------------------------------------------  
$avatarIds = DB::select('SELECT distinct(avatar_Id) FROM avatar where fk_avatar_categories_Id = ?',[1]);
foreach ($avatarIds as $avatars) {
DB::table('avatar_permission')->insert(
['fk_user_Id' => $user->user_Id, 'fk_avatar_Id' => $avatars->avatar_Id]
);
}
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
/*
//Asignate every category available to the user as a standard
$categsIds = DB::select('SELECT distinct(category_Id) FROM category;');
foreach ($categsIds as $categ) {
DB::table('preferred_categories')->insert(
['fk_user_Id' => $user->user_Id, 'fk_category_Id' => $categ->category_Id]
);
}
//assignate standard avatars-----------------------------------------------------------------------  
$avatarIds = DB::select('SELECT distinct(avatar_Id) FROM avatar where fk_avatar_categories_Id = ?',[1]);
foreach ($avatarIds as $avatars) {
DB::table('avatar_permission')->insert(
['fk_user_Id' => $user->user_Id, 'fk_avatar_Id' => $avatars->avatar_Id]
);
}
*/
//Return Succes html template
return View::make('emails.successEmail');
}else
{
//Return Expired html template
return View::make('emails.expirationEmail');
} 
}
/*
|
| 
|
|
| 
|---------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Update User Function
|--------------------------------------------------------------------------
|
| This function updates the password of the user
|
*/
public function updatepassword(Request $request)
{
//method to asignate and validate rules of validation
$reqv = Validator::make($request->all(), [
'password' => 'required|min:8|alpha_num|max:18',
'new_Password' => 'required|min:8|alpha_num|max:18',
'new_Password2' => 'required|min:8|alpha_num|max:18',
]);
//if fails to succes one of the rules , display errors on form of array
if ($reqv->fails())
{
return $reqv->errors();
}
//user email,id,nickname received globaly
$email =$request->input('email');
//database email retrieved with global email
$dbemail = DB::table('users')->where('email', $email)->pluck('email');
//database password of the user retrieved with email
$dbPassword = DB::table('users')->where('email', $email)->pluck('password');
//old password input by user
$old_Password =$request->input('password');
//new password 1 input by user
$new_Password=$request->input('new_Password');
//new password 2 input by user
$new_Password2=$request->input('new_Password2');
//Function to format the dbpassword from database to remove all the [] and "
$dbPasswordFormatter = str_replace(array( '\\','[',']','"'), null,$dbPassword);
//Checks if old password and hashed password from formated database password of user match
if (Hash::check($old_Password, $dbPasswordFormatter)) //-primera validacion
{   
//Checks if new password and new password 2(confirm password) match 
if($new_Password == $new_Password2) //- segunda validacion
{
//Checks if new password 2(confirmation password) its different from hashed db password and new password 1 its different from old password
if($new_Password2 != $dbPasswordFormatter and $new_Password != $old_Password)
{
//Query to update the password with hash security to the user 
try{User::where('email', '=', $email)->update(array('password' => Hash::make($new_Password)));
return response()->json(['status'=>true,'Succes update!'],202);
//In case of an error throws an exception
}catch(\Exception $e)
{
return response()->json(['status'=>true,'Something went wrong!'],202);      
}
}else
{
return ' Fail new_password2 and new_password match with old_password';
}
}else{
return ' Fail new_password and new_password_2 does not match';
}
}else{
return 'Fail old_password and DB_password does not match ';
}
}
/*
|--------------------------------------------------------------------------
| Recover email function
|--------------------------------------------------------------------------
|
| This function sends an email with a link to proceed with
| the recovering of the email
|
*/
public function recoverEmail(Request $request)
{//generates the changeToken for the user 
DB::table('users')
->where('email', $request->email)
->update(['changeToken' => Str::random(40)]);
//gives the user model to user
$user = User::where('email', $request->email)->first();
//go to route to send recovery email and its content
Mail::to($user['email'])->send(new recoveryEmail($user));
}
public function sendRecoveryEmail($email, $changeToken)
{
//pass the data to the model
$user = User::where(['email' =>$email,'changeToken'=>$changeToken])->first();
//compare if model exist
if($user)
{
//if it exist redirect to the app route with the parameters
return redirect()->route('routeToApp' ,["email" =>$email,"changeToken" => $changeToken]);
}else{return View::make('emails.expirationEmail');} 
}
/*
|--------------------------------------------------------------------------
| Saves the new password 
|--------------------------------------------------------------------------
|
|This function is the second part of the forgot account function to save the entered password
|
*/
public function passwordCreate(Request $request)
{
User::where('email', '=', $request->email)
->update([
'password' => Hash::make($request->input('password')),
'changeToken' => null
]);
}
//------------------------------------------------------------------------functions to test facebook login functions-------------------------------------------------------------------------
/*
|--------------------------------------------------------------------------
| Updater
|--------------------------------------------------------------------------
|
| if hasnick ==1 , retrieves the existing user email and register it with its dependency on the social_provider  then updates the remember token for the user
| if hasnick ==0 , creates the user with given nickname and retrieves the existing user email and register it with its dependency on the social_provider  then updates the remember token for the user
| requires : -email , -hasnick , -nickaname
| 
| 
|
*/
public function SPManagerAndroidUpdater(Request $request)
{//obtained during session stored info on the app that will be erased after process ends
$userEmail =$request->email;
//$check = $request->check;
$provider = $request->provider;
//$providerId = $request->providerId;
$token = $request->token;
//$devicetoken = $request->devicetoken;
$nickname = $request->nickname;
$hasnick = $request->hasnick;
$date = date("Y-m-d H:i:s");
if($hasnick == 1)
{//if has nick
$person = User::firstOrCreate(
['email'=> $userEmail]
);
$userId = $person->id;
if($userId == null){$userId = $person->user_Id;}
User::where(['user_Id'=>$userId])->update(['remember_token' => $token]);
}else{//if doesnt have nick
$reqv = Validator::make($request->all(), [
'nickname' => 'required|unique:users|max:18|alpha_num|min:6',
]);
//if fails to succes one of the rules , display errors
if ($reqv->fails())
{
return $reqv->errors();
}
//create the user with given nickname
$person = User::firstOrCreate(
['email'=> $userEmail,
'nickname'=> $nickname,
"remember_token" => $token]
);
$userId = $person->id;
if($userId == null){$userId = $person->user_Id;}
} //end of if
if($person->status == 0){User::where(['email'=>$userEmail])->update(['status' =>'1']);}
//create social provider dependancys
DB::table('social_provider')->insert(
['fk_user_Id' => $userId,
//'provider_Id' => $providerId,
'provider' => $provider]
);
$userd = User::where('email', '=', $userEmail)->firstOrFail();
return response()->json(array('token'=>$userd->remember_token,'email'=>$userEmail,'nickname'=>$userd->nickname,'image'=>$userd->imagelink,'status'=>$userd->status));
}
/*
|--------------------------------------------------------------------------
| Check existance on social_providers and let pass existing ones
|--------------------------------------------------------------------------
|
| Checks if the user exist with the given email
| requires : -email , -provider , -token
| 
| 
|
*/
public function SPManagerAndroidChecker(Request $request)
{	
$check;
$hasnick;
$userEmail = $request->email;
$checkExistence = 0;
$provider = $request->provider;	
$token=$request->token;
$checks = DB::select('SELECT DISTINCT
social_provider_Id
FROM
social_provider,
users
WHERE
users.email = ?
AND social_provider.fk_user_Id = users.user_Id
AND social_provider.provider = ?', [$userEmail,$provider]);
if (count($checks))
{
$check = 1;
DB::table('users')
->where('email', $userEmail)
->update(array( 
"remember_token" => $token,
));
$userd = User::where('email', '=', $userEmail)->firstOrFail();
return response()->json(array('checkSPExistence'=>$check,'token'=>$token,'email'=>$userEmail,'nickname'=>$userd->nickname,'image'=>$userd->imagelink,'status'=>$userd->status));
}else
{
$check = 0;
$hasnick=0;
$checknicks = DB::select('select nickname from users where email = ?', [$userEmail]);
if (count($checknicks)){$hasnick = 1;}
}
return response()->json(array('checkSPExistence'=>$check,'email'=>$userEmail,'hasNick'=>$hasnick));
}

/*
|--------------------------------------------------------------------------
| NOT GONNA BE USED--Check existance on users table --NOT GONNA BE USED
|--------------------------------------------------------------------------
|
| Checks if the user exist with the given email  if it exist return 1 , if its not 0
| requires : -email
| 
| 
|
*/
/*
public function SPManagerAndroidExistence(Request $request)
{
$userEmail = $request->email;
$hasnick=0;
$checknicks = DB::select('select nickname from users where email = ?', [$userEmail]);
if (count($checknicks)){$hasnick = 1;}
//return $hasnick;
return response()->json(['hasnick'=> $hasnick]);
}
*/
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------





























//<---------------------------------------------------------------------------TRASH-------------------------------------------------------------------------->
/*
|--------------------------------------------------------------------------
| Social Media : Google+ login
|--------------------------------------------------------------------------
|
| This function authenticates Google+ login 
*/
/**
* Redirect the user to the GitHub authentication page.
*
* @return Response
*/
public function redirectToProvider($provider)
{
return Socialite::driver($provider)->redirect();
}
/**
* Obtain the user information from GitHub.
*
* @return Response
*/
public function handleProviderCallback($provider)
{
try
{
$socialUser = Socialite::driver($provider)->stateless()->user();
}catch(\Exception $e)
{
return redirect('/');
}
//check if we have logged provider
$socialProvider = socialProvider::where('provider_Id',$socialUser->getId())->first();
$token = $socialUser->token;
//echo 'el social prov es ' .$socialProvider;
if(!$socialProvider)
{
$user = User::firstOrCreate(
['email'=> $socialUser->getEmail()],
['nickname'=> $socialUser->getName()]
);
$user->socialProviders()->create(
['provider_Id' => $socialUser->getId(), 'provider' => $provider]
);
DB::table('users')
->where('email', $socialUser->getEmail())
->update(['remember_token' => $socialUser->token]);
}else    
echo $socialUser->getId();
echo $socialUser->getNickname();
echo $socialUser->getName();
echo $socialUser->getEmail();
echo $socialUser->getAvatar();
echo $token;
//echo $expiresIn = $socialUser->expiresIn;
// $user = $socialUser->getName();
//auth()->login($socialUser);
//return redirect('/')
//    echo 'user es  ' .$user;
// return $user->getEmail();
//dd($user);
// $user->token;
}
//<---------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
}