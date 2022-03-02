@extends('layouts.sidebar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4>User</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>User List</h3>
                <div class="row">
                    <div class="card card-body">
                        <div class="float-container">
                            <div style="float:right; width:50%">
                                <a href="/user/adduser" class="btn btn-smrs" style="width:50%;float: right">Add New</a>
                            </div>
                            <div id="buttonExport" style="float: left; width:50%"></div>
                        </div>
                        <br>
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead class="table-smrs">
                                <tr>
                                    <th>Name</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Mobile Phone</th>
                                    <th>Status</th>
                                    <!-- <th>Image</th> -->
                                    {{-- <th>Remember Token</th> --}}
                                    {{-- <th>Created At</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @switch($user->type)
                                                @case(1)
                                                    Admin
                                                @break
                                                @case(2)
                                                    Supervuser / Final Approval
                                                @break
                                                @case(3)
                                                    Supervisor
                                                @break
                                                @case(4)
                                                    Meter Reader
                                                @break
                                                @case(5)
                                                    Temporary Supervuser / Final Approval
                                                @break
                                                @case(5)
                                                    Temporary Supervisor
                                                @break
                                                @default
                                            @endswitch
                                        </td>
                                        <td>{{ $user->mobile_phone }}</td>
                                        <td>
                                            @switch($user->active_status)
                                                @case(0)
                                                    <span class="badge badge-danger">Inactive</span>
                                                @break
                                                @case(1)
                                                    <span class="badge badge-success">Active</span>
                                                @break
                                                @default
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="/user/edit/{{ $user->id }}"
                                                class="btn btn-xs btn-block btn-primary">Edit</a>
                                            <a href="/user/resetpassword/{{ $user->id }}"
                                                class="btn  btn-block btn-xs btn-warning">Reset Password</a>
                                            <button class="btn btn-xs btn-danger  btn-block"
                                                onclick="deleteConfirmation({{ $user->id }})">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var a = $('#data-table').DataTable({
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                "dom": "Blfrtip",
                buttons: {
                    buttons: [
                        {
                            extend : 'pdf',
                            orientation: 'landscape',
                            className: 'btn-smrs',
                            title: function(){
                                return 'List of User'
                            }
                        },
                        {
                            extend : 'excel',
                            className: 'btn-smrs',
                            filename: function () {
                                return 'List of User';
                            }
                        }
                    ],
                    dom: {
                        button: {
                            className: 'btn'
                        },
                    },
                },
            }).buttons()
                .container()
                .appendTo("#buttonExport");

        });

        function deleteConfirmation(id) {
            swal({
                    text: "Are you sure you want to delete?",
                    icon: "warning",
                    buttons: ['NO', 'YES'],
                    dangerMode: true
                })
                .then(function(value) {
                    if (value == true) {
                        window.location.href = "/user/removeuser/" + id;
                    }
                });
        }
    </script>
@endsection
