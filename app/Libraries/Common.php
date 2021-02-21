<?php

namespace App\Libraries;

use App\Models\Loan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Common
{

    public static $userRoles = [
        'admin' => 1,
        'rep' => 2
    ];

    public static function isRoute($name)
    {
        return Str::startsWith(Route::currentRouteName(), $name);
    }

    public static function getInCurrencyFormat($value)
    {
        return (float) number_format($value, 2, '.', "");
    }

    public static function getFullLoanAmount($loanId)
    {
        $loan = Loan::find($loanId);

        if (empty($loan))
            return "Inavlid Loan ID";

        $interestPercentage = floatval($loan->int_rate_mo) / 100;
        $amount = floatval($loan->loan_amount);

        $fullAmount = $amount + ($amount * $interestPercentage) / $loan->installments;

        return self::getInCurrencyFormat($fullAmount);
    }
}
