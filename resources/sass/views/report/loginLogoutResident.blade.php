

@extends('layouts.sidebar')

@section('content')

<head>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.52/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  
</head>
<body>
<div class="row">
    <div class="col-md-12" >
        <h4>Report > Login Logout</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Login Logout</h3>
                  <div class="row">
                    <div class="card card-body">
                      <div style="float: left">
                      <!-- <button class="btn btn-smrs" style="width: 5%; float: left">PDF</button>  -->
                      </div><br>
                      <table id="allRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr class="column-filtering">
                            <th>#</th>
                            <th>#</th>
                            <th>FWID</th>
                            <th>MRID</th>
                            <th>Meter Reader Name</th>
                            <th>Read</th>
                            <th>No Access</th>
                            <th>Unread</th>
                            <th>Note</th>
                            <th>Elapsed Time (Min)</th>
                            <th>Login Date & Time</th>  <!-- //end -->
                            <th>Logout Date & Time</th>
                            <th>Average Time & Reading</th>
                          </tr>
                        </thead>
                        <br>
                      {{-- {{ $items->links() }} --}} 
                      </table> 
                    </div>
                  </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function () {
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
          "bScrollCollapse": true,
          processing: true,
          serverSide: true,
          ajax: {
          url:  "/report/loginlogoutresidenttable",
          type: 'GET',
          },
          columns: [
                 { data: 'id', name: 'id', 'visible': false},
                  { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                  { data: 'user', name: 'user' },
                  { data: 'user', name: 'user' },
                  { data: 'name', name: 'name' },
                  { data: 'read', name: 'read' },
                  { data: 'noaccess', name: 'noaccess' },
                  { data: 'unread', name: 'unread' },
                  { data: 'id', name: 'note' }, //note (idk)
                  { data: 'time', name: 'time' },
                  { data: 'created_at', name: 'created_at' },
                  { data: 'logout', name: 'logout' },
                  { data: 'time', name: 'time' },
              ],
          order: [[0, 'desc']],
      });
    });

</script>
</body>
@endsection
