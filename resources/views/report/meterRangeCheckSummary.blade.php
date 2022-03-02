

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
        <h4>Report > Meter Range Check Summary</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Meter Range Check Summary</h3>
                  <div class="row">
                    <div class="card card-body">
                        <div id="buttonExport" style="float: left"></div>
                        <table id="allRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr class="column-filtering">
                            <th>#</th>
                            <th>#</th>
                            <th>Route ID</th>
                            <th>Read Date</th>
                            <th>Total MTR</th>
                            <th>Zero</th>
                            <th>Low</th>
                            <th>High</th>
                            <th>Negative</th>
                            <th>Length_Err</th>
                            <th>Too High</th>  <!-- //end -->
                          </tr>
                        </thead>
                        <br>
                      {{-- {{ $items->links() }} --}}
                      </table>
                    </div>
                  </div>
                 <!--  <br>
                  <h3>Assinged Route</h3>
                  <div class="row">
                    <div class="card card-body">
                      <div style="float: left">
                      <button class="btn btn-smrs" style="width: 5%; float: left">PDF</button>
                      </div><br>
                      <table id="assignedRoute" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr>
                            <th>#</th>
                            <th>Route ID</th>
                            <th>Handheld UUID</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Created_at</th>
                            <th>Updated_at</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if( !empty($handheldRoutes) ){ ?>
                            <?php foreach( $handheldRoutes as $handheldRoute ){?>
                              <tr>
                                <td>{{$handheldRoute->id}}</td>
                                <td>{{$handheldRoute->route}}</td>
                                <td>{{$handheldRoute->handheld}}</td>
                                @if ($handheldRoute->status == 1)
                                <td>Active</td>
                                @else
                                <td>Deactive</td>
                                @endif
                                <td>{{$handheldRoute->date}}</td>
                                <td>{{$handheldRoute->created_at}}</td>
                                <td>{{$handheldRoute->updated_at}}</td>
                              </tr>
                              <?php }?>
                          <?php }?>
                        </tbody>
                      </table>
                    </div>
                  </div> -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function () {
      var allRoute = $('#allRoute').DataTable({
          aaSorting : [[0, 'desc']],
          lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
          "dom": "Blfrtip",
          buttons: {
              buttons: [
                  {
                      extend : 'pdf',
                      orientation: 'landscape',
                      className: 'btn-smrs',
                      title: function(){
                          return 'System Log'
                      }
                  },
                  {
                      extend : 'excel',
                      className: 'btn-smrs',
                      filename: function () {
                          return 'System Log';
                      }
                  }
              ],
              dom: {
                  button: {
                      className: 'btn'
                  },
              },
          },
          "bPaginate": true,
          "bLengthChange": true,
          "bFilter": true,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": true,
          "iDisplayLength": 25,
          "sScrollX": "100%",
          "sScrollXInner": "100%",
          "bScrollCollapse": true,
          processing: true,
          serverSide: true,
          ajax: {
          url:  "/report/meterrangechecksummarytable",
          type: 'GET',
          },
          columns: [
                  { data: 'id', name: 'id', 'visible': false},
                  { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                  { data: 'route', name: 'route' },
                  { data: 'read_date', name: 'comment' },
                  { data: 'total_meter', name: 'status' },
                  { data: 'zero', name: 'sub' },
                  { data: 'low', name: 'low' },
                  { data: 'high', name: 'hht_id' },
                  { data: 'negative', name: 'address' },
                  { data: 'length_err', name: 't_ac' },
                  { data: 'too_high', name: 'handheld' },
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
      }).buttons()
          .container()
          .appendTo("#buttonExport");

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
