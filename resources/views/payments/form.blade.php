<?php

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Guarantor;
use App\Models\User;

$isEdit = !empty($payment);
?>


<x-app-layout>
    <x-slot name="title">
        @if(!$isEdit)Create a @else Edit @endif Payment
    </x-slot>

    @if (session('status'))
    <div class="alert alert-success">
        Payment has been @if(!$isEdit) created. @else edited. @endif <u><a href="{{route('payments.show', session('status')->id)}}">View</a></u>
    </div>
    @endif

    <form action="{{route('payments.store')}}" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">

                        @csrf

                        @if($isEdit)
                        <input type="hidden" name="id" value="{{$payment->id}}" />
                        @endif

                        @foreach(Payment::entityFields() as $field)
                        <div class="row mt-2">
                            @if(@$field['type'] != "hidden")
                            <div class="col-12 col-md-3">
                                <label class="col-form-label">{{@$field['label']}}</label>
                            </div>
                            @endif
                            <div class="col">
                                @switch(@$field['type'])
                                @case('select')
                                <select class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" {{@$field['attributes']}} value="{{ old($field['name'] , @$payment[$field['name']]) }}">
                                    <option value="">Select {{@$field['label']}}</option>
                                    @foreach($field['selectOptions'] as $option)
                                    <option value="{{$option->id}}" @if($isEdit && $option->id == $payment[$field['name']] || $option->id == old($field['name']) ){{ 'selected' }}@endif>{{$option[$field['selectOptionNameField']]}}</option>
                                    @endforeach
                                </select>
                                @break
                                @default
                                <input class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" type="{{@$field['type']}}" {{@$field['attributes']}} @if(!in_array($field['name'], ['proof_doc'])) value="{{ old($field['name'] , @$payment[$field['name']]) }}" @endif>
                                @endswitch
                            </div>
                        </div>
                        @endforeach
                        <div class="row mt-3">

                            <div class="col text-center">
                                <button type="submit" class="btn btn-block btn-primary">Submit</button>
                            </div>
                            @if($isEdit)
                            <div class="col-2">
                                <a href="#" class="btn btn-danger" onclick="triggerDeleteForm(event, 'payment-delete-form')"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                            @endif
                        </div>
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
                                <label class="col-form-label font-weight-bold">{{ $field['label'] }}</label>
                            </div>
                            <div class="col mt-2" data-customer-field="{{ $field['name'] }}">
                                : [Select Loan No. to display]
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if($isEdit)
    <form id="payment-delete-form" action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-none">
        @method('DELETE')
        @csrf
    </form>
    @endif

    @section('scripts')
    <script src="{{asset('lib/jquery-maskmoney/jquery.maskMoney.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('[mask-money]').maskMoney();

            $('form').on('submit', function(e) {
                $('[mask-money]').each(function() {
                    var v = $(this).maskMoney('unmasked')[0];
                    $(this).val(v);
                });

            });

            $('[name="loan_id"]').change(function() {
                $('[data-customer-field]').each(function() {
                    $(this).html('Loading...');
                });

                $.ajax({
                    url: "{{route('loans.index')}}/customer/" + $(this).val(),
                    success: function(data) {
                        $('[data-customer-field]').each(function() {
                            $(this).html(data[$(this).data('customer-field')]);
                        });
                    }
                });
            });
        });
    </script>
    @endsection
</x-app-layout>