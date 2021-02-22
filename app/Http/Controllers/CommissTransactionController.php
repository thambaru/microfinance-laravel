<?php

namespace App\Http\Controllers;

use App\Libraries\Common;
use App\Models\CommissTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class CommissTransactionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (empty($request->ajax))
            return view('commissions.index');

        $commissions = CommissTransaction::with('rep');

        if ($request->has('from') && $request->has('to'))
            $commissions = $commissions->whereBetween('created_at', ["$request->from 00:00:00", "$request->to 23:59:59"]);

        if ($request->has('rep_id'))
            $commissions = $commissions->whereHas('rep', function ($q) use ($request) {
                $q->where('id', $request->rep_id);
            });

        $commissions = $commissions->get();

        return compact('commissions');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('commissions.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'rep_id' => 'required',
            'amount' => 'required',
        ]);

        $rep = User::find($request->rep_id);

        $commission = new CommissTransaction();

        $commission->amount = $request->amount;
        $commission->rep_id = $request->rep_id;
        $commission->type = Common::$CommissTransactionTypes['commissionPayment'];
        $commission->balance = $rep->commiss_bal - $request->amount;

        $commission->save();

        $rep->commiss_bal += $request->amount;
        $rep->save();

        return redirect()->back()->with('status', $commission);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CommissTransaction  $commissTransaction
     * @return \Illuminate\Http\Response
     */
    public function show($commissTransaction)
    {
        $commissTransaction = CommissTransaction::find($commissTransaction)->with('rep')->first();

        return view('commissions.show', compact('commissTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CommissTransaction  $commissTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(CommissTransaction $commissTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommissTransaction  $commissTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommissTransaction $commissTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CommissTransaction  $commissTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommissTransaction $commissTransaction)
    {
        //
    }

    /**
     * Sends the user object.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function getRep($id)
    {
        return User::find($id);
    }
}
