

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
                  <div class="row" style="font-size: 10px">
                    <div class="card card-body">
                          <div class="row">
                            <div class="col-md-6">
                              <label>Company Name</label>
                              <input id="text" type="text" class="form-control" name="text" value="">
                            </div>
                          <div class="col-md-6">
                            <label>Company Address</label>
                            <input id="text" type="text" class="form-control" style="height: 100px" name="text" value="">
                          </div>
                          <br>
                          <div class="row col-md-6">
                              <div class="col-md-3">
                                <label>Company Registration No.</label>
                                <input id="text" type="text" class="form-control" name="text" value="">
                              </div>
                              <div class="col-md-3">
                                <label>Company GST No.</label>
                                <input id="text" type="text" class="form-control" name="text" value="">
                              </div>
                              <div class="col-md-3">
                                <label>Company Phone No.</label>
                                <input id="text" type="text" class="form-control" name="text" value="">
                              </div>
                              <div class="col-md-3">
                                <label>Company Fax No.</label>
                                <input id="text" type="text" class="form-control" name="text" value="">
                            </div>
                          </div>
                            <button class="btn btn-smrs col-md-3" style="margin: auto">Confirm</button> 
                          </div>
                    </div>
                  </div>
        </div>
    </div>
</div>
<script type="text/javascript">


</script>
</body>
@endsection
