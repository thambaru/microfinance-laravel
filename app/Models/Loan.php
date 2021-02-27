<?php

namespace App\Models;

use App\Libraries\Common;
use App\Scopes\OwnerRoleScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Loan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['last_payment', 'outstanding_amount', 'daily_rental', 'status_text'];

    protected $casts = [
        'loan_amount' => 'float',
        'int_rate_mo' => 'float',
        'is_active' => 'boolean',
        'is_an_overdue_loan' => 'boolean',
        'installments' => 'integer',
        'start_date' => 'date',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new OwnerRoleScope);
    }

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
                'label' => 'Pay in Days',
                'name' => 'installments',
                'type' => 'number',
                'attributes' => 'required min=1'
            ],
            [
                'label' => 'Daily Rental',
                'name' => 'rental',
                'attributes' => 'readonly mask-money'
            ],
            [
                'label' => 'Starting Date',
                'name' => 'start_date',
                'attributes' => 'required date-field'
            ],
            [
                'label' => 'Sales Rep',
                'name' => 'rep_id',
                'type' => 'select',
                'selectOptions' => User::whereRoleIs('rep')->get(),
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

    public function getAmountInterestAttribute()
    {
        $interestPercentage = $this->int_rate_mo / 100;

        return $loanInterest = $this->loan_amount * $interestPercentage;
    }

    public function getFullLoanAmountAttribute()
    {
        $loanWithInterest = $this->loan_amount + $this->amount_interest;

        return Common::getInCurrencyFormat($loanWithInterest);
    }

    public function getDailyRentalAttribute()
    {
        $date = $this->start_date->addDays($this->installments);
        $now = Carbon::now();

        $diff = $date->diffInDays($now);

        if ($diff <= 0)
            return $this->full_loan_amount;

        return Common::getInCurrencyFormat($this->full_loan_amount / $diff);
    }

    public static function lastNMonths($months = 12)
    {
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {

            $data[] = [
                'i' => $i,
                'month' => Carbon::now()->subMonths($i)->format('M'),
                'sum' => Loan::whereBetween('created_at', [
                    Carbon::now()->subMonths($i)->startOfMonth()->format("Y-m-d 00:00:00"),
                    Carbon::now()->subMonths($i)->endOfMonth()->format("Y-m-d 00:00:00"),
                ])->sum('loan_amount')
            ];
        }

        return $data;
    }

    public function getRemainingDaysAttribute()
    {
        $now = Carbon::now();

        return $this->installments - $this->start_date->diffInDays($now);
    }

    public function getOutstandingAmountAttribute()
    {
        $paidAmount = $this->payments->sum('amount');
        return $this->loan_amount - $paidAmount;
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Ongoing' : 'Closed';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
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
