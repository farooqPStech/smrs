

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
        <h4>Setting > GCPT</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>GCPT - Add</h3>
                  <div style="font-size: 10px">
                    <form method="POST" action="/setting/addnewgcpt" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                            <div class="row">
                              <div class="col-md-6">
                                <label>Code</label>
                                <input id="code" type="text" class="form-control" name="code" value=""><br>
                                <label>Rate</label>
                                <input id="rate" type="text" class="form-control" name="rate" value=""><br>
                              </div>
                              <div class="col-md-6">
                                <label>Effective Date</label>
                                <input id="effective_date" type="text" class="form-control" name="effective_date" value=""><br>
                              </div>
                            </div><br>
                            <div style="float: right">
                              <button style="float: right;" class="btn btn-smrs col-md-3">Add</button>
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
