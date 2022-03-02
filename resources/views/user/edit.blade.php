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
                <h3>User Edit</h3>
                <div class="row">
                    <div class="card card-body">
                        <form method="POST" action="/user/update" enctype="multipart/form-data">
                            <div class="row">
                                @csrf
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label>Name</label>
                                        <input id="name" type="text" class="form-control" name="name"
                                            value="{{ $user->name }}">
                                        <input id="id" type="text" class="form-control" name="id"
                                            value="{{ $user->id }}" hidden>
                                    </div>
                                    <div class="col-md-12">
                                        <label>User Name <b style="color: red;">*</b></label>
                                        <input id="username" type="text" class="form-control" name="username"
                                            value="{{ $user->username }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label>Full Name</label>
                                        <input id="fullname" type="text" class="form-control" name="fullname"
                                            value="{{ $user->full_name }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label>Email <b style="color: red;">*</b></label>
                                        <input id="email" type="text" class="form-control" name="email"
                                            value="{{ $user->email }}">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label>Mobile Phone</label>
                                        <input id="mobilephone" type="text" class="form-control" name="mobilephone"
                                            value="{{ $user->mobile_phone }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label>User Type <b style="color: red;">*</b></label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="">Select...</option>
                                            <option value="1" {{ $user->type == 1 ? 'selected' : '' }}>Admin</option>
                                            <option value="2" {{ $user->type == 2 ? 'selected' : '' }}>Superuser /Final
                                                Approval</option>
                                            <option value="3" {{ $user->type == 3 ? 'selected' : '' }}>Supervisor
                                            </option>
                                            <option value="4" {{ $user->type == 4 ? 'selected' : '' }}>Meter Reader
                                            </option>
                                            <option value="5" {{ $user->type == 5 ? 'selected' : '' }}>Temporary
                                                Superuser / Temporary Final Approval</option>
                                            <option value="6" {{ $user->type == 6 ? 'selected' : '' }}>Temporary
                                                Supervisor</option>
                                        </select>
                                        <!-- <input id="type" type="text" class="form-control" name="type" value="{{ $user->type }}"> -->
                                    </div>
                                    <div class="col-md-12">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">Select...</option>
                                            <option value="0" {{ $user->active_status == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                            <option value="1" {{ $user->active_status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                        </select>
                                        <!-- <input id="status" type="text" class="form-control" name="status" value="{{ $user->active_status }}"> -->
                                    </div>
                                    <div class="col-md-12">
                                        <label>Branch <b style="color: red;">*</b></label>
                                        <select class="form-control" name="branch" id="branch">
                                            <option value="">Select...</option>
                                            @if (!empty($branches))
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        {{ $user->branch_id == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}</option>
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
