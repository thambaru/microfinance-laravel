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
            <th>Amount</th>
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
            data: 'loan_amount'
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

        window.location.href = `{{route('loans.index')}}/${data.id}/edit`;
      });
    });
  </script>
  @endsection
</x-app-layout>