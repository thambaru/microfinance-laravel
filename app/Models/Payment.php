<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'amount' => 'float',
    ];

    public static function entityFields()
    {
        return [
            [
                'label' => 'Loan No.',
                'name' => 'loan_id',
                'type' => 'select',
                'selectOptions' => Loan::all(),
                'selectOptionNameField' => 'id',
                'attributes' => 'required'
            ],
            [
                'label' => 'Amount',
                'name' => 'amount',
                'attributes' => 'required mask-money'
            ],
            [
                'label' => 'Sales Rep',
                'name' => 'rep_id',
                'type' => 'select',
                'selectOptions' => User::whereRoleIs('rep')->get(),
                'selectOptionNameField' => 'full_name',
                'attributes' => 'required'
            ],
        ];
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("Y-m-d H:i:s");
    }

    public static function lastNMonths($months = 12)
    {
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {

            $data[] = [
                'i' => $i,
                'month' => Carbon::now()->subMonths($i)->format('M'),
                'sum' => Payment::whereBetween('created_at', [
                    Carbon::now()->subMonths($i)->startOfMonth()->format("Y-m-d 00:00:00"),
                    Carbon::now()->subMonths($i)->endOfMonth()->format("Y-m-d 00:00:00"),
                ])->sum('amount')
            ];
        }

        return $data;
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }
}
