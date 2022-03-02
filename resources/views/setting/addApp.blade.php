

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
                <h3>App Version - Add</h3>
                  <div style="font-size: 10px">
                     
                    
                    <form method="POST" action="/setting/addnewapp" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                            <label for="file-upload" class="custom-file-upload btn btn-smrs">
                                <i class="fa fa-cloud-upload"></i> Upload APK Here
                              </label>
                              <input id="file-upload" name='file-upload' type="file" style="display:none;">
                            <div class="row">
                              <div class="col-md-6">
                                <label>Version Number *Please Make Sure Version number and App Version number is similar. </label>
                                <input id="version" type="text" class="form-control" name="version" value=""><br>
                                <label>Name </label>
                                <input id="name" type="text" class="form-control" name="name" value=""><br>
                              </div>
                              <div class="col-md-6">
                                <label>Comment</label>
                                <input id="comment" type="text" class="form-control" name="comment" value=""><br>
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
$('#file-upload').change(function() {
  var i = $(this).prev('label').clone();
  var file = $('#file-upload')[0].files[0].name;
  $(this).prev('label').text(file);
});

</script>
</body>
@endsection
