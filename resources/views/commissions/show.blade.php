<?php

use App\Models\CommissTransaction;
use App\Models\User;
use App\Libraries\Common;
?>
<x-app-layout>

    <x-slot name="title">
        Commission Receipt
    </x-slot>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Commission details</h5>
                    <hr class="mb-2" />
                    @foreach(CommissTransaction::entityFields() as $field)
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <label class="col-form-label font-weight-bold">{{ @$field['label'] }}</label>
                        </div>
                        <div class="col mt-2">
                            @switch($field['name'])
                            @case('rep_id')
                            : {{ $commissTransaction->rep->name }}
                            @break
                            @default
                            : {{ $commissTransaction[$field['name']] }}
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
                    <h5 class="card-title">Rep details</h5>
                    <hr class="mb-2" />
                    @foreach(User::entityFields() as $field)
                    @if(@$field['formOnly']) @continue @endif
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label class="col-form-label font-weight-bold">{{ @$field['label'] }}</label>
                        </div>
                        <div class="col mt-2">
                            : {{ $commissTransaction->rep[$field['name']] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>