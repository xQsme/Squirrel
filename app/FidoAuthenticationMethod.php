<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FidoAuthenticationMethod extends Model
{

    protected $table = 'fido_authentication_methods';

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

    protected $attributeModifiers = [
        'credentialId' => Base64Encoder::class,
        'AAGUID' => Base64Encoder::class,
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
