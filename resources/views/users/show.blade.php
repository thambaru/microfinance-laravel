<?php

use App\Models\User;
use App\Libraries\Common;
?>
<x-app-layout>

    <x-slot name="title">
        User #{{$user->id}}
        <a href="{{route('users.edit', $user->id)}}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-edit"></i>
            </span>
            <span class="text">Edit</span>
        </a>
        <a href="{{route('commissions.create', ['rep-id' => $user->id])}}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-file-invoice-dollar"></i>
            </span>
            <span class="text">Pay Commission</span>
        </a>
    </x-slot>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Earnings (Monthly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{$user->monthly_earnings}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="font-weight-bold text-primary">Rep details</h6>
                </div>
                <div class="card-body">
                    @foreach(User::entityFields() as $field)
                    @if(@$field['formOnly'] == true) @continue @endif
                    <div class="row mt-2">
                        <div class="col-12 col-md-3">
                            <label class="col-form-label font-weight-bold">{{ @$field['label'] }}</label>
                        </div>
                        <div class="col mt-2">
                            : {{ $user[$field['name']] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>