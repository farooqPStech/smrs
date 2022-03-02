@extends('layouts.sidebar')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h4>Log</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>System Log</h3>
                <div class="row">
                    <div class="card card-body">

                        <div class="tab">
                            <button class="tablinks" onclick="openType(event, 'Industrial')" id="defaultOpen">SMRS
                                Web</button>
                            <button class="tablinks" onclick="openType(event, 'Residential')">Handheld</button>
                        </div>
                        <div id="Industrial" class="tabcontent">
                            <table style="width: 100%;" id="industrial" class="table table-bordered table-striped">
                                <thead class="table-smrs">
                                    <tr>
                                        <th>#</th>
                                        <th>Number</th>
                                        <th>User</th>
                                        <th>Activity</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <br>
                            </table>
                            {{-- {{ $items->links() }} --}}
                        </div>
                        <div id="Residential" class="tabcontent">
                            <table style="width: 100%;" id="resident" class="table table-bordered table-striped">
                                <thead class="table-smrs">
                                    <tr>
                                        <th>#</th>
                                        <th>Number</th>
                                        <th>User</th>
                                        <th>Activity</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <br>
                            </table>
                            {{-- {{ $items->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        // $(document).ready( function () {

        //   var a = $('#data-table').DataTable({
        //       aaSorting : [[0, 'desc']],
        //       "dom": "Bfrtip",
        //        buttons : [ {
        //         extend : 'pdf',
        //         className: 'btn btn-smrs',
        //         exportOptions : {
        //             modifier : {
        //                 // DataTables core
        //                 order : 'current',  // 'current', 'applied', 'index',  'original'
        //                 page : 'all',      // 'all',     'current'
        //                 search : 'none',     // 'none',    'applied', 'removed'
        //                 selected: null,
        //             },
        //         },
        //        } ],
        //       "bPaginate": true,
        //       "bLengthChange": false,
        //       "bFilter": true,
        //       "bSort": true,
        //       "bInfo": true,
        //       "bAutoWidth": true,
        //       "iDisplayLength": 10,
        //       "sScrollX": "100%",
        //       "sScrollXInner": "100%",
        //       "bScrollCollapse": true,
        //       processing: true,
        //       serverSide: true,
        //       ajax: {
        //       url:  "/log/list",
        //       type: 'GET',
        //       },
        //       columns: [
        //               { data: 'id', name: 'id', 'visible': false},
        //               { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        //               { data: 'user', name: 'user' },
        //               { data: 'activity', name: 'activity' },
        //               { data: 'month', name: 'month' },
        //               { data: 'year', name: 'year' },
        //               { data: 'created_at', name: 'created_at' },
        //           ],
        //       order: [[0, 'desc']]
        //   });
        // });


        var industrial = $('#industrial').DataTable({
            aaSorting: [
                [0, 'desc']
            ],
            "dom": "Bfrtip",
            buttons: [{
                extend: 'pdf',
                className: 'btn btn-smrs',
                exportOptions: {
                    modifier: {
                        // DataTables core
                        order: 'current', // 'current', 'applied', 'index',  'original'
                        page: 'all', // 'all',     'current'
                        search: 'none', // 'none',    'applied', 'removed'
                        selected: null,
                    },
                },
            }],
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
                url: "/log/web",
                type: 'GET',
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    'visible': false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'activity',
                    name: 'activity'
                },
                {
                    data: 'month',
                    name: 'month'
                },
                {
                    data: 'year',
                    name: 'year'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ],
            order: [
                [0, 'asc']
            ]
        });

        var resident = $('#resident').DataTable({
            aaSorting: [
                [0, 'desc']
            ],
            "dom": "Bfrtip",
            buttons: [{
                extend: 'pdf',
                className: 'btn btn-smrs',
                exportOptions: {
                    modifier: {
                        // DataTables core
                        order: 'current', // 'current', 'applied', 'index',  'original'
                        page: 'all', // 'all',     'current'
                        search: 'none', // 'none',    'applied', 'removed'
                        selected: null,
                    },
                },
            }],
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
                url: "/log/handheld",
                type: 'GET',
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    'visible': false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'activity',
                    name: 'activity'
                },
                {
                    data: 'month',
                    name: 'month'
                },
                {
                    data: 'year',
                    name: 'year'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ],
            order: [
                [0, 'asc']
            ]
        });

        function openType(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        function openSubType(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabsubcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tabsublinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
@endsection
