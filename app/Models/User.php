<?php

namespace App\Models;

use App\Scopes\UserScope;
use Carbon\Carbon;
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


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }

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

    public function getMonthlyEarningsAttribute()
    {
        return $this->commissTransactions()
            ->whereBetween('created_at', [
                Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00'),
                Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59')
            ])
            ->sum('amount');
    }

    public function getCustomersAttribute()
    {
        $customerIds = [];

        foreach ($this->loans as $loan)
            $customerIds[] = $loan->customer_id;

        return Customer::find($customerIds);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'rep_id');
    }

    public function commissTransactions()
    {
        return $this->hasMany(CommissTransaction::class, 'rep_id');
    }
}
