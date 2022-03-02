

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
        <h4>Report > Billing Details By Route</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Billing Details By Route</h3>
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
                            <th>Route</th>
                            <th>Consumer Number</th>
                            <th>Address</th>
                            <th>Meter Number</th>
                            <th>Dial</th>
                            <th>Previous Reading Date</th>
                            <th>Current Reading Date</th>
                            <th>Previous Reading</th>
                            <th>Current Reading</th>
                            <th>Current Consumption</th>
                            <th>MMBTU Consumption</th>  
                            <th>Cutoff Consumption</th>
                            <th>Tariff</th> <!-- //end -->
                            <th>Bill Amount (RM)</th>
                            <th>GST Amount (RM)</th>
                            <th>GCPT Amount</th>
                            <th>Time Stamp</th>
                            <th>Meter Reader ID</th>
                            <th>Remark</th>
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
          url:  "/report/billingdetailsbyrouteresidenttable",
          type: 'GET',
          },
          columns: [
                    { data: 'id', name: 'id', 'visible': false},
                  { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                  { data: 'route', name: 'user' },
                  { data: 'consumer_number', name: 'user' },
                  { data: 'address', name: 'user' },
                  { data: 'meter_number', name: 'name' },
                  { data: 'dial', name: 'read' },
                  { data: 'last_reading_date', name: 'noaccess' },
                  { data: 'current_reading_date', name: 'unread' },
                  { data: 'previous_reading', name: 'note' }, 
                  { data: 'current_reading', name: 'time' },
                  { data: 'current_consumption', name: 'created_at' },
                  { data: 'mmbtu', name: 'logout' },
                  { data: 'cutoff_consumption', name: 'time' }, // idk 
                  { data: 'tariff_code', name: 'time' },
                  { data: 'bill_amount', name: 'time' },
                  { data: 'gst', name: 'time' }, //idk
                  { data: 'gcpt', name: 'time' },//idk
                  { data: 'timestamp', name: 'time' },
                  { data: 'mrid', name: 'time' },
                  { data: 'remark', name: 'time' },

              ],
          order: [[0, 'desc']],
      });
    });

</script>
</body>
@endsection
