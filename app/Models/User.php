<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function entityFields()
    {
        return [
            [
                'label' => 'Full Name',
                'name' => 'full_name',
                'attributes' => 'required'
            ],
            [
                'label' => 'Name',
                'name' => 'name',
                'attributes' => 'required'
            ],
            [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'label' => 'Password (min: 8)',
                'name' => 'password',
                'type' => 'password',
                'formOnly' => true
            ],
            [
                'label' => 'Confirm Password',
                'name' => 'password_confirmation',
                'type' => 'password',
                'formOnly' => true
            ],
            [
                'label' => 'NIC Number',
                'name' => 'nic',
                'attributes' => 'required'
            ],
            [
                'label' => 'Home Address',
                'name' => 'address'
            ],
            [
                'label' => 'Phone Number',
                'name' => 'phone_num'
            ],
            [
                'label' => 'Commission Percentage (%)',
                'name' => 'commiss_perc'
            ],
        ];
    }
}
