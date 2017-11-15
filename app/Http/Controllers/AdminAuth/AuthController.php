<?php

namespace App\Http\Controllers\Adminauth;

use App\Http\Controllers\Controller;
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
use Auth;
use Session;

class AuthController extends Controller
{
  // use AuthenticatesAndRegistersUsers, ThrottlesLogins;
   protected $redirectTo = '/dashboard';
   protected $guard= 'admin_user';
   

   public function showLoginForm()
   {
/*
        if(Auth::guard('admin_user')->user())
            {
                return redirect('/dashboard');
            }
            return redirect('/admin_login');

            return 'ekizde';*/
   }
   public function logout()
   {
        Auth::guard('web')->logout();
        return redirect('/login');
   }
   public function login(Request $request)
   {  //Gets the email and password that the user inputs
if(Auth::attempt(['email'=>$request['email'],'password'=>$request['password']))
      {
        $user = User::where('email', '=', $request->email)->firstOrFail();
        Auth::login($user);
        return redirect('/dashboard');
      }
      //return $next($request);
      //return view('/dashboard');
      //$user = User::where('email', '=',$request['email'])->firstOrFail();
      //Auth::login($user);
      //Session::save();
      return redirect()->back();
   }

   


}
