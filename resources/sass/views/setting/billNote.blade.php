

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
                <h3>Bill Note</h3>
                  <div class="row">
                    <div class="card card-body">
                      <div style="float: left">
                      <button class="btn btn-smrs" style="width: 5%; float: left">PDF</button> 
                      </div><br>
                      <div style="float: right">
                      <a href="/setting/addbillnote" class="btn btn-smrs" style="width: 20%; float: right">Add New</a> 
                      </div><br>
                      <table id="allRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr>
                            <th>#</th>
                            <th>Note</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Updated By</th>
                            <th>Created At</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if( !empty($app) ){ ?>
                            <?php foreach( $app as $item ){?>
                              <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->bill_notes}}</td>
                                <td>{{$item->comment}}</td>
                                @if($item->status == 0)
                                <td style="color: red;"><b>Inactive</b></td>
                                @else
                                <td style="color: limegreen;"><b>Active</b></td>
                                @endif
                                <td>{{$item->updated_by}}</td>
                                <td>{{date('d-m-Y', strtotime($item->created_at))}}</td>
                                <td align="center"><a href="/setting/editbillnote/{{$item->id}}" style="width: 90%" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> Edit</i></a><br><a href="/setting/setactivebillnote/{{$item->id}}" class="btn btn-warning" style="margin-top: 3%; width: 90%;" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-edit text-white"> Set Active</i></a></td> 
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
</script>
</body>
@endsection
