<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static function entityFields()
    {
        return [
            [
                'label' => 'Full Name',
                'name' => 'full_name',
                'attributes' => 'required'
            ],
            [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'label' => 'NIC Number',
                'name' => 'nic',
                'attributes' => 'required'
            ],
            [
                'label' => 'Address in NIC',
                'name' => 'address_nic'
            ],
            [
                'label' => 'Home Address',
                'name' => 'address'
            ],
            [
                'label' => 'Business Address',
                'name' => 'address_bus'
            ],
            [
                'label' => 'Profession',
                'name' => 'profession'
            ],
            [
                'label' => 'Phone Number',
                'name' => 'phone_num'
            ],
        ];
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
