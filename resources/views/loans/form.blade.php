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

    <div class="card">
        <div class="card-body">

            <form action="{{route('loans.store')}}" method="POST">
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
                        'label' => 'Monthly Interest Rate (%)',
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
                ];

                ?>
                @foreach($fields as $field)
                <div class="row mt-2">
                    <div class="col-12 col-md-2">
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
        </div>
    </div>

    @section('scripts')
    <script src="{{asset('lib/jquery-maskmoney/jquery.maskMoney.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('[mask-money]').maskMoney();
        });
    </script>
    @endsection
</x-app-layout>