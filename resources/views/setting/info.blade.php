

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
                  <div style="font-size: 10px">
                    <form method="POST" action="/setting/infoupdate" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                            <label>Company Name</label>
                            <input type="text" class="" style="border:none" name="name" id="name" value="{{$info->name}}"><br>
                            <input type="text" class="" style="border:none" name="id" id="id" value="{{$info->id}}" hidden>
                            <label>Company Address</label>
                            <input type="text" class="" style="border:none" name="address" id="address" value="{{$info->address}}"><br>
                            <label>Company Registration No.</label>
                            <input type="text" class="" style="border:none" name="reg" id="reg" value="{{$info->registration_number}}"><br>
                            <label>Company GST No.</label>
                            <input type="text" class="" style="border:none" name="gst" id="gst" value="{{$info->gst_number}}"><br>
                            <label>Company Phone No.</label>
                            <input type="text" class="" style="border:none" name="phone" id="phone" value="{{$info->phone_number}}"><br>
                            <label>Company Fax No.</label>
                            <input type="text" class="" style="border:none" name="fax" id="fax" value="{{$info->fax_number}}"><br>
                            <div style="float: right;">
                              <button style="float: right;" class="btn btn-smrs col-md-3">Update</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <form method="POST" action="/setting/infoupdate" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                            <label>Company Name</label>
                            <input type="text" class="" style="border:none" name="name" id="name" value="{{$info2->name}}"><br>
                            <input type="text" class="" style="border:none" name="id" id="id" value="{{$info2->id}}" hidden>
                            <label>Company Address</label>
                            <input type="text" class="" style="border:none" name="address" id="address" value="{{$info2->address}}"><br>
                            <label>Company Registration No.</label>
                            <input type="text" class="" style="border:none" name="reg" id="reg" value="{{$info2->registration_number}}"><br>
                            <label>Company GST No.</label>
                            <input type="text" class="" style="border:none" name="gst" id="gst" value="{{$info2->gst_number}}"><br>
                            <label>Company Phone No.</label>
                            <input type="text" class="" style="border:none" name="phone" id="phone" value="{{$info2->phone_number}}"><br>
                            <label>Company Fax No.</label>
                            <input type="text" class="" style="border:none" name="fax" id="fax" value="{{$info2->fax_number}}"><br>
                            <div style="float: right;">
                              <button style="float: right;" class="btn btn-smrs col-md-3">Update</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <form method="POST" action="/setting/infoupdate" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                            <label>Company Name</label>
                            <input type="text" class="" style="border:none" name="name" id="name" value="{{$info3->name}}"><br>
                            <input type="text" class="" style="border:none" name="id" id="id" value="{{$info3->id}}" hidden>
                            <label>Company Address</label>
                            <input type="text" class="" style="border:none" name="address" id="address" value="{{$info3->address}}"><br>
                            <label>Company Registration No.</label>
                            <input type="text" class="" style="border:none" name="reg" id="reg" value="{{$info3->registration_number}}"><br>
                            <label>Company GST No.</label>
                            <input type="text" class="" style="border:none" name="gst" id="gst" value="{{$info3->gst_number}}"><br>
                            <label>Company Phone No.</label>
                            <input type="text" class="" style="border:none" name="phone" id="phone" value="{{$info3->phone_number}}"><br>
                            <label>Company Fax No.</label>
                            <input type="text" class="" style="border:none" name="fax" id="fax" value="{{$info3->fax_number}}"><br>
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
