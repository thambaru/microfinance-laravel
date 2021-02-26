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
                'attributes' => 'required'
            ],
            [
                'label' => 'Profession',
                'name' => 'profession',
                'attributes' => 'required'
            ],
            [
                'label' => 'NIC Number',
                'name' => 'nic',
                'attributes' => 'required'
            ],
            [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'attributes' => 'required'
            ],
            [
                'label' => 'Address',
                'name' => 'address',
                'attributes' => 'required'
            ],
            [
                'label' => 'Phone Number',
                'name' => 'phone_num',
                'attributes' => 'required'
            ],
        ];
    }
}
