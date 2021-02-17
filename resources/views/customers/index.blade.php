<x-app-layout>

  <x-slot name="title">
    List of Customers
    <a href="{{route('customers.create')}}" class="btn btn-success btn-icon-split">
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
      <table id="customer-list" class="display">
        <thead>
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Profession</th>
            <th>NIC</th>
            <th>Phone Number</th>
            <th>Address</th>
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
      var table = $('#customer-list').DataTable({
        ajax: {
          url: '{{route("customers.index",["ajax"=>true])}}',
          dataSrc: 'customers'
        },
        columns: [{
            data: 'id'
          },
          {
            data: 'full_name'
          },
          {
            data: 'profession'
          },
          {
            data: 'nic'
          },
          {
            data: 'phone_num'
          },
          {
            data: 'address'
          },
        ]
      });


      $('#customer-list tbody').on('click', 'tr', function() {
        var data = table.row(this).data();

        window.location.href = `{{route('customers.index')}}/${data.id}`;
      });
    });
  </script>
  @endsection
</x-app-layout>