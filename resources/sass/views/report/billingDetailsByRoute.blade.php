

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
                      </div><br>
                      <table id="allRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr class="column-filtering">
                            <th>#</th>
                            <th>Route ID</th>
                            <th>Address</th>
                            <th>Previous Reading Date</th>
                            <th>Current Reading Date</th>
                            <th>Previous Reading Figure</th>
                            <th>Current Reading Figure</th>
                            <th>Current Consumption</th>
                            <th>mmBtu Consumption</th>//idk
                            <th>Average Consumption</th>
                            <th>Tariff</th>
                            <th>Current Bill Amount</th>//idk
                            <th>Estimate Adjustment</th>
                            <th>Temperature</th>
                            <th>Pressure</th>
                            <th>GCPT Amount</th>//idk
                            <th>Time</th>//idk
                            <th>Meter Reader ID</th>
                            <th>Remark</th>//idk
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
          url:  "/report/billingdetailsbyroutetable",
          type: 'GET',
          },
          columns: [
                  { data: 'id', name: 'id', 'visible': true},
                  { data: 'route', name: 'route', 'visible': true},
                  { data: 'consumer_address_1', name: 'consumer_address_1', 'visible': true}, 
                  { data: 'last_reading_date', name: 'last_reading_date', 'visible': true},
                  { data: 'created_at', name: 'created_at', 'visible': true},
                  { data: 'last_reading', name: 'last_reading', 'visible': true},
                  { data: 'reading', name: 'reading', 'visible': true},
                  { data: 'current', name: 'current', 'visible': true},
                  { data: 'mmbtu', name: 'mmbtu', 'visible': true},
                  { data: 'daily_average_consumption', name: 'daily_average_consumption', 'visible': true},
                  { data: 'tariff_code', name: 'tariff_code', 'visible': true},
                  { data: 'current_bill_amount', name: 'current_bill_amount', 'visible': true},
                  { data: 'adjustment_consumption', name: 'adjustment_consumption', 'visible': true},
                  { data: 'temperature', name: 'temperature', 'visible': true},
                  { data: 'pressure', name: 'pressure', 'visible': true},
                  { data: 'gcpt', name: 'gcpt', 'visible': true},
                  { data: 'time', name: 'time`', 'visible': true},
                  { data: 'mrid', name: 'mrid', 'visible': true}, 
                  { data: 'remark', name: 'remark', 'visible': true}, //idk
              ],
          order: [[0, 'desc']],
        //   initComplete: function () {
        //   this.api()
        //     .columns()
        //     .every(function (index) {
        //       console.log(index)
        //       if (index == 9) {
        //         var column = this
        //         // $('<th>').appendTo(".column-filtering");
        //           // .appendTo('.column-filtering th:eq(' + index + ')')
        //       var select = $('<select><option value=""></option></select>')
        //           .appendTo('.column-filtering th:eq(' + index + ')')
        //           .on('change', function () {
        //             var val = $.fn.dataTable.util.escapeRegex($(this).val())

        //             column.search(val ? '^' + val + '$' : '', true, false).draw()
        //           })

        //         column
        //           .data()
        //           .unique()
        //           .sort()
        //           .each(function (d, j) {
        //             select.append('<option value="' + d + '">' + d + '</option>')
        //           })
        //         // $('</th>').appendTo(".column-filtering");
        //       } else {
        //         // $('<th></th>').appendTo(".column-filtering");
        //       }
        //       $(select).click(function (e) {
        //         e.stopPropagation()
        //       })
        //     })
        // },
      });

      // allRoute
      // .on('order.dt search.dt', function () {
      //   allRoute
      //     .column(0, { search: 'applied', order: 'applied' })
      //     .nodes()
      //     .each(function (cell, i) {
      //       cell.innerHTML = i + 1
      //       allRoute.cell(cell).invalidate('dom')
      //     })
      // })
      // .draw()

    });

</script>
</body>
@endsection
