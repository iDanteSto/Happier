<?php
namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\User;
	use DB;

	class notificationController extends Controller
	{
/*
|--------------------------------------------------------------------------
| Refresh token
|--------------------------------------------------------------------------
|
| refresh token of the device on DB
| expects : email and devicetoken
*/
public function refreshtokenDB(Request $request)
{
$user = User::where('email', '=', $request->email)->firstOrFail();//get hidden info of the session to compare and retrieve of the database
$userid = $user->user_Id;//place id on a variable to use it 

DB::table('users')
->where('user_Id', $userid)
->update(['devicetoken' => $request->devicetoken]);


return response()->json(['succes'=> 'Succes! '], 200);
}

	}
