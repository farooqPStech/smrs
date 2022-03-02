@extends('layouts.sidebar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4>Handheld > Add Handheld</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>Handheld Information - add</h3>
                <div style="font-size: 10px">
                    <form method="POST" action="/handheld/addnewhandheld" enctype="multipart/form-data">
                        <div class="row" style="font-size: 10px">
                            <div class="card card-body">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>UUID</label>
                                        <input id="uuid" type="text" class="form-control" name="uuid" value=""><br>
                                        <label>Type</label>
                                        <input id="type" type="text" class="form-control" name="type" value=""><br>
                                        <label>Branch</label>

                                        <select class="form-control" name="branch_id" id="branch_id">
                                            <option value="">Select...</option>
                                            <?php if( !empty($branches) ){ ?>
                                            <?php foreach( $branches as $branch ){?>
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Brand</label>
                                        <input id="brand" type="text" class="form-control" name="brand" value=""><br>
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">Select...</option>
                                            <option value="0">Inactive</option>
                                            <option value="1">Active</option>
                                        </select><br>
                                        <label>Label Tag</label>
                                        <input id="label" type="text" class="form-control" name="label" value=""><br>
                                    </div>
                                    <br>
                                    <br>
                                    <button class="btn btn-smrs col-md-3"
                                        style="margin: auto; margin-right: 10px; margin-top: 20px;">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
