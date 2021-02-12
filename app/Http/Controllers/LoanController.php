<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (empty($request->ajax))
            return view('loans.index');

        $loans = Loan::with('customer')->get();

        return compact('loans');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('loans.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = [
            'customer_id' => 'required|numeric',
            'rep_id' => 'required|numeric',
            'loan_amount' => 'required',
            'int_rate_mo' => 'required',
            'start_date' => 'required',
            'installments' => 'required',
            'rental' => 'required',
        ];

        $request->validate($fields);

        $isEdit = empty($request->id);

        $loan = $isEdit ? new Loan() : Loan::find($request->id);

        foreach ($fields as $key => $val)
            $loan->$key = $request->$key;

        $loan->save();

        return redirect()->back()->with('status', "success");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        return view('loans.form', compact('loan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();

        return redirect()->route('loans.index')->with('status', "Loan ID #$loan->id was deleted.");
    }
}
