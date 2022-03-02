

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
        <h4>Setting > Branch</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Branch List</h3>
                  <div class="row">
                    <div class="card card-body">
                      <div style="float: left">
                      <button class="btn btn-smrs" style="width: 5%; float: left">PDF</button> 
                      </div><br>
                      <div style="float: right">
                      <a href="/setting/addbranch" class="btn btn-smrs" style="width: 20%; float: right">Add New</a> 
                      </div><br>
                      <table id="allRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr>
                            <th>No</th>
                            <th>Branch Code</th>
                            <th>Name</th>
                            <th>Last Modified</th>
                            <th>Modified By</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if( !empty($branches) ){ ?>
                            <?php foreach( $branches as $branch ){?>
                            <tr>
                              <td>{{$branch->id}}</td>
                              <td>{{$branch->code}}</td>
                              <td>{{$branch->name}}</td>
                              <td>{{$branch->updated_at}}</td>
                              <td>{{$branch->updated_by}}</td>
                              <td align="center"><a href="/setting/editbranch/{{$branch->id}}" style="width: 90%" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> Edit</i></a><br><a href="/setting/removebranch/{{$branch->id}}" class="btn btn-danger" style="margin-top: 3%; width: 90%;" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-edit text-white"> Remove</i></a>
                            </tr>
                            <?php }?>
                          <?php }?>
                        </tbody>
                      </table> 
                    </div>
                  </div>
        </div>
    </div>
</div>
<script type="text/javascript">
      

      var allRoute = $('#allRoute').DataTable({
      aaSorting : [[0, 'desc']],
      "dom": "Bfrtip",
      "bPaginate": true,
      "bLengthChange": false,
      "bFilter": true,
      "bSort": true,
      "bInfo": true,
      "bAutoWidth": true,
      "iDisplayLength": 10,
      "sScrollX": "100%",
      "sScrollXInner": "100%",
      "bScrollCollapse": true
    });
          var assignedRoute = $('#assignedRoute').DataTable({
      aaSorting : [[0, 'desc']],
      "dom": "Bfrtip",
      "bPaginate": true,
      "bLengthChange": false,
      "bFilter": true,
      "bSort": true,
      "bInfo": true,
      "bAutoWidth": true,
      "iDisplayLength": 10,
      "sScrollX": "100%",
      "sScrollXInner": "100%",
      "bScrollCollapse": true
    });
</script>
</body>
@endsection
