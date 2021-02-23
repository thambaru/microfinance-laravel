<?php

use App\Models\Customer;
use App\Models\Loan;
use App\Models\Guarantor;
use App\Libraries\Common;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

?>
<x-app-layout>

    <x-slot name="title">
        Loan #{{$loan->id}} @if(!$loan->is_active) [Closed] @endif
        <a href="{{route('loans.edit', $loan->id)}}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-edit"></i>
            </span>
            <span class="text">Edit</span>
        </a>
        <a href="{{route('payments.create', ['loan-id' => $loan->id])}}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-file-invoice-dollar"></i>
            </span>
            <span class="text">Add Payment</span>
        </a>
        @role('admin')

        @if($loan->is_active)
        <a href="{{route('payments.create', ['loan-id' => $loan->id])}}" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-window-close"></i>
            </span>
            <span class="text">Close</span>
        </a>
        @endif

        <a href="{{route('payments.create', ['loan-id' => $loan->id])}}" class="btn btn-danger btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-trash"></i>
            </span>
            <span class="text">Delete</span>
        </a>
        @endrole
    </x-slot>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h6 class="font-weight-bold text-primary">Loan details</h6>
                </div>
                <div class="card-body">
                    @foreach(Loan::entityFields() as $field)
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <label class="col-form-label font-weight-bold">{{ @$field['label'] }}</label>
                        </div>
                        <div class="col mt-2">
                            @switch($field['name'])
                            @case('proof_doc')

                            <ul>
                                @foreach($loan[$field['name']] as $file)

                                <li>
                                    <a href="{{ asset('storage/proof_docs/' . basename($file)) }}" target="_blank">{{ basename($file) }}</a>
                                </li>

                                @endforeach
                            </ul>
                            @break
                            @default
                            : {{ $loan[$field['name']] }}
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
                            : {{ $loan->customer[$field['name']] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @for($i=0; $i < 2; $i++) <div class="col-6">
            <div class="card my-2">
                <div class="card-header">
                    <h6 class="font-weight-bold text-primary">Guarantor {{$i + 1}}</h6>
                </div>
                <div class="card-body">
                    @foreach(Guarantor::entityFields() as $field)
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label class="col-form-label">{{@$field['label']}}</label>
                        </div>
                        <div class="col mt-2">
                            : {{ @$loan->guarantors[$i][$field['name']] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
    </div>

    @endfor
    </div>
</x-app-layout>