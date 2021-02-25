<?php

use App\Models\User;
?>

<x-app-layout>

  <x-slot name="title">
    List of Commissions
    <a href="{{route('commissions.create')}}" class="btn btn-success btn-icon-split">
      <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
      </span>
      <span class="text">Pay</span>
    </a>
  </x-slot>

  @if (session('status'))
  <div class="alert alert-success">
    {{session('status')}}
  </div>
  @endif

  <div class="card">
    <div class="card-body">

      <form id="commission-filter-form" action="">
        @csrf
        <div class="row mb-3">
          <div class="col-3">
            <select class="form-control" name="rep_id">
              <option value="">Filter by rep</option>
              @foreach(User::whereRoleIs('rep')->get() as $rep)
              <option value="{{$rep->id}}" @if(Request::get('rep_id')==$rep->id) selected @endif>{{$rep->name}}</option>
              @endforeach
            </select>
          </div>

          <div class="col">
            <div class="form-check form-check-inline">
              <label class="mx-2" for="from">From</label>
              <input class="form-control" id="from" name="from" value="{{ Request::get('from') }}" autocomplete="off">
              <label class="mx-2" for="to">to</label>
              <input class="form-control" id="to" name="to" value="{{ Request::get('to') }}" autocomplete="off">
            </div>
          </div>
        </div>
      </form>

      <table id="commission-list" class="display">
        <thead>
          <tr>
            <th>#</th>
            <th>Rep Name</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  @section('styles')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  @endsection

  @section('scripts')
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{asset('lib/jquery-maskmoney/jquery.maskMoney.min.js')}}"></script>

  <script>
    $(document).ready(function() {
      var table = $('#commission-list').DataTable({
        ajax: {
          url: "{!! url()->current().'?'.http_build_query(array_merge(request()->all(),['ajax'=>true])) !!}",
          dataSrc: 'commissions'
        },
        columns: [{
            data: 'id'
          },
          {
            data: 'rep.name'
          },
          {
            data: 'amount',
            className: 'dt-body-right',
            render: $.fn.dataTable.render.number( ',', '.', 2, 'Rs. ' )
          },
        ]
      });


      $('#commission-list tbody').on('click', 'tr', function() {
        var data = table.row(this).data();

        window.location.href = `{{route('commissions.index')}}/${data.id}`;
      });

      var dateFormat = "yy-mm-dd",
        datePickerOptions = {
          numberOfMonths: 2,
          dateFormat
        },
        from = $("#from")
        .datepicker(datePickerOptions)
        .on("change", function() {
          to.datepicker("option", "minDate", getDate(this));
        }),
        to = $("#to")
        .datepicker(datePickerOptions)
        .on("change", function() {
          from.datepicker("option", "maxDate", getDate(this));
        });

      function getDate(element) {
        var date;
        try {
          date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
          date = null;
        }

        return date;
      }

      $('[name="rep_id"]').change(function() {
        $('#commission-filter-form').submit();
      });

      $('#from, #to').change(function() {
        if ($('#from').val() == "" || $('#to').val() == "") return;

        $('#commission-filter-form').submit();
      })
    });
  </script>
  @endsection
</x-app-layout>