<x-app-layout>
    <x-slot name="title">
        Create a Customer
    </x-slot>

    @if (session('status'))
    <div class="alert alert-success">
        Customer has been created! <u><a href="{{route('customers.index')}}">View the list</a></u>
    </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{route('customers.store')}}" method="POST">
            @csrf
            <input type="hidden" name="id" />

            <?php

            $fields = [
                [
                    'label' => 'Full Name*',
                    'name' => 'full_name',
                    'attributes' => 'required'
                ],
                [
                    'label' => 'Email',
                    'name' => 'email',
                    'type' => 'email'
                ],
                [
                    'label' => 'NIC Number*',
                    'name' => 'nic',
                    'attributes' => 'required'
                ],
                [
                    'label' => 'Address in NIC',
                    'name' => 'address_nic'
                ],
                [
                    'label' => 'Home Address',
                    'name' => 'address'
                ],
                [
                    'label' => 'Business Address',
                    'name' => 'address_bus'
                ],
                [
                    'label' => 'Profession',
                    'name' => 'profession'
                ],
                [
                    'label' => 'Phone Number',
                    'name' => 'phone_num'
                ],
            ];

            ?>
            @foreach($fields as $field)
            <div class="row mt-2">
                <div class="col-12 col-md-2">
                    <label class="col-form-label">{{$field['label']}}</label>
                </div>
                <div class="col">
                    <input class="form-control @error($field['name']) border border-danger @enderror" name="{{$field['name']}}" {{@$field['attributes']}}>
                </div>
            </div>
            @endforeach
            <div class="row mt-3">
                <div class="col text-center">
                    <button type="submit" class="btn btn-block btn-primary">Submit</button>
                </div>
            </div>
            </form>
    </div>
    </div>
</x-app-layout>