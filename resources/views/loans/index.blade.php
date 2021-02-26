<x-app-layout>

  <x-slot name="title">
    List of Loans
    <a href="{{route('loans.create')}}" class="btn btn-success btn-icon-split">
      <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
      </span>
      <span class="text">Create</span>
    </a>
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
            <th>Loan Amount</th>
            <th>Installments</th>
            <th>Rental</th>
            <th>Outstanding Amount</th>
            <th>Starting Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  @section('scripts')
  <script src="{{asset('lib/moment/moment.min.js')}}"></script>

  <script>
    $(document).ready(function() {
      var table = $('#loan-list').DataTable({
        ajax: {
          url: '{{route("loans.index",["ajax"=>true])}}',
          dataSrc: 'loans'
        },
        columns: [{
            data: 'id'
          },
          {
            data: 'customer.full_name'
          },
          {
            data: 'loan_amount',
            className: 'dt-body-right',
            render: $.fn.dataTable.render.number(',', '.', 2, 'Rs. ')
          },
          {
            data: 'installments',
            className: 'dt-body-right',
          },
          {
            data: 'rental',
            className: 'dt-body-right',
            render: $.fn.dataTable.render.number(',', '.', 2, 'Rs. ')
          },
          {
            data: 'outstanding_amount',
            className: 'dt-body-right',
            render: $.fn.dataTable.render.number(',', '.', 2, 'Rs. ')
          },
          {
            data: 'start_date',
            render: function(data) {
              return moment(data).format('YYYY-MM-DD');
            }
          },
          {
            data: 'status_text'
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