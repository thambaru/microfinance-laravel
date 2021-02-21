<?php

namespace App\Console\Commands;

use App\Models\DailyRecord;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateDailyRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update daily records';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->format('Y-m-d 00:00:00');

        $loans = Loan::where('is_active', 1)->get();

        foreach ($loans as $loan) {
            $dailyInstallment = $loan->payments
                // ->whereBetween('created_at', [$today, Carbon::now()->format('Y-m-d 23:59:59')])
                ->sum('amount');

            if (empty($loan->last_daily_record)) {
                $accruedAmount = $loan->loan_amount;
                $accumilativeAmount = $dailyInstallment;
            } else {
                $accruedAmount = $loan->last_daily_record->accrued_am - $loan->last_daily_record->paid_am;
                $accumilativeAmount = $loan->last_daily_record->accumulat_am + $dailyInstallment;
            }

            /*
                Difference between today's paid installment vs Daily Rental.
                Excess: if the answer is +.
                Arrears: if the answer is -.
            */
            $paymentDiff = $loan->daily_rental - $dailyInstallment;

            $arrearsTotal = 0;
            $excessTotal = 0;
            if ($paymentDiff > 0)
                $arrearsTotal = $paymentDiff;
            else
                $excessTotal = abs($paymentDiff);

            /*
                Create Record using above calculation
            */
            $dailyRecord = new DailyRecord();

            $dailyRecord->date = $today;
            $dailyRecord->loan_id = $loan->id;
            $dailyRecord->accrued_am = $accruedAmount;
            $dailyRecord->paid_am = $dailyInstallment;
            $dailyRecord->accumulat_am = $accumilativeAmount;
            $dailyRecord->arrears_tot = $arrearsTotal;
            $dailyRecord->excess_tot = $excessTotal;

            $dailyRecord->save();
        }

        $this->info("Completed {$loans->count()} daily records.");
    }
}
