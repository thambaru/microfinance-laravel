<x-app-layout>

  <x-slot name="title">
    List of Reps
    <a href="{{route('users.create')}}" class="btn btn-success btn-icon-split">
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
      <table id="user-list" class="display">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Commission (%)</th>
            <th>Commission Balance</th>
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
      var table = $('#user-list').DataTable({
        ajax: {
          url: '{{route("users.index",["ajax"=>true])}}',
          dataSrc: 'users'
        },
        columns: [{
            data: 'id'
          },
          {
            data: 'name'
          },
          {
            data: 'email'
          },
          {
            data: 'commiss_perc'
          },
          {
            data: 'commiss_bal'
          }
        ]
      });


      $('#user-list tbody').on('click', 'tr', function() {
        var data = table.row(this).data();

        window.location.href = `{{route('users.index')}}/${data.id}`;
      });
    });
  </script>
  @endsection
</x-app-layout>