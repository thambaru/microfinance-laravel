<?php

namespace App\Models;

use App\Libraries\Common;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

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
                'selectOptions' => User::all(),
                'selectOptionNameField' => 'full_name',
                'attributes' => 'required'
            ],
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("Y-m-d H:i:s");
    }

    public function getAmountAttribute($value)
    {
        return Common::getInCurrencyFormat($value);
    }
}
