<?php

use App\Models\Customer;
use App\Models\User;

$isEdit = !empty($loan);
?>


<x-app-layout>
    <x-slot name="title">
        @if(!$isEdit)Create a @else Edit @endif Loan
    </x-slot>

    @if (session('status'))
    <div class="alert alert-success">
        Loan has been @if(!$isEdit) created. @else edited. @endif <u><a href="{{route('loans.index')}}">View the list</a></u>
    </div>
    @endif

    <form action="{{route('loans.store')}}" method="POST">
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="card">
                    <div class="card-body">

                        @csrf

                        @if($isEdit)
                        <input type="hidden" name="id" value="{{$loan->id}}" />
                        @endif

                        <?php

                        $fields = [
                            [
                                'label' => 'Customer',
                                'name' => 'customer_id',
                                'type' => 'select',
                                'selectOptions' => Customer::all(),
                                'selectOptionNameField' => 'full_name',
                                'attributes' => 'required'
                            ],
                            [
                                'label' => 'Amount',
                                'name' => 'loan_amount',
                                'attributes' => 'required mask-money'
                            ],
                            [
                                'label' => 'Interest (% p.m.)',
                                'name' => 'int_rate_mo',
                                'attributes' => 'required mask-money'
                            ],
                            [
                                'label' => 'Starting Date',
                                'name' => 'start_date',
                                'type' => 'date',
                                'attributes' => 'required'
                            ],
                            [
                                'label' => 'Installment Months',
                                'name' => 'installments',
                                'type' => 'number',
                                'attributes' => 'required min=0'
                            ],
                            [
                                'label' => 'Rental',
                                'name' => 'rental',
                                'attributes' => 'required mask-money'
                            ],
                            [
                                'label' => 'Sales Rep',
                                'name' => 'rep_id',
                                'type' => 'select',
                                'selectOptions' => User::all(),
                                'selectOptionNameField' => 'full_name',
                                'attributes' => 'required'
                            ],
                            [
                                'label' => 'Proof document',
                                'name' => 'proof_doc',
                                'type' => 'file',
                                'attributes' => 'required'
                            ],
                        ];

                        ?>
                        @foreach($fields as $field)
                        <div class="row mt-2">
                            <div class="col-12 col-md-3">
                                <label class="col-form-label">{{@$field['label']}}</label>
                            </div>
                            <div class="col">
                                @switch(@$field['type'])
                                @case('select')
                                <select class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" {{@$field['attributes']}} value="@if($isEdit){{ $loan[$field['name']] }}@else{{ old($field['name']) }}@endif">
                                    <option>Select {{@$field['label']}}</option>
                                    @foreach($field['selectOptions'] as $option)
                                    <option value="{{$option->id}}" @if($isEdit && $option->id == $loan[$field['name']]){{ 'selected' }}@endif>{{$option[$field['selectOptionNameField']]}}</option>
                                    @endforeach
                                </select>
                                @break
                                @default
                                <input class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" type="{{@$field['type']}}" {{@$field['attributes']}} value="@if($isEdit){{ $loan[$field['name']] }}@else{{ old($field['name']) }}@endif">
                                @endswitch
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">

            <?php

            $fields = [
                [
                    'label' => 'Full Name',
                    'name' => 'full_name',
                    'attributes' => 'required'
                ],
                [
                    'label' => 'Profession',
                    'name' => 'profession'
                ],
                [
                    'label' => 'NIC Number',
                    'name' => 'nic',
                    'attributes' => 'required'
                ],
                [
                    'label' => 'Email',
                    'name' => 'email',
                    'type' => 'email'
                ],
                [
                    'label' => 'Address',
                    'name' => 'address'
                ],
                [
                    'label' => 'Phone Number',
                    'name' => 'phone_num'
                ],
            ];
            ?>
            @for($i=0;$i<2;$i++) <div class="col-6">
                <div class="card">
                    <div class="card-body">

                        <div class="row mt-2">
                            <div class="col-12 col-md-3">
                                <h1 class="font-weight-bold mb-3">Guarantor {{$i + 1}}</h1>
                            </div>
                        </div>
                        @foreach($fields as $field)
                        <div class="row m-2">
                            <div class="col-12 col-md-3">
                                <label class="col-form-label">{{@$field['label']}}</label>
                            </div>
                            <div class="col">
                                <input class="form-control @error($field['name']) border border-danger @enderror" name="guarantor[{{$i}}][{{$field['name']}}]" {{@$field['attributes']}} @if($isEdit) value="@if($isEdit){{ $customer[$field['name']] }}@else{{ old($field['name']) }}@endif" @endif>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                </div>

                @endfor
        
        </div>

        <div class="row mt-3">

            <div class="col text-center">
                <button type="submit" class="btn btn-block btn-primary">Submit</button>
            </div>
            @if($isEdit)
            <div class="col-1 text-center">
                <a href="#" class="btn btn-danger" onclick="triggerDeleteForm(event, 'loan-delete-form')"><i class="fa fa-trash"></i> Delete</a>
            </div>
            @endif
        </div>

    </form>

    @if($isEdit)
    <form id="loan-delete-form" action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="d-none">
        @method('DELETE')
        @csrf
    </form>
    @endif

    @section('scripts')
    <script src="{{asset('lib/jquery-maskmoney/jquery.maskMoney.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('[mask-money]').maskMoney();
        });
    </script>
    @endsection
</x-app-layout>