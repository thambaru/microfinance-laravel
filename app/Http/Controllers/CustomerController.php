<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (empty($request->ajax))
            return view('customers.index');

        $customers = Customer::all();

        return compact('customers');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.form');
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
            'full_name' => ['required', 'max:255'],
            'nic' => 'required',
            'email' => 'email' . $isEdit ? '' : '|unique:customers'
        ]);

        $customer = $isEdit ? Customer::find($request->id) : new Customer();

        $customer->full_name = $request->full_name;
        $customer->email = $request->email;
        $customer->nic = $request->nic;
        $customer->address = $request->address;
        $customer->address_nic = $request->address_nic;
        $customer->address_bus = $request->address_bus;
        $customer->profession = $request->profession;
        $customer->phone_num = $request->phone_num;

        $customer->save();

        return redirect()->back()->with('status', "success");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('status', "$customer->full_name was deleted.");
    }
}
