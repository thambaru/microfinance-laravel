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
    
    public static $CommissTransactionTypes = [
        'onLoanClose' => 0,
        'commissionPayment' => 1
    ];

    public static function isRoute($name)
    {
        return Str::startsWith(Route::currentRouteName(), $name);
    }

    public static function getInCurrencyFormat($value)
    {
        return (float) number_format($value, 2, '.', "");
    }

}
