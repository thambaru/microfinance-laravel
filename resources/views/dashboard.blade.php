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
  <script src="https://code.highcharts.com/highcharts.js"></script>

  <script>
    $(document).ready(function() {
      var overallPaymentsVSLoans = chartData(<?php echo json_encode($overallPaymentsVSLoans) ?>);

      let seriesData = [
        overallPaymentsVSLoans.months,
        [{
            name: 'Payments',
            data: overallPaymentsVSLoans.payments
          },
          {
            name: 'Loans',
            data: overallPaymentsVSLoans.loans
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