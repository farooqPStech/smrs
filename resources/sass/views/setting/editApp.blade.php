

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
        <h4>Setting > App Version</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>App - Edit</h3>
                    <form method="POST" action="/setting/updateapp" enctype="multipart/form-data">
                       <div class="card card-body">
                          @csrf
                                <input id="id" type="text" class="form-control" name="id" value="{{$app->id}}" hidden>
                            <div class="row">
                              <div class="col-md-6">
                                <label>Version Number *Please Make Sure Version number and App Version number is similar. </label>
                                <input id="version" type="text" class="form-control" name="version" value="{{$app->version_number}}"><br>
                                <label>Name </label>
                                <input id="name" type="text" class="form-control" name="name" value="{{$app->name}}"><br>
                              </div>
                              <div class="col-md-6">
                                <label>Comment</label>
                                <input id="comment" type="text" class="form-control" name="comment" value="{{$app->comment}}"><br>
                              </div>
                            </div><br>
                            <div style="float: right">
                              <button style="float: right;" class="btn btn-smrs col-md-3">Update</button>
                            </div>
                    </form>
        </div>
    </div>
</div>
<script type="text/javascript">


</script>
</body>
@endsection
