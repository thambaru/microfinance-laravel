<?php

use App\Models\CommissTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Request;
?>


<x-app-layout>
    <x-slot name="title">
        Pay Commission
    </x-slot>

    @if (session('status'))
    <div class="alert alert-success">
        Commission has been paid. <u><a href="{{route('commissions.show', session('status')->id)}}">View</a></u>
    </div>
    @endif

    <form action="{{route('commissions.store')}}" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">

                        @csrf

                        @foreach(CommissTransaction::entityFields() as $field)
                        <div class="row mt-2">
                            @if(@$field['type'] != "hidden")
                            <div class="col-12 col-md-3">
                                <label class="col-form-label">{{@$field['label']}}</label>
                            </div>
                            @endif
                            <div class="col">
                                @switch(@$field['type'])
                                @case('select')
                                <select class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" {{@$field['attributes']}} value="{{ old($field['name'] , @$commission[$field['name']]) }}">
                                    <option value="">Select {{@$field['label']}}</option>
                                    @foreach($field['selectOptions'] as $option)
                                    <option value="{{$option->id}}" @if($option->id == old($field['name']) ){{ 'selected' }}@endif>{{$option[$field['selectOptionNameField']]}}</option>
                                    @endforeach
                                </select>
                                @break
                                @default
                                <input class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" type="{{@$field['type']}}" {{@$field['attributes']}} @if(!in_array($field['name'], ['proof_doc'])) value="{{ old($field['name'] , @$commission[$field['name']]) }}" @endif>
                                @endswitch
                            </div>
                        </div>
                        @endforeach
                        <div class="row mt-3">

                            <div class="col text-center">
                                <button type="submit" class="btn btn-block btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">User details</h5>
                        <hr class="mb-2" />
                        @foreach(User::entityFields() as $field)
                        @if(@$field['formOnly']) @continue @endif
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <label class="col-form-label font-weight-bold">{{ $field['label'] }}</label>
                            </div>
                            <div class="col mt-2" data-user-field="{{ $field['name'] }}">
                                : [Select Rep to display]
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>

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

            $('[name="rep_id"]').change(function() {
                $('[data-user-field]').each(function() {
                    $(this).html('Loading...');
                });

                $.ajax({
                    url: "{{route('commissions.index')}}/rep/" + $(this).val(),
                    success: function(data) {
                        $('[data-user-field]').each(function() {
                            $(this).html(data[$(this).data('user-field')]);
                        });
                        $('[name="rep_id"').val(data.id);
                    }
                });
            });

            <?php if (Request::has('rep-id')) { ?>
                $('[name="rep_id"]').val("{{Request::get('rep-id')}}").trigger('change');
            <?php } ?>
        });
    </script>
    @endsection
</x-app-layout>