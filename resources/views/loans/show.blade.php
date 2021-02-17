<?php

use App\Models\Customer;
use App\Models\Loan;
use App\Models\Guarantor;
use App\Libraries\Common;
?>
<x-app-layout>

    <x-slot name="title">
        Loan #{{$loan->id}}
        <a href="{{route('loans.edit', $loan->id)}}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-edit"></i>
            </span>
            <span class="text">Edit</span>
        </a>
    </x-slot>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Loan details</h5>
                    <hr class="mb-2" />
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
                <div class="card-body">
                    <h5 class="card-title">Customer details</h5>
                    <hr class="mb-2" />
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
                <div class="card-body">

                    <div class="row mt-2">
                        <div class="col-12 col-md-3">
                            <h1 class="font-weight-bold mb-3">Guarantor {{$i + 1}}</h1>
                        </div>
                    </div>
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