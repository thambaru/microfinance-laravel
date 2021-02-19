<x-app-layout>

  <x-slot name="title">
    List of {{ $reportType }} loans
  </x-slot>

  @if (session('status'))
  <div class="alert alert-success">
    {{session('status')}}
  </div>
  @endif

  <div class="card">
    <div class="card-body">
      <table id="loan-list" class="display">
        <thead>
          <tr>
            <th>#</th>
            <th>Customer Name</th>
            <th>Last Payment</th>
            <th>Due Amount</th>
            <th>Loan Amount</th>
            <th>Total Paid</th>
            <th>Installments</th>
            <th>Rental</th>
            <th>Starting Date</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  @section('scripts')
  <script>
    $(document).ready(function() {
      var table = $('#loan-list').DataTable({
        ajax: {
          url: '{{route("reports.show",["type" => $reportType, "ajax" => true])}}',
          dataSrc: 'loans'
        },
        columns: [{
            data: 'id'
          },
          {
            data: 'customer.full_name'
          },
          {
            data: 'last_payment.created_at'
          },
          {
            data: 'total_due_todate'
          },
          {
            data: 'loan_amount'
          },
          {
            data: 'total_paid'
          },
          {
            data: 'installments'
          },
          {
            data: 'rental'
          },
          {
            data: 'start_date'
          },
        ]
      });


      $('#loan-list tbody').on('click', 'tr', function() {
        var data = table.row(this).data();

        window.location.href = `{{route('loans.index')}}/${data.id}`;
      });
    });
  </script>
  @endsection
</x-app-layout>