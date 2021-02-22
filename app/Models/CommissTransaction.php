<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'float',
    ];

    public static function entityFields()
    {
        return [
            [
                'label' => 'Sales Rep',
                'name' => 'rep_id',
                'type' => 'select',
                'selectOptions' => User::whereRoleIs('rep')->get(),
                'selectOptionNameField' => 'full_name',
                'attributes' => 'required'
            ],
            [
                'label' => 'Amount (Rs.)',
                'name' => 'amount',
                'attributes' => 'required mask-money'
            ]
        ];
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }
}
