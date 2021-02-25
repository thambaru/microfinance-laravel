<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function show(Request $request, $type = "excess")
    {
        if (empty($request->ajax))
            return view("reports.index", ['reportType' => $type]);

        $user = User::find(Auth::id());

        $loans = Loan::with('customer');

        switch ($type) {
            case ('arrears'):
                $loans->whereRaw('total_due_todate > total_paid');
                break;
            case ('excess'):
            default:
                $loans->whereRaw('total_due_todate < total_paid');
        }

        $loans = $user->hasRole('rep') ? $loans->where('rep_id', $user->id)->get() : $loans->get();

        return compact('loans');
    }
}
