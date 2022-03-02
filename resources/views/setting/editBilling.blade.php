

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
        <h4>Setting > Billing Days</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Billing - Edit</h3>
                    <form method="POST" action="/setting/updatebilling" enctype="multipart/form-data">
                      <div class="row" style="font-size: 10px">
                        <div class="card card-body">
                          @csrf
                              <div class="row">
                              <div class="col-md-6">
                                <label>Month Number</label>
                                <input id="month_number" type="text" class="form-control" name="month_number" value="{{$billing->month_number}}"><br>
                                <label>Number Of Day</label>
                                <input id="number_of_day" type="text" class="form-control" name="number_of_day" value="{{$billing->number_of_day}}"><br>
                                <input id="id" type="text" class="form-control" name="id" value="{{$billing->id}}" hidden>
                              </div>
                              <div class="col-md-6">
                                <label>Month Name</label>
                                <input id="month_name" type="text" class="form-control" name="month_name" value="{{$billing->month_name}}"><br>
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
