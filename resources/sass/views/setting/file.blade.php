

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
        <h4>Setting > File Location</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>File Location For UBIS SYSTEM - Edit</h3>
                  <div>
                    <form method="POST" action="/setting/updatefile" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                            <label>IP Download</label>
                            <input type="text" class="" style="border:none" name="upload_ip" id="upload_ip" value="{{$file->upload_ip}}"><br>
                            <label>Download Folder Name</label>
                            <input type="text" class="" style="border:none" name="upload_filename" id="upload_filename" value="{{$file->upload_filename}}"><br>
                            <label>IP Upload</label>
                            <input type="text" class="" style="border:none" name="download_ip" id="download_ip" value="{{$file->download_ip}}"><br>
                            <label>Upload Folder Name</label>
                            <input type="text" class="" style="border:none" name="download_filename" id="download_filename" value="{{$file->download_filename}}"><br>
                            <input type="text" class="" style="border:none" name="id" id="id" value="{{$file->id}}" hidden>
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
