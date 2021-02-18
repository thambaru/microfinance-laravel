<x-app-layout>

  <x-slot name="title">
    List of Payments
    <a href="{{route('payments.create')}}" class="btn btn-success btn-icon-split">
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
      <table id="payment-list" class="display">
        <thead>
          <tr>
            <th>#</th>
            <th>Amount (Rs.)</th>
            <th>Paid On</th>
            <th>Loan Rental (Rs.)</th>
            <th>Rep Name</th>
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
      var table = $('#payment-list').DataTable({
        ajax: {
          url: '{{route("payments.index",["ajax"=>true])}}',
          dataSrc: 'payments'
        },
        columns: [{
            data: 'id'
          },
          {
            data: 'amount'
          },
          {
            data: 'created_at'
          },
          {
            data: 'loan.rental'
          },
          {
            data: 'rep.name'
          },
        ]
      });


      $('#payment-list tbody').on('click', 'tr', function() {
        var data = table.row(this).data();

        window.location.href = `{{route('payments.index')}}/${data.id}`;
      });
    });
  </script>
  @endsection
</x-app-layout>