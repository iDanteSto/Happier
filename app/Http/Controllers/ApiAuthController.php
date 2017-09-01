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
    {
      $userExist = DB::table('users')
                     ->select(DB::raw('count(user_Id)as exist'))
                     ->where('email', '=', $request->email)
                     ->get();
      return $userExist;               
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
// if [2]  : checks if user status is 1:confirmed or 0:default,not confirmed, if not sends an error 
if($userd->status == 1 )
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

       // $emailg = $request->email;  
      // echo 'el email es ' . $emailg .$token;  
    //return response()->json(compact('token'));
            echo 'Login succesful';

            return response()->json(array('token'=>$token,'nickname'=>$userd->nickname,'image'=>$userd->imagelink));
  }else
  {//error from if [2]
    return response()->json(['error'=> 'This email its not Confirmed , Please check your email']);
  }
}else 
{//error from if [1]
  return response()->json(['error'=> '2 This email Does not exist']);
}
     
  }
    
//test function
    public function dummyFunction(Request $request)
    {     
$users = DB::select('SELECT 
    users.user_Id, frequency.timesAtDay
FROM
    users,
    frequency,
    userfrequency
WHERE
    userfrequency.fk_frequency_Id = frequency.frequency_Id
        AND users.status = ?
        AND fk_user_Id = user_Id', [1]);

foreach ($users as $usuarios) 
{
$currentDate = date('Y-m-d');
$userid = $usuarios->user_Id;//place id on a variable to use it 
$timesAtDay = $usuarios->timesAtDay;

$recomCountCollection = DB::table('userrecommendation')
                     ->select(DB::raw('count(userRecommendation_Id) as recomCount'))
                     ->where('fk_user_Id', '=', $userid)
                     ->whereDate('creation_date', $currentDate)
                     ->get();
$recomCount = $recomCountCollection[0]->recomCount;
//compare the recomCount against the user frequency chosen to see if he can receive a new recommendation on the day
if($recomCount < $timesAtDay)
{
//retrieve the number of recommendations of the user that he has not interacted with 
 $recomReadyCollection = DB::table('userrecommendation')
                     ->select(DB::raw('count(userRecommendation_Id) as recomCount2'))
                     ->where('fk_user_Id', '=', $userid)
                     ->where('fk_status_Id', '=', 2)
                     ->whereDate('creation_date', $currentDate)
                     ->get(); 
//place the number of recommendations of the user that he has not interacted with on a variable to use it                 
$recomReady = $recomReadyCollection[0]->recomCount2;
//check if the user has a pending recommendation to interact with and if is eligible to get a new one
if($recomReady == 0)
{//the user is eligible to get a new recommendation
//*****'The user is ready to receive a recommendation *****
//--***********************************chorizon del proceso de recommendacion***************------------------------------
$timeOfDay =0;
//date("h:i:sa")
//Current server time
$currentTime = DateTime::createFromFormat('H:i a',date("h:i:sa"));
//Morning
$beginMorning = DateTime::createFromFormat('H:i a', "8:00 am");
$endMorning = DateTime::createFromFormat('H:i a', "12:59 pm");
//Evening
$beginEvening = DateTime::createFromFormat('H:i a', "1:00 pm");
$endEvening = DateTime::createFromFormat('H:i a', "5:59 pm");
//Night
$beginNight = DateTime::createFromFormat('H:i a', "6:00 pm");
$endNight = DateTime::createFromFormat('H:i a', "9:00 pm");
if ($currentTime >= $beginMorning && $currentTime <= $endMorning)
{
  //Morning
  $timeOfDay = 1;   
}else if($currentTime >= $beginEvening && $currentTime <= $endEvening)
{ 
  //Afternoon
  $timeOfDay =2;
}else if($currentTime >= $beginNight && $currentTime <= $endNight)
{
  //Night
  $timeOfDay =3;
}
$assign = DB::select("call recomendationSetter($userid,$timeOfDay)");
//*****Succes ! a new recommendation has been assigned *****
//|****************************************|
//New one assigned Notificacion logic here
//|****************************************|
//------------------------------------------------------------------------------------------------------------------------
}else
{//the user its not eligible to get a new recommendation
//*****'The user has not interacted with its last recommendation of the day *****
//|***********************************************************************|
//Interact with your current recommendation please Notificacion logic here
//|***********************************************************************|
}
}
else
{//the user has reached the limit of recommendations that he can get on the day
//*****'The user ran out of recommendations for the day' *****
}  
}
//return nothing
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
      //dd(Config::get('mail'));
        return $reqv->errors();
    }

    //  try{//Eloquen ORM query
        $user = new User([
    //fields to be taken from the post and placed on the DB
        'nickname' => $request->input('nickname'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
        'verifyToken' => Str::random(40),
        ]);

       $user->save();//if success will throw a succes message 

     //  return response()->json(['status'=>true,'Se registro el usuario'],200);
//new lines added-----------------------------------------------------------------------------
      // $this = $user ->create($request->all());
       //return redirect(route('verifyEmailFirst'));
       //return view('email.sendView');
     // $thisid =User::findOrFail($user->user_Id);
      // $thisid = DB::table('users')->where('user_Id', '=', ($user->id))->get();
      // return $thisid;
       //$this->sendEmail($user);
      Mail::to($user['email'])->send(new verifyEmail($user));
      // Mail::to($request->user())->send(new verifyEmail($thisid));
       //Mail::to($thisid['email'])->send(new verifyEmail($thisid));
       //echo ''  . 'el user del ' .$user['email'];

       //return $user;
//new lines added-----------------------------------------------------------------------------
     // }catch(\Exception $e)
     // {//if an error occurs it will throw an exception with a message of false
     //    return response()->json(['status'=>false,'No se pudo registrar'],500);
      //} 
    }
  
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

        
        //Mail::to($user['email'])->send(new verifyEmail($user));
       // echo 'fui a la funcion verifyEmail' . $user;
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


    //Asignate every category available to the user as a standar
    $categsIds = DB::select('SELECT distinct(category_Id) FROM category;');
    
foreach ($categsIds as $categ) {
        DB::table('preferred_categories')->insert(
          ['fk_user_Id' => $user->user_Id, 'fk_category_Id' => $categ->category_Id]
          );
        
}



//test area of avatar permissions-----------------------------------------------------------------------  

 $avatarIds = DB::select('SELECT distinct(avatar_Id) FROM avatar where fk_avatar_categories_Id = ?',[1]);
  foreach ($avatarIds as $avatars) {
        DB::table('avatar_permission')->insert(
          ['fk_user_Id' => $user->user_Id, 'fk_avatar_Id' => $avatars->avatar_Id]
          );
}


//-----------------------------------------------------------------------------
          return 'Exito! Se ha verificado correctamente';
        }else
        {
          //return 'Expired';
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
        

        //abierto a discusion : recibe  mail o nickname(global) , o id  , old_password ,  new_password  , new_password_2

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
   //  global $emailg;
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
 //In case that it success , send this message

          //echo 'el global es '.$emailg;
        //  echo 'el dbpassformated' .$dbPasswordFormatter;
        //  echo 'el dbpassword' .$dbPassword;
        //  echo 'el old password' .$old_Password;
          
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
          /*
          echo 'el global es '.$email;
          echo "       ";
          echo 'el dbpassformated  ' .$dbPasswordFormatter;
          echo "       ";
          echo 'el dbpassword  ' .$dbPassword;
          echo "       ";
          echo 'el old password  '  .$old_Password;
          echo "       ";*/
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
           // return User::where(['email'=>$email,'verifyToken'=>$verifyToken])->update(['status' =>'1','verifyToken'=>NULL]);
          
        //if it exist redirect to the app route with the parameters
          return redirect()->route('routeToApp' ,["email" =>$email,"changeToken" => $changeToken]);

        }else{return View::make('emails.expirationEmail');} 
    }
 // second part of the forgot account function
    public function passwordCreate(Request $request)
    {
/*
             DB::table('users')
            ->where('email', $request->email)
            ->update(['password' => Hash::make($request->input('password'))]);
          //  ->update(['changeToken' => null]);

*/
    User::where('email', '=', $request->email)
    ->update([
        'password' => Hash::make($request->input('password')),
        'changeToken' => null
    ]);




    }



































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







}