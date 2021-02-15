<?php
$isEdit = !empty($customer);
?>


<x-app-layout>
    <x-slot name="title">
        @if(!$isEdit)Create a @else Edit @endif Customer
    </x-slot>

    @if (session('status'))
    <div class="alert alert-success">
        Customer has been @if(!$isEdit) created. @else edited. @endif <u><a href="{{route('customers.index')}}">View the list</a></u>
    </div>
    @endif

    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card">
                <div class="card-body">

                    <form action="{{route('customers.store')}}" method="POST">
                        @csrf

                        @if($isEdit)
                        <input type="hidden" name="id" value="{{$customer->id}}" />
                        @endif

                        <?php

                        $fields = [
                            [
                                'label' => 'Full Name*',
                                'name' => 'full_name',
                                'attributes' => 'required'
                            ],
                            [
                                'label' => 'Email',
                                'name' => 'email',
                                'type' => 'email'
                            ],
                            [
                                'label' => 'NIC Number*',
                                'name' => 'nic',
                                'attributes' => 'required'
                            ],
                            [
                                'label' => 'Address in NIC',
                                'name' => 'address_nic'
                            ],
                            [
                                'label' => 'Home Address',
                                'name' => 'address'
                            ],
                            [
                                'label' => 'Business Address',
                                'name' => 'address_bus'
                            ],
                            [
                                'label' => 'Profession',
                                'name' => 'profession'
                            ],
                            [
                                'label' => 'Phone Number',
                                'name' => 'phone_num'
                            ],
                        ];

                        ?>
                        @foreach($fields as $field)
                        <div class="row mt-2">
                            <div class="col-12 col-md-3">
                                <label class="col-form-label">{{@$field['label']}}</label>
                            </div>
                            <div class="col">
                                <input class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" {{@$field['attributes']}} @if($isEdit) value="@if($isEdit){{ $customer[$field['name']] }}@else{{ old($field['name']) }}@endif" @endif>
                            </div>
                        </div>
                        @endforeach
                        <div class="row mt-3">

                            <div class="col text-center">
                                <button type="submit" class="btn btn-block btn-primary">Submit</button>
                            </div>
                            @if($isEdit)
                            <div class="col-1 text-center">
                                <a href="#" class="btn btn-danger" onclick="triggerDeleteForm(event, 'customer-delete-form')"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                            @endif
                        </div>
                    </form>
                    @if($isEdit)
                    <form id="customer-delete-form" action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>