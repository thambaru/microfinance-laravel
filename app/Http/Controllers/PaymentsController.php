<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \PDF;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (empty($request->ajax))
            return view('payments.index');

        $user = User::find(Auth::id());

        $payments = Payment::with('rep', 'loan');

        if ($request->has('from') && $request->has('to'))
            $payments = $payments->whereBetween('created_at', ["$request->from 00:00:00", "$request->to 23:59:59"]);

        if ($request->has('rep_id'))
            $payments = $payments->whereHas('rep', function ($q) use ($request) {
                $q->where('id', $request->rep_id);
            });

        $payments = $payments->get();

        return compact('payments');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payments.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isEdit = !empty($request->id);

        $request->validate([
            'loan_id' => 'required',
            'rep_id' => 'required',
            'amount' => 'required',
        ]);

        $payment = $isEdit ? Payment::find($request->id) : new Payment();

        $payment->loan_id = $request->loan_id;
        $payment->amount = $request->amount;
        $payment->rep_id = $request->rep_id;

        $payment->save();

        return redirect()->back()->with('status', $payment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     *@param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        return view('payments.form', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('status', "Payment ID #$payment->id was deleted.");
    }

    /**
     * Return payment receipt in PDF
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function receipt(Payment $payment)
    {
        $user = User::find(Auth::id());
        if ($user->hasRole('rep') && $payment->rep_id != $user->id)
            return abort(403);

        $loanStart = $payment->loan->start_date->format('Y-m-d 00:00:00');
        $endOfToday = Carbon::now()->format('Y-m-d 23:59:59');

        $totalPaid = Loan::find($payment->loan->id)
            ->payments
            ->sum('amount');

        $paidToday = Loan::find($payment->loan->id)
            ->payments
            ->whereBetween('created_at', [$loanStart, $endOfToday])
            ->sum('amount');

        $now = Carbon::now();
        $dayDiff = $payment->loan->start_date->diffInDays($now);

        $arrears = $dayDiff * $payment->loan->daily_rental;

        $pdf = PDF::loadView('payments.invoice', compact('payment', 'totalPaid', 'paidToday', 'arrears'));
        return $pdf->download("invoice-{$payment->id}-{$payment->created_at}.pdf");
    }
}
