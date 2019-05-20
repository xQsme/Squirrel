<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{

    protected $table = 'logins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
