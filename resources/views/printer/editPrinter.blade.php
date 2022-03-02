@extends('layouts.sidebar')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h4>Printer > Printer Information</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>Printer Information - Edit</h3>
                <form method="POST" action="/printer/updateprinter" enctype="multipart/form-data">
                    <div class="row" style="font-size: 10px">
                        <div class="card card-body">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Mac Address</label>
                                    <input id="mac" type="text" class="form-control" name="mac"
                                        value="{{ $printer->mac_address }}"><br>
                                    <label>Type</label>
                                    <input id="type" type="text" class="form-control" name="type"
                                        value="{{ $printer->type }}"><br>
                                    <label>Branch</label>

                                    <select class="form-control" name="branch_id" id="branch_id">
                                        <option value="" {{ $printer->branch_id == 0 ? 'selected' : '' }}>Select...
                                        </option>
                                        @if (!empty($branches))
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ $branch->id == $printer->branch_id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    <!-- <input id="branch_id" type="text" class="form-control" name="branch_id" value="{{ $printer->branch_id }}"> -->
                                    <input hidden id="id" type="text" class="form-control" name="id"
                                        value="{{ $printer->id }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Brand</label>
                                    <input id="brand" type="text" class="form-control" name="brand"
                                        value="{{ $printer->brand }}"><br>
                                    <label>Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Select...</option>
                                        <option value="0" {{ $printer->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        <option value="1" {{ $printer->status == 1 ? 'selected' : '' }}>Active</option>
                                    </select><br>
                                    <label>Label Tag</label>
                                    <input id="label" type="text" class="form-control" name="label"
                                        value="{{ $printer->label }}"><br>
                                    <!-- <input id="status" type="text" class="form-control" name="status" value="{{ $printer->status }}"> -->
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
