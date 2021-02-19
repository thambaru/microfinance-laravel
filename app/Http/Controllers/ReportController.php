<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function show(Request $request, $type = "excess")
    {
        if (empty($request->ajax))
            return view("reports.show", ['reportType' => $type]);

        $customers = Customer::whereHas('loans', function ($q) use ($type) {
            switch ($type) {
                case ('arrears'):
                    $q->whereRaw('total_due_todate > total_paid');
                    break;
                case ('excess'):
                default:
                    $q->whereRaw('total_due_todate < total_paid');
            }
        })->get();

        return compact('customers');
    }
}
