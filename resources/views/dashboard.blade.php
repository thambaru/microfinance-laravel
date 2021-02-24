<?php

use Illuminate\Support\Carbon;
?>
<x-app-layout>

  <x-slot name="title">
    Dashboard
  </x-slot>

  @if (session('status'))
  <div class="alert alert-success">
    {{session('status')}}
  </div>
  @endif

  <div class="card">
    <div class="card-header">
      <h6 class="font-weight-bold text-primary">Overall Performance</h6>
    </div>
    <div class="card-body">
      <div id="overall-payments-vs-loans"></div>
    </div>
  </div>

  @section('scripts')
  <script src="https://cdn.datatables.net/searchpanes/1.2.1/js/dataTables.searchPanes.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>

  <script>
    $(document).ready(function() {
      $.ajax({
        url: '{{route("reports.getOverallPaymentsVSLoans")}}',
        success: function(data) {
          let seriesData = [
            chartData(data).months,
            [{
                name: 'Payments',
                data: chartData(data).payments
              },
              {
                name: 'Loans',
                data: chartData(data).loans
              }
            ]
          ];

          Highcharts.chart('overall-payments-vs-loans', {
            chart: {
              type: 'column'
            },
            title: {
              text: '',
            },
            xAxis: {
              categories: seriesData[0]
            },
            yAxis: {
              min: 0,
              title: {
                text: 'Amount (Rs.)'
              }
            },
            series: seriesData[1]
          });

        }
      });

      function chartData(data) {
        var monthArray = [];
        var paymentData = [];
        var loanData = [];

        $.map(data.loans, function(val) {
          loanData.push(val.sum);
        });

        $.map(data.payments, function(val) {
          monthArray.push(val.month);
          paymentData.push(val.sum);
        });

        return {
          months: monthArray,
          payments: paymentData,
          loans: loanData
        };
      }
    });
  </script>
  @endsection
</x-app-layout>