

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
        <h4>Setting > Billing Days</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Billing Days</h3>
                  <div class="row">
                    <div class="card card-body">
                       <table id="allRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr>
                            <th>No</th>
                            <th>Month Number</th>
                            <th>Month Name</th>
                            <th>Month Of Day</th>
                            <th>Updated At</th>
                            <th>Updated By</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if( !empty($billing) ){ ?>
                            <?php foreach( $billing as $bill ){?>
                            <tr>
                              <td>{{$bill->id}}</td>
                              <td>{{$bill->month_number}}</td>
                              <td>{{$bill->month_name}}</td>
                              <td>{{$bill->number_of_day}}</td>
                              <td>{{$bill->updated_at}}</td>
                              <td>{{$bill->updated_by}}</td>
                              <td align="center"><a href="/setting/editbilling/{{$bill->id}}" style="width: 90%" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> Edit</i></a><br><a href="/setting/removebilling/{{$bill->id}}" class="btn btn-danger" style="margin-top: 3%; width: 90%;" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-edit text-white"> Remove</i></a>
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
