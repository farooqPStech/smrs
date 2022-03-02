

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
        <h4>Route > Route List</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>All Route</h3>
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
                            <th>Route ID</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Sub</th>
                            <th>Branch Code</th>
                            <th>HHT_ID</th>
                            <th>Address</th>
                            <th>T_AC</th>
                            <th>Assinged Handheld</th>
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
          url:  "/route/list",
          type: 'GET',
          },
          columns: [
                  { data: 'id', name: 'id', 'visible': false},
                  { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                  { data: 'route', name: 'route' },
                  { data: 'comment', name: 'comment' },
                  { data: 'status', name: 'status' },
                  { data: 'sub', name: 'sub' },
                  { data: 'branch_code', name: 'branch_code' },
                  { data: 'hht_id', name: 'hht_id' },
                  { data: 'address', name: 'address' },
                  { data: 't_ac', name: 't_ac' },
                  { data: 'handheld', name: 'handheld' },
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

    $(document).ready( function () {
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
          "bScrollCollapse": true,
      });
    });

</script>
</body>
@endsection
