

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
        <h4>Setting > Company Information</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Company Information - Edit</h3>
                    <form method="POST" action="/setting/updateissue" enctype="multipart/form-data">
                      <div class="row" style="font-size: 10px">
                        <div class="card card-body">
                          @csrf
                              <div class="row">
                              <div class="col-md-6">
                                <label>Issue Code</label>
                                <input id="issue" type="text" class="form-control" name="issue" value="{{$issue->code_number}}"><br>
                                <input id="id" type="text" class="form-control" name="id" value="{{$issue->id}}" hidden>
                                <label>BIll Code</label>
                                <input id="bill" type="text" class="form-control" name="bill" value="{{$issue->bill_code}}">
                              </div>
                              <div class="col-md-6">
                                <label>Description</label>
                                <input id="description" type="text" class="form-control" name="description" value="{{$issue->description}}"><br>
                                <label>Require Image</label>
                                <input id="image" type="text" class="form-control" name="image" value="{{$issue->require_image}}">
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
