@extends('layouts.sidebar')

@section('content')


    <div class="row">
        <div class="col-md-12">
            <h4>Handheld > Handheld Information</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>Handheld Information - Edit</h3>
                <form method="POST" action="/handheld/updatehandheld" enctype="multipart/form-data">
                    <div class="row" style="font-size: 10px">
                        <div class="card card-body">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label>UUID</label>
                                    <input id="uuid" type="text" class="form-control" name="uuid"
                                        value="{{ $handheld->uuid }}"><br>
                                    <label>Type</label>
                                    <input id="type" type="text" class="form-control" name="type"
                                        value="{{ $handheld->type }}"><br>
                                    <label>Branch</label>

                                    <select class="form-control" name="branch_id" id="branch_id">
                                        <option value="" {{ $handheld->branch_id == 0 ? 'selected' : '' }}>Select...
                                        </option>
                                        @if (!empty($branches))
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ $branch->id == $handheld->branch_id ? 'selected' : '' }}>
                                                    {{ $branch->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    <!-- <input id="branch_id" type="text" class="form-control" name="branch_id" value="{{ $handheld->branch_id }}"> -->
                                    <input hidden id="id" type="text" class="form-control" name="id"
                                        value="{{ $handheld->id }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Brand</label>
                                    <input id="brand" type="text" class="form-control" name="brand"
                                        value="{{ $handheld->brand }}"><br>
                                    <label>Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Select...</option>
                                        <option value="0" {{ $handheld->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                        <option value="1" {{ $handheld->status == 1 ? 'selected' : '' }}>Active</option>
                                    </select><br>
                                    <label>Label Tag</label>
                                    <input id="label" type="text" class="form-control" name="label"
                                        value="{{ $handheld->label }}"><br>
                                    <!-- <input id="status" type="text" class="form-control" name="status" value="{{ $handheld->status }}"> -->
                                </div>
                                <br>
                                <br>
                                <button class="btn btn-smrs col-md-3"
                                    style="margin: auto; margin-right: 10px; margin-top: 20px;">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
