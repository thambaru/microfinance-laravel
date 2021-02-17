<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    protected $table = 'guaranters';

    use HasFactory;

    public static function entityFields()
    {
        return [
            [
                'label' => 'Full Name',
                'name' => 'full_name',
                // 'attributes' => 'required'
            ],
            [
                'label' => 'Profession',
                'name' => 'profession'
            ],
            [
                'label' => 'NIC Number',
                'name' => 'nic',
                // 'attributes' => 'required'
            ],
            [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'label' => 'Address',
                'name' => 'address'
            ],
            [
                'label' => 'Phone Number',
                'name' => 'phone_num'
            ],
        ];
    }
}
