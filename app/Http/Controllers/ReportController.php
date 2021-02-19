<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function show(Request $request, $type = "excess")
    {
        if (empty($request->ajax))
            return view("reports.index", ['reportType' => $type]);

        $loans = Loan::with('customer');

        switch ($type) {
            case ('arrears'):
                $loans->whereRaw('total_due_todate > total_paid');
                break;
            case ('excess'):
            default:
                $loans->whereRaw('total_due_todate < total_paid');
        }

        $loans = $loans->get();

        return compact('loans');
    }
}
