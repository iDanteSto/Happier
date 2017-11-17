<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\AdminUser;
use Hash;

class AvatarContent extends Controller
{
    








public function showAvatarContent()
    {
            if(Auth::guard('admin_user')->user())
            {
                return view('admin-auth.avatar_categories');
            }
            return redirect('/dashboard');

    }























}
