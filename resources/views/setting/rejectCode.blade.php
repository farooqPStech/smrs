

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
        <h4>Setting > Reject Code</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Reject Code List</h3>
                  <div class="row">
                    <div class="card card-body">
                      <div style="float: right">
                      <a href="/setting/addreject" class="btn btn-smrs" style="width: 20%; float: right">Add New</a>
                      </div><br>
                      <table id="allRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Last Modified</th>
                            <th>Last User Modified</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if( !empty($rejects) ){ ?>
                            <?php foreach( $rejects as $reject ){?>
                              <tr>
                                <td>{{$reject->id}}</td>
                                <td>{{$reject->code_number}}</td>
                                <td>{{$reject->description}}</td>
                                <td>{{$reject->updated_at}}</td>
                                <td>{{$reject->updated_by}}</td>
                                <td align="center"><a href="/setting/editreject/{{$reject->id}}" style="width: 90%" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> Edit</i></a><br><a href="/setting/removereject/{{$reject->id}}" class="btn btn-danger" style="margin-top: 3%; width: 90%;" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-edit text-white"> Remove</i></a></td>
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
