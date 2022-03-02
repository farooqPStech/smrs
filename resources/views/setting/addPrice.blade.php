

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
        <h4>Setting > Price</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Price - Add</h3>
                  <div style="font-size: 10px">
                    <form method="POST" action="/setting/addnewprice" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                              <div class="row">
                                <div class="col-md-6">
                                  <label>Code</label>
                                  <input id="code" type="text" class="form-control" name="code" value=""><br>
                                  <input hidden id="id" type="text" class="form-control" name="id" value="">
                                  <label>Min Charge</label>
                                  <input id="min_charge" type="text" class="form-control" name="min_charge" value=""><br>
                                  <label>Min Quantity</label>
                                  <input id="min_quantity" type="text" class="form-control" name="min_quantity" value=""><br>
                                  <label>Min Consumption</label>
                                  <input id="min_consumption" type="text" class="form-control" name="min_consumption" value=""><br>
                                  <label>Rate</label>
                                  <input id="rate" type="text" class="form-control" name="rate" value=""><br>
                                </div>
                              <div class="col-md-6">
                                  <label>GST RATE</label>
                                  <input id="gstrate" type="text" class="form-control" name="gstrate" value=""><br>
                                  <label>Multiplier</label>
                                  <input id="multiplier" type="text" class="form-control" name="multiplier" value=""><br>
                                  <label>Effective Date</label>
                                  <input id="effective_date" type="text" class="form-control" name="effective_date" value=""><br>
                                  <label>Max Consumption</label>
                                  <input id="max_consumption" type="text" class="form-control" name="max_consumption" value=""><br>
                              </div>
                              <br>
                              <br>
                                <button class="btn btn-smrs col-md-3" style="margin: auto">Add</button> 
                              </div>
                        </div>
                    </form>
                  </div>
        </div>
    </div>
</div>
<script type="text/javascript">


</script>
</body>
@endsection
