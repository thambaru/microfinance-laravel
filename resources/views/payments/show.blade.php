<?php

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Guarantor;
use App\Libraries\Common;
?>
<x-app-layout>

    <x-slot name="title">
        Payment Receipt
        <a href="{{route('payments.receipt', $payment->id)}}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-print"></i>
            </span>
            <span class="text">Print</span>
        </a>
    </x-slot>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h6 class="font-weight-bold text-primary">Payment details</h6>
                </div>
                <div class="card-body">
                    @foreach(Payment::entityFields() as $field)
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <label class="col-form-label font-weight-bold">{{ @$field['label'] }}</label>
                        </div>
                        <div class="col mt-2">
                            @switch($field['name'])
                            @case('rep_id')
                            : {{ $payment->rep->name }}
                            @break
                            @default
                            : {{ $payment[$field['name']] }}
                            @endswitch
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="font-weight-bold text-primary">Customer details</h6>
                </div>
                <div class="card-body">
                    @foreach(Customer::entityFields() as $field)
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label class="col-form-label font-weight-bold">{{ @$field['label'] }}</label>
                        </div>
                        <div class="col mt-2">
                            : {{ $payment->loan->customer[$field['name']] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>