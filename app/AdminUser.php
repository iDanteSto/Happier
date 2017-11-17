<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends User
{


protected $fillable = [
        'name', 'email', 'password',
    ];
    
protected $table = "admin_users";



}
