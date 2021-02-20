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
    </x-slot>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    @foreach(User::entityFields() as $field)
                    @if(@$field['formOnly'] == true) @continue @endif
                    <div class="row mt-2">
                        <div class="col-12 col-md-3">
                            <label class="col-form-label font-weight-bold">{{ @$field['label'] }}</label>
                        </div>
                        <div class="col">
                            : {{ $user[$field['name']] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>