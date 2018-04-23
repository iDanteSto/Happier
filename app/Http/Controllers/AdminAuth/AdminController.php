<?php

namespace App\Http\Controllers\AdminAuth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\AdminUser;
use Hash;
use DB;
use View;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nickname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
public function showRegistrationForm()
    {
         if(Auth::guard('admin_user')->user()->level == '1')
            {
                $availableAdmins ['availableAdmins'] = DB::table('admin_users')->orderBy('level' , 'desc')->get();  
                //$currentUser = Auth::guard('admin_user')->user();
               
                return View::make('admin-auth.adminspage')->with($availableAdmins);
                //->with($currentUser)
            }
            return redirect('/dashboard');
        //return view('auth.register');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
public function create(Request $request)
    {
    
        /*
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
*/
//if fails to succes one of the rules , display errors
$AdminUser = new AdminUser([
//fields to be taken from the post and placed on the DB
'name' => $request->input('name'),
'email' => $request->input('email'),
'password' => Hash::make($request->input('password')),
]);
$AdminUser->save();//if success will throw a succes message      

return redirect('/adminspage');      
}

public function create_guest(Request $request)
{
    
//if fails to succes one of the rules , display errors
$AdminUser = new AdminUser([
//fields to be taken from the post and placed on the DB
'name' => $request->input('name'),
'email' => $request->input('email'),
'password' => Hash::make($request->input('password')),
'level' => 0,
]);
$AdminUser->save();//if success will throw a succes message      
return redirect('/adminspage');      
}

public function DeleteAdmin($id)
{
DB::table('admin_users')->where('id', '=', $id)->delete();
return redirect('/adminspage');   
}

     
    
}
