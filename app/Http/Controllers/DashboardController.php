<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard reports
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::id());

        $overallPaymentsVSLoans = [
            'payments' => Payment::lastNMonths(),
            'loans' => Loan::lastNMonths(),
        ];

        $startOfTheMonth = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $endOfTheMonth = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');

        $startOfTheDay = Carbon::now()->format('Y-m-d 00:00:00');
        $endOfTheDay = Carbon::now()->format('Y-m-d 23:59:59');

        $monthPaymentTotal = Payment::whereBetween('created_at', [
                $startOfTheMonth,
                $endOfTheMonth
            ]);

        $dailyPayments = Payment::with('loan.customer')
            ->whereBetween('created_at', [
                $startOfTheDay,
                $endOfTheDay
            ]);

        $unpaidCustomers = Loan::with('customer')->where('is_active', 1)
            ->doesntHave('payments');

        $monthlyTotalLoanValue = Loan::whereBetween('created_at', [
            $startOfTheMonth,
            $endOfTheMonth
        ]);

        $totalActiveLoans = Loan::where('is_active', 1);

        $totalActiveCustomers = Payment::whereBetween('created_at', [
            $startOfTheMonth,
            $endOfTheMonth
        ])
            ->groupBy('loan_id');

        if ($user->hasRole('rep')) {
            $monthPaymentTotal = $monthPaymentTotal->where('rep_id', $user->id)->sum('amount');
            $dailyPayments = $dailyPayments->where('rep_id', $user->id)->get();
            $unpaidCustomers = $unpaidCustomers->where('rep_id', $user->id)->get();
            $monthlyTotalLoanValue = $monthlyTotalLoanValue->where('rep_id', $user->id)->sum('loan_amount');
            $totalActiveLoans = $totalActiveLoans->where('rep_id', $user->id)->count();
            $totalActiveCustomers = $totalActiveCustomers->where('rep_id', $user->id)->count();
        }else{
            $monthPaymentTotal = $monthPaymentTotal->sum('amount');
            $dailyPayments = $dailyPayments->get();
            $unpaidCustomers = $unpaidCustomers->get();
            $monthlyTotalLoanValue = $monthlyTotalLoanValue->sum('loan_amount');
            $totalActiveLoans = $totalActiveLoans->count();
            $totalActiveCustomers = $totalActiveCustomers->count();
        }

        return view('dashboard', compact(
            'overallPaymentsVSLoans',
            'monthPaymentTotal',
            'dailyPayments',
            'unpaidCustomers',
            'monthlyTotalLoanValue',
            'totalActiveLoans',
            'totalActiveCustomers'
        ));
    }
}
