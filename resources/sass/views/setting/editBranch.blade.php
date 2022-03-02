

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
                    <form method="POST" action="/setting/updatebranch" enctype="multipart/form-data">
                      <div class="row" style="font-size: 10px">
                        <div class="card card-body">
                          @csrf
                              <div class="row">
                                <div class="col-md-6">
                                  <label>Branch Code</label>
                                  <input id="code" type="text" class="form-control" name="code" value="{{$branch->code}}">
                                  <input hidden id="id" type="text" class="form-control" name="id" value="{{$branch->id}}">
                                </div>
                              <div class="col-md-6">
                                <label>Name</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{$branch->name}}">
                              </div>
                              <br>
                              <br>
                                <button class="btn btn-smrs col-md-3" style="margin: auto">Confirm</button> 
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
