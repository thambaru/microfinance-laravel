<x-app-layout>
  <div class="card">
    <div class="card-body">
      <table id="table_id" class="display">
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
      $('#table_id').DataTable({
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
    });
  </script>
  @endsection
</x-app-layout>