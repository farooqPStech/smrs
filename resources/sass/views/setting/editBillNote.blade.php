

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
        <h4>Setting > Bill Note</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Bill Note - Edit</h3>
                    <form method="POST" action="/setting/updatebillnote" enctype="multipart/form-data">
                       <div class="card card-body">
                          @csrf
                            <input id="id" type="text" class="form-control" name="id" value="{{$app->id}}" hidden>
                            <div class="row">
                              <div class="col-md-6">
                                 <label>Note </label>
                                <textarea id="note" type="text" class="form-control" name="note">{{$app->bill_notes}}</textarea><br>
                                <label>Comment </label>
                                <input id="comment" type="text" class="form-control" name="comment" value="{{$app->comment}}"><br>
                              </div>
                              <div class="col-md-6">
                                 <label>Status</label>
                                  <select class="form-control" name="status" id="status">
                                    <option value="">Select...</option>
                                    <option value="0" <?php if($app->status==0){ print ' selected'; }?>>Inactive</option>
                                  <option value="1" <?php if($app->status==1){ print ' selected'; }?>>Active</option>
                                  </select>
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
