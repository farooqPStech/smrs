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
                <h3>Add User</h3>
                <div class="row">
                    <div class="card card-body">
                        <form method="POST" action="/user/addnewuser" enctype="multipart/form-data">
                            <div class="row">
                                @csrf
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label>Name</label>
                                        <input id="name" type="text" class="form-control" name="name" value="">
                                    </div>
                                    <div class="col-md-12">
                                        <label>User Name <b style="color: red;">*</b></label>
                                        <input id="username" type="text" class="form-control" name="username" value="">
                                    </div>
                                    <div class="col-md-12">
                                        <label>Full Name</label>
                                        <input id="fullname" type="text" class="form-control" name="fullname" value="">
                                    </div>
                                    <div class="col-md-12">
                                        <label>Email <b style="color: red;">*</b></label>
                                        <input id="email" type="text" class="form-control" name="email" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label>Mobile Phone</label>
                                        <input id="mobilephone" type="text" class="form-control" name="mobilephone"
                                            value="">
                                    </div>
                                    <div class="col-md-12">
                                        <label>User Type <b style="color: red;">*</b></label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="">Select...</option>
                                            <option value="1">Admin</option>
                                            <option value="2">Superuser /Final Approval</option>
                                            <option value="3">Supervisor</option>
                                            <option value="4">Meter Reader</option>
                                            <option value="5">Temporary Superuser / Temporary Final Approval</option>
                                            <option value="6">Temporary Supervisor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">Select...</option>
                                            <option value="0">Inactive</option>
                                            <option value="1">Active</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Branch <b style="color: red;">*</b></label>
                                        <select class="form-control" name="branch" id="branch">
                                            <option value="">Select...</option>
                                            @if (!empty($branches))
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <button class="btn btn-smrs col-md-3 mt-3"
                                    style="margin: auto; margin-right:20px">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
