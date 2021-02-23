<?php

use App\Models\Customer;
use App\Libraries\Common;
?>
<x-app-layout>

    <x-slot name="title">
        Customer #{{$customer->id}}
        <a href="{{route('customers.edit', $customer->id)}}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-edit"></i>
            </span>
            <span class="text">Edit</span>
        </a>
    </x-slot>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    @foreach(Customer::entityFields() as $field)
                    <div class="row mt-2">
                        <div class="col-12 col-md-3">
                            <label class="col-form-label font-weight-bold">{{ @$field['label'] }}</label>
                        </div>
                        <div class="col mt-2">
                            : {{ $customer[$field['name']] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
            <div class="card-header">
                    <h6 class="font-weight-bold text-primary">Ongoing Loans</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @if($customer->loans->count() == 0)
                        <li class="list-group-item">No active loans</li>
                        @else
                        @foreach($customer->loans as $loan)
                        @if(!$loan->is_active) @continue @endif
                        <li class="list-group-item">
                            <a href="{{ route('loans.show', $loan->id )}}">
                                @if($loan->is_an_overdue_loan)
                                Rs. {{ $loan->full_loan_amount }} immediately
                                @else
                                Rs. {{ $loan->full_loan_amount / $loan->installments }} in {{ $loan->installments }} months
                                @endif
                            </a>
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>