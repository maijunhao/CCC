<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //
    public $table = 'users';


    public function userinfo()
    {
        return $this->hasOne('App\Models\UsersInfo','uid');
    }
}
