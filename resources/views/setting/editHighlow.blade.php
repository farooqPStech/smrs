

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
        <h4>Setting > High Low</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>High Low Control - Edit</h3>
                    <form method="POST" action="/setting/updatehighlow" enctype="multipart/form-data">
                      <div class="row" style="font-size: 10px">
                        <div class="card card-body">
                          @csrf
                              <div class="row">
                              <div class="col-md-6">
                                <label>Tariff Code Number</label>
                                <input id="code" type="text" class="form-control" name="code" value="{{$highlow->tariff_code}}"><br>
                                <label>High COnsumption</label>
                                <input id="high" type="text" class="form-control" name="high" value="{{$highlow->high_consumption}}"><br>
                                <input id="id" type="text" class="form-control" name="id" value="{{$highlow->id}}" hidden>
                              </div>
                              <div class="col-md-6">
                                <label>Low Consumption</label>
                                <input id="low" type="text" class="form-control" name="low" value="{{$highlow->low_consumption}}"><br>
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
