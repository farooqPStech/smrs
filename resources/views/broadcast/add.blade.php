@extends('layouts.sidebar')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h4>Broadcast > Add Broadcast</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>Broadcast Information - add</h3>
                <div style="font-size: 10px">
                    <form method="POST" action="/broadcast/addnewbroadcast" enctype="multipart/form-data">
                        <div class="row" style="font-size: 10px">
                            <div class="card card-body">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Title</label>
                                        <input id="title" type="text" class="form-control" name="title" value=""><br>
                                        <label>Content</label>
                                        <input id="content" type="text" class="form-control" name="content" value=""><br>
                                        <input hidden id="id" type="text" class="form-control" name="id" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <label>To Which Device</label>
                                        <select class="form-control" name="device" id="device">
                                            <option value="0">All</option>
                                            <?php if( !empty($device) ){ ?>
                                            <?php foreach( $device as $dev ){?>
                                            <option value="{{ $dev->id }}">{{ $dev->label }}</option>
                                            <?php }?>
                                            <?php }?>
                                        </select>
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
