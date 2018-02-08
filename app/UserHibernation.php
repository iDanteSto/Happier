<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHibernation extends Model
{
    


	protected $fillable = [
        'hibernation_Id','name', 'duration','fk_user_Id', 'creation_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       
    ];


protected $table = "userhibernation";

public $timestamps = false;
protected $primaryKey = 'hibernation_Id';
}
