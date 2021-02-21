<?php

namespace App\Http\Controllers;

use App\Models\CommissTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;

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

        // Create Rep Commission
        $commissTransaction = new CommissTransaction();

        $repPercentage = floatval($payment->rep->commiss_perc) / 100;
        $repCommission = $request->amount * $repPercentage;

        $commissTransaction->rep_id = $request->rep_id;
        $commissTransaction->amount =  $repCommission;
        $commissTransaction->balance = $payment->rep->commis_bal - $repCommission;
        $commissTransaction->type =  0;

        $commissTransaction->save();

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
}
