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

        $monthPaymentTotal = Payment::with('rep')
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'),
                Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59')
            ])
            ->sum('amount');

        $dailyPayments = Payment::with('customer')
            ->whereBetween('created_at', [
                Carbon::now()->format('Y-m-d 00:00:00'),
                Carbon::now()->format('Y-m-d 23:59:59')
            ])
            ->get();

        $overallPayments = Payment::select(
            DB::raw('sum(amount) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months")
        )
            ->groupBy('months')
            ->get();

        $monthTopLoans = Loan::with('rep')
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'),
                Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59')
            ])->get();

        $unpaidCustomers = Loan::with('customer')->whereBetween('created_at', [
            Carbon::now()->format('Y-m-d 00:00:00'),
            Carbon::now()->format('Y-m-d 23:59:59')
        ])
            ->doesntHave('payments')
            ->get();

        return view('dashboard', compact(
            'overallPaymentsVSLoans',
            'monthPaymentTotal',
            'dailyPayments',
            'overallPayments',
            'monthTopLoans',
            'unpaidCustomers'
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
