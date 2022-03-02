

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
        <h4>Setting > Due Date</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Due Date - Edit</h3>
                  <div>
                    <form method="POST" action="/setting/updateduedate" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                            <label>Number Of Day</label>
                            <input type="text" class="" style="border:none" name="no" id="no" value="{{$dueDate->number_of_day}}"><br>
                            <input type="text" class="" style="border:none" name="id" id="id" value="{{$dueDate->id}}" hidden>
                            <div style="float: right;">
                              <button style="float: right;" class="btn btn-smrs col-md-3">Update</button>
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
