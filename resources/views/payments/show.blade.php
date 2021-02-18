<?php

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Guarantor;
use App\Libraries\Common;
?>
<x-app-layout>

    <x-slot name="title">
        Payment #{{$payment->id}}
        <a href="{{route('payments.edit', $payment->id)}}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-edit"></i>
            </span>
            <span class="text">Edit</span>
        </a>
        <a href="{{route('payments.edit', $payment->id)}}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-print"></i>
            </span>
            <span class="text">Print</span>
        </a>
    </x-slot>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payment details</h5>
                    <hr class="mb-2" />
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
                <div class="card-body">
                    <h5 class="card-title">Customer details</h5>
                    <hr class="mb-2" />
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