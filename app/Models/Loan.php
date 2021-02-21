<?php

namespace App\Models;

use App\Libraries\Common;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Loan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['last_payment'];

    public static function entityFields()
    {
        return [
            [
                'label' => 'Customer',
                'name' => 'customer_id',
                'type' => 'select',
                'selectOptions' => Customer::all(),
                'selectOptionNameField' => 'full_name',
                'attributes' => 'required'
            ],
            [
                'label' => 'Amount',
                'name' => 'loan_amount',
                'attributes' => 'required mask-money'
            ],
            [
                'label' => 'Interest (%)',
                'name' => 'int_rate_mo',
                'attributes' => 'required mask-money'
            ],
            [
                'label' => 'Pay in Months',
                'name' => 'installments',
                'type' => 'number',
                'attributes' => 'required min=1'
            ],
            [
                'label' => 'Monthly Rental',
                'name' => 'rental',
                'attributes' => 'readonly mask-money'
            ],
            [
                'label' => 'Starting Date',
                'name' => 'start_date',
                'type' => 'date',
                'attributes' => 'required'
            ],
            [
                'label' => 'Sales Rep',
                'name' => 'rep_id',
                'type' => 'select',
                'selectOptions' => User::all(),
                'selectOptionNameField' => 'full_name',
                'attributes' => 'required'
            ],
            [
                'label' => 'Proof document',
                'name' => 'proof_doc',
                'type' => 'file'
            ],
        ];
    }

    public static function proofDocumentsPath()
    {
        return storage_path() . "/app/public/proof_docs/";
    }

    public function getProofDocAttribute()
    {
        return File::glob($this->proofDocumentsPath() . "*_" . $this->id . ".*");
    }

    public function getLastPaymentAttribute()
    {
        $lastPayment = $this->payments()->orderBy('id', 'desc')->first();
        return empty($lastPayment) ? ['created_at' => 'Never'] : $lastPayment;
    }

    public function getLastDailyRecordAttribute()
    {
        return $this->dailyRecords()->orderBy('id', 'desc')->first();
    }

    public function getDailyRentalAttribute()
    {
        $interestPercentage = floatval($this->int_rate_mo) / 100;
        $loanAmount = floatval($this->loan_amount);
        $loanInterest = $loanAmount * $interestPercentage;
        $loanWithInterest = $loanAmount + $loanInterest;

        $date = Carbon::parse($this->start_date)->addMonths($this->installments);
        $now = Carbon::now();

        $diff = $date->diffInDays($now);

        return Common::getInCurrencyFormat($loanWithInterest / $diff);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function dailyRecords()
    {
        return $this->hasMany(DailyRecord::class);
    }
}
