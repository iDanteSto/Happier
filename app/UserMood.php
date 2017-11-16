<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMood extends Model
{
    


	protected $fillable = [
        'mood','fk_user_Id', 'userMood_Id','created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       
    ];


protected $table = "usermood";

public $timestamps = false;
protected $primaryKey = 'userMood_Id';
}
