

@extends('layouts.sidebar')

@section('content')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</head>
<body>
<div class="row">
    <div class="col-md-12" >
        <h4>Setting > Assign Table</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                  <div class="row">
                    <div class="col-md-12">
                      <h3>Assign Route to Handheld Setting</h3>
                      <div class="card card-body">
                        <div style="float: left">
                        <button class="btn btn-smrs" style="width: 11%; float: left">PDF</button> 
                        </div><br>
                         <table id="allRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr>
                            <th>#</th>
                            <th>UUID</th>
                            <th>Branch Code</th>
                            <th>Route</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if( !empty($handhelds) ){ ?>
                            <?php foreach( $handhelds as $handheld ){?>
                              <tr class='id'>
                                <td>{{$handheld->id}}</td>
                                <td>{{$handheld->uuid}}</td>
                                <td>{{$handheld->branch_code}}</td>
                                <td><select name="route[]" id="route">
                                      <option value="">Select...</option>
                                    <?php foreach( $routes as $route ){?>
                                      <option value="{{$route->id}}">{{$route->route}}</option>
                                    <?php }?>
                                  </select>
                                </td>
                              </tr>
                              <?php }?>
                          <?php }?>
                        </tbody> 
                      </table>
                        <br>
                        <a style="float: left;" id="button" class="btn btn-smrs col-md-4 float-right">Assign</a> 
                      </div>
                    </div>
                    <br>
                    <div class="col-md-12">
                    <h3>Meter Reader Route Listing Setting</h3>
                      <div class="card card-body">
                        <div style="float: left">
                        <button class="btn btn-smrs" style="width: 11%; float: left">PDF</button> 
                        </div><br>
                        <table id="assignedRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr>
                            <th>#</th>
                            <th>Handheld UUID</th>
                            <th>Branch Code</th>
                            <th>Route</th>
                            <th>Meter Reader</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if( !empty($routeAssignments) ){ ?>
                            <?php foreach( $routeAssignments as $handheldRoute ){?>
                              @if(auth()->user()->branch_id == $handheldRoute->branch->id)
                              <tr>
                                <td>{{$handheldRoute->id}}</td>
                                <td>{{$handheldRoute->handheld->uuid}}</td>
                                <td>{{$handheldRoute->branch_code}}</td>
                                <td>{{$handheldRoute->route}}</td>
                                <?php if( !empty($handheldRoute->user) ){ ?>
                                  <td>{{$handheldRoute->user->full_name}} ({{$handheldRoute->user->id}})</td>
                                <?php } else { ?>
                                  <td></td>
                                <?php }?>
                              </tr>
                              @endif
                              <?php }?>
                          <?php }?>
                        </tbody> 
                      </table>
                      <br>
                        <a style="float: left;" id="button2" class="btn btn-smrs col-md-4 float-right">Unassign</a> 
                      </div>
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

      var table = $('#allRoute').DataTable();

      $('#button').click( function () {

        var table = $("#allRoute tbody");
        var routes = [];

        table.find('tr').each(function (i) {
        var $tds = $(this).find('td'),
            Routeid = $tds.eq(3).find('input,select').val();
            routes.push(Routeid);
        });

        var table1 = $('#allRoute').DataTable();
        var ids = table1.cells('.id',0).data().toArray();   

        window.location.href = "/route/routeassignmentnewassign/" + routes + "/" + ids;
      } );

      $('#assignedRoute tbody').on( 'click', 'tr', function () {
          $(this).toggleClass('selected');
      } );
   
      $('#button2').click( function () {

        if(assignedRoute.cells('.selected',0).data().toArray() == '' )
        {
         swal("Oh no!", "Please highlight row on table on the right!", "error");
        }
        else
        {
         var array = assignedRoute.cells('.selected',0).data().toArray(); 
         var data = [];
        $.each(array, function(idx2,val2) {                    
          var str = val2;
          data.push(str);
        });

         // alert(data);
        window.location.href = "/route/routeassignmentunassign/" + data;
        }

      } );

</script>
</body>
@endsection
