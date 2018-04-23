<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends User
{


protected $fillable = [
        'name', 'email', 'password', 'level',
    ];
    
protected $table = "admin_users";



}
