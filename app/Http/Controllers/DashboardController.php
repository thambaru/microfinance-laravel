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
     * Display dashboard reports for Admin
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRole('rep'))
            return $this->repIndex($request);

        $overallPaymentsVSLoans = [
            'payments' => Payment::lastNMonths(),
            'loans' => Loan::lastNMonths(),
        ];

        $startOfTheMonth = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $endOfTheMonth = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');

        $startOfTheDay = Carbon::now()->format('Y-m-d 00:00:00');
        $endOfTheDay = Carbon::now()->format('Y-m-d 23:59:59');

        $monthPaymentTotal = Payment::with('rep')
            ->whereBetween('created_at', [
                $startOfTheMonth,
                $endOfTheMonth
            ])
            ->sum('amount');

        $dailyPayments = Payment::with('loan.customer')
            ->whereBetween('created_at', [
                $startOfTheDay,
                $endOfTheDay
            ])
            ->get();

        $unpaidCustomers = Loan::with('customer')->where('is_active', 1)
            ->doesntHave('payments')
            ->get();

        $monthlyTotalLoanValue = Loan::whereBetween('created_at', [
            $startOfTheMonth,
            $endOfTheMonth
        ])
            ->sum('loan_amount');

        $totalActiveLoans = Loan::where('is_active', 1)->count();

        $totalActiveCustomers = Payment::whereBetween('created_at', [
                $startOfTheMonth,
                $endOfTheMonth
            ])
            ->groupBy('loan_id')
            ->count();

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

    /**
     * Display dashboard reports for Rep
     * 
     * @return \Illuminate\Http\Response
     */
    public function repIndex(Request $request)
    {
    }
}
