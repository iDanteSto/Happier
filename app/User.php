<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use \Illuminate\Auth\Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'password','verifyToken', 'remember_token', 'devicetoken','user_Id','imagelink','displayname','created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password' ,'remember_token','status',
    ];

    //protected $primaryKey = 'user_Id';

   

    function socialProviders()
    {
        return $this->hasMany(socialProvider::class);
    }
   
}
