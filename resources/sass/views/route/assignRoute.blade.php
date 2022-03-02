

@extends('layouts.sidebar')

@section('content')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.52/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</head>
<body>
<div class="row">
    <div class="col-md-12" >
        <h4>Route > Assign Route</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                  <div class="row">
                    <div class="col-md-12">
                      <h3>Assign Route to Handheld</h3>
                      <div class="card card-body">
                        <div style="float: left">
                        </div><br>
                       <!--  <select class="js-data-example-ajax"></select> -->
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
                                <td><select class="js-example-basic-single" name="route[]" id="route">
                                      <option value="">Select...</option>
                                    <?php foreach( $routes as $route ){?>
                                      <option value="{{$route->id}}">{{$route->route}} <?php if($route->sub){ ?>  ({{$route->sub}}) <?php }?></option>
                                    <?php }?>
                                  </select>
                                </td>
                              </tr>
                              <?php }?>
                          <?php }?>
                        </tbody>
                      </table>
                        <br>
                        <div class="col-md-12">
                          <a style="float: left" id="button" class="btn btn-smrs col-md-4">Assign</a>
                          <a style="float: right" id="buttonAssignAll" class="btn btn-warning col-md-4">Restore Default Setting</a>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="col-md-12">
                      <br>
                    <h3>Meter Reader Route Listing</h3>
                      <div class="card card-body">
                        <div style="float: left">
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
                          <?php if( !empty($handheldRoutes) ){ ?>
                            <?php foreach( $handheldRoutes as $handheldRoute ){?>
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
           buttons: {
            buttons: [
             {
               extend : 'pdf',
               className: 'btn-smrs'
             }
            ],
            dom: {
            button: {
                 className: 'btn'
            },
            },
          },
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
           buttons: {
            buttons: [
             {
               extend : 'pdf',
               className: 'btn-smrs'
             }
            ],
            dom: {
            button: {
                 className: 'btn'
            },
            },
          },
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

        window.location.href = "/route/newassignroute/" + routes + "/" + ids;
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
        window.location.href = "/route/unassign/" + data;
        }

      } );

      $('#buttonAssignAll').click( function () {

         swal({
            text: "Are you sure you want to Assign Previous Setting?",
            icon: "info",
            buttons: ['NO', 'YES'],
            dangerMode: true
          })
          .then(function(value) {
            if(value == true){
              window.location.href = "/route/assignrouteprev";
            }
          });

      } );



</script>
</body>
@endsection
