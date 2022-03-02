

@extends('layouts.sidebar')

@section('content')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

</head>
<body>
<div class="row">
    <div class="col-md-12" >
        <h4>Setting > Edit Obstacle Code</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Obstacle Code - Edit</h3>
                    <form method="POST" action="/setting/updateobstacle" enctype="multipart/form-data">
                      <div class="row" style="font-size: 10px">
                        <div class="card card-body">
                          @csrf
                              <div class="row">
                              <div class="col-md-6">
                                <label>Code Number</label>
                                <input id="code" type="text" class="form-control" name="code" value="{{$obstacle->code_number}}"><br>
                                <input id="id" type="text" class="form-control" name="id" value="{{$obstacle->id}}" hidden>
                              </div>
                              <div class="col-md-6">
                                <label>Description</label>
                                <input id="description" type="text" class="form-control" name="description" value="{{$obstacle->description}}"><br>
                              </div>
                            </div><br>
                            <div style="float: right">
                              <button style="float: right;" class="btn btn-smrs col-md-3">Update</button>
                            </div>
                        </div>
                      </div>
                    </form>
        </div>
    </div>
</div>
<script type="text/javascript">


</script>
</body>
@endsection
