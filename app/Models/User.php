<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'password', 'role_id', 'otp', 'provider', 'provider_id', 'device_token'
    ];

/**
     * Challanges' Status
    *
    * @var array
    */
    public const Provider = [
        'facebook'  => 1,
        'google'    => 2
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
     'password', 'remember_token', 'created_at', 'updated_at'
    ];

    public function team()
    {
        return $this->hasOne('App\Models\Team', 'captain_id');
    }

    public static function providerWrapper($reqestProvider)
    {
       return self::Provider[$reqestProvider];
    }

    public static function getStatusAttribute($value)
    {
        return array_search($value, self::Provider);
    }


}
