<?php

use App\Models\User;

$isEdit = !empty($user);
?>


<x-app-layout>
    <x-slot name="title">
        @if(!$isEdit)Create a @else Edit @endif User
    </x-slot>

    @if (session('status'))
    <div class="alert alert-success">
        User has been @if(!$isEdit) created. @else edited. @endif <u><a href="{{route('users.index')}}">View the list</a></u>
    </div>
    @endif

    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card">
                <div class="card-body">

                    <form action="{{route('users.store')}}" method="POST">
                        @csrf

                        @if($isEdit)
                        <input type="hidden" name="id" value="{{$user->id}}" />
                        @endif

                        @foreach(User::entityFields() as $field)
                        <div class="row mt-2">
                            <div class="col-12 col-md-3">
                                <label class="col-form-label">{{@$field['label']}}</label>
                            </div>
                            <div class="col">
                                <input class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" type="{{@$field['type']}}" {{@$field['attributes']}} value="@if($isEdit && $field['name'] != 'password'){{ $user[$field['name']] }}@else{{ old($field['name']) }}@endif">
                                
                                @error($field['name'])
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endforeach
                        <div class="row mt-3">

                            <div class="col text-center">
                                <button type="submit" class="btn btn-block btn-primary">Submit</button>
                            </div>
                            @if($isEdit)
                            <div class="col-1 text-center">
                                <a href="#" class="btn btn-danger" onclick="triggerDeleteForm(event, 'user-delete-form')"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                            @endif
                        </div>
                    </form>
                    @if($isEdit)
                    <form id="user-delete-form" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>