@extends('layouts.main')

@section('main-container')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                timer: 3000
            });
        </script>
    @endif
    <div class="mt-5 mx-2">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">User Listing</h2>
                    <div class="d-flex">
                        <div class="mx-2">
                            <select class="form-control" id="selectCountry">
                                <option selected value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->_id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mx-2">
                            <select class="form-control" id="selectStatus">
                                <option selected value="">Select Status</option>
                                <option value=1>Active</option>
                                <option value=0>Inactive</option>
                            </select>
                        </div>
                        <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
                        <button class="btn btn-danger mx-2 delete-selected">Delete Selected</button>
                    </div>
                </div>
            </div>
        </div>

        <form id="deleteSelectedForm" action="" method="POST">
            @csrf
            <table class="table table-bordered" id="userTable">
                <thead>
                    <tr>
                        <div class="form-check">
                            <th><input type="checkbox" id="selectAll" value="checkedValue"></th>
                        </div>
                        <!-- <th>#</th> -->
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Status</th>
                        <th>Current Address</th>
                        <th>permenent Address</th>
                        <th>Country</th>
                        <th>Actions</th>
                    </tr>
                </thead>
               {{-- <tbody>
                    @foreach ($users as $index => $user)
                        <tr class="user-row" data-user-id="{{ $user->_id }}">
                            <td><input type="checkbox" name="selected_ids[]" value="{{ $user->_id }}"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->gender }}</td>
                            <td>{{ $user->date_of_birth }}</td>
                            <td>{{ $user->status == 0 ? 'Inactive' : 'Active' }}</td>
                            <td>{{ $user->address_1 }}</td>
                            <td>{{ $user->address_2 }}</td>
                            <td>{{ $user->country->name }}</td>
                            <td>
                                <a href="{{ route('users.edit', ['id' => $user->_id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i></a>
                                <a href="{{ route('users.destroy', ['id' => $user->_id]) }}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>--}}
            </table>
        </form>
    </div>
    <!---- ends ---->

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            var table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                        url: "{{ route('users.list') }}",
                        type: 'GET',
                        data: function(d) {
                            d.country_id = $('#selectCountry').val();
                            d.status = $('#selectStatus').val();
                        }
                    },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'last_name', name: 'last_name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'gender', name: 'gender' },
                    { data: 'date_of_birth', name: 'date_of_birth' },
                    { data: 'status', name: 'status' },
                    { data: 'address_1', name: 'address_1' },
                    { data: 'address_2', name: 'address_2' },
                    { data: 'country_name', name: 'country_name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[1, 'asc']],
                searching: true,
                paging: true,
                lengthChange: true,
                pageLength: 10,
            });

            $('#selectAll').click(function() {
                var isChecked = $(this).prop('checked');
                $('input[name="selected_ids[]"]').prop('checked', isChecked);
            });

            $('.delete-selected').click(function() {
                var selectedUserIds = $('input[name="selected_ids[]"]:checked').map(
                    function() {
                        return $(this).val();
                    }).get();

                if (selectedUserIds.length === 0) {
                    Swal.fire('Error!', 'No products selected for deletion.', 'error');
                    return;
                }
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete selected!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/users/deleteSelectedUsers',
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "selectedUserIds": selectedUserIds
                            },
                            success: function(response) {
                                $.each(selectedUserIds, function(index,
                                    userId) {
                                    $('.user-row[data-user-id="' +
                                            userId + '"]')
                                        .remove();
                                });
                                Swal.fire(
                                    'Deleted!',
                                    response.success,
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', xhr.responseText);
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete selected products.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('body').on('click', '.btn-danger.btn-sm', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.success,
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', xhr.responseText);
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete user.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            $('#selectCountry').change(function() {
                table.draw();
            });

            $('#selectStatus').change(function() {
                table.draw();
            });

        });
    </script>
@endsection
