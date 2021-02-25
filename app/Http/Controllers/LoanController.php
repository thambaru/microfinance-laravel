<?php

namespace App\Http\Controllers;

use App\Models\CommissTransaction;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $user = User::find(Auth::id());

        $loans = Loan::with('customer', 'guarantors');

        $loans = $user->hasRole('rep') ? $loans->where('rep_id', $user->id)->get() : $loans->get();

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
        $isEdit = !empty($request->id);

        $fields = [
            'customer_id' => 'required|numeric',
            'rep_id' => 'required|numeric',
            'loan_amount' => 'required',
            'int_rate_mo' => 'required',
            'start_date' => 'required',
            'installments' => 'required',
            'rental' => 'required',
            'proof_doc' => 'file' . $isEdit ? '' : '|required',
        ];

        $guarantorFields = [
            // 'guarantors.*.full_name' => 'required',
            'guarantors.*.nic' => 'required_with:guarantors.*.full_name',
            'guarantors.*.email' => 'email' . $isEdit ? '' : '|unique:guaranters',
        ];

        $request->validate(array_merge($fields, $guarantorFields));


        $loan = $isEdit ? Loan::find($request->id) : new Loan();

        $loan->customer_id = $request->customer_id;
        $loan->rep_id = $request->rep_id;
        $loan->loan_amount = $request->loan_amount;
        $loan->int_rate_mo = $request->int_rate_mo;
        $loan->installments = $request->installments;
        $loan->start_date = $request->start_date;
        $loan->rental = $request->rental;

        if (!$isEdit)
            $loan->total_due_todate = $request->loan_amount;

        $loan->save();

        if ($request->hasFile('proof_doc')) {
            $fileName = Carbon::now()->format("Y-m-d-H:i") . "_$loan->id." . $request->file('proof_doc')->extension();

            $request->file('proof_doc')->storeAs('proof_docs', $fileName, 'public');
        }

        if ($isEdit)
            $loan->guarantors()->delete();

        foreach ($request->guarantors as $guarantor) {

            if (empty($guarantor['full_name']))
                continue;

            $newGuarantor = new Guarantor();

            $newGuarantor->full_name = $guarantor['full_name'];
            $newGuarantor->profession = $guarantor['profession'];
            $newGuarantor->nic = $guarantor['nic'];
            $newGuarantor->email = $guarantor['email'];
            $newGuarantor->address = $guarantor['address'];
            $newGuarantor->phone_num = $guarantor['phone_num'];

            $loan->guarantors()->save($newGuarantor);
        }

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
        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        $loan = Loan::with('customer', 'guarantors')->find($loan->id);

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

    /**
     * Sends the customer object.
     *
     * @param  $loan
     * @return \Illuminate\Http\Response
     */
    public function getCustomer($loan)
    {
        return Loan::find($loan)->with('customer')->first();
    }

    /**
     * Closes the loan by setting is_active = 0
     * and Set payable commission to the rep.
     *
     * @param  $loan
     * @return \Illuminate\Http\Response
     */
    public static function closeLoan(Loan $loan)
    {
        $loan->is_active = 0;
        $loan->save();

        $paidAmount = $loan->payments()->sum('amount');

        $commissTransaction = new CommissTransaction();

        $repPercentage = $loan->loan_amount * $loan->rep->commiss_perc / 100;
        $repCommission = $paidAmount * $repPercentage;

        $commissTransaction->rep_id = $loan->rep_id;
        $commissTransaction->amount =  $repCommission;
        $commissTransaction->balance = $loan->rep->commis_bal - $repCommission;
        $commissTransaction->type =  0;

        $commissTransaction->save();
    }
}
