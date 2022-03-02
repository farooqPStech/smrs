@extends('layouts.sidebar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4>Consumer</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>Consumer List</h3>
                <div class="row">
                    <div class="card card-body">
                        <div class="tab">
                            <button class="tablinks" onclick="openType(event, 'Industrial')"
                                id="defaultOpen">Industrial</button>
                            <button class="tablinks" onclick="openType(event, 'Residential')">Residential</button>
                        </div>
                        <div id="Industrial" class="tabcontent">
                            <br>
                            <div id="buttonExport1" style="float: left; "></div>
                            <br><br>
                            <table style="width: 100%;" id="industrial" class="table table-bordered table-striped">
                                <thead class="table-smrs">
                                    <tr>
                                        <th>#</th>
                                        <th>Consumer Number</th>
                                        <th>Old Acc Number</th>
                                        <th>Consumer Name</th>
                                        <th>Address</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <br>
                            </table>
                            {{-- {{ $items->links() }} --}}
                        </div>
                        <div id="Residential" class="tabcontent">
                            <br>
                            <div id="buttonExport2" style="float: left; "></div>
                            <br><br>
                            <table style="width: 100%;" id="resident" class="table table-bordered table-striped">
                                <thead class="table-smrs">
                                    <tr>
                                        <th>#</th>
                                        <th>Consumer Number</th>
                                        <th>Old Acc Number</th>
                                        <th>Consumer Name</th>
                                        <th>Address</th>
                                        <th>Created At</th>
                                        <th>Action</th>
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

        var industrial = $('#industrial').DataTable({
            aaSorting: [
                [0, 'desc']
            ],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "dom": "Blfrtip",
            buttons: {
                buttons: [
                    {
                        extend : 'pdf',
                        orientation: 'landscape',
                        className: 'btn-smrs',
                        title: function(){
                            return 'List Of Consumer (Industrial)'
                        }
                    },
                    {
                        extend : 'excel',
                        className: 'btn-smrs',
                        filename: function () {
                            return 'List Of Consumer (Industrial)';
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
            processing: true,
            serverSide: true,
            ajax: {
                url: "/consumer/industriallist",
                type: 'GET',
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'consumer_number',
                    name: 'consumer_number'
                },
                {
                    data: 'old_account_number',
                    name: 'old_account_number'
                },
                {
                    data: 'consumer_name',
                    name: 'consumer_name'
                },
                {
                    data: 'consumer_address_1',
                    name: 'consumer_address_1'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            order: [
                [0, 'asc']
            ]
        }).buttons()
            .container()
            .appendTo("#buttonExport1");

        var resident = $('#resident').DataTable({
            aaSorting: [
                [0, 'desc']
            ],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "dom": "Blfrtip",
            buttons: {
                buttons: [
                    {
                        extend : 'pdf',
                        orientation: 'landscape',
                        className: 'btn-smrs',
                        title: function(){
                            return 'List Of Consumer (Resident)'
                        }
                    },
                    {
                        extend : 'excel',
                        className: 'btn-smrs',
                        filename: function () {
                            return 'List Of Consumer (Resident)';
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
            processing: true,
            serverSide: true,
            ajax: {
                url: "/consumer/residentlist",
                type: 'GET',
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'consumer_number',
                    name: 'consumer_number'
                },
                {
                    data: 'old_account_number',
                    name: 'old_account_number'
                },
                {
                    data: 'consumer_name',
                    name: 'consumer_name'
                },
                {
                    data: 'consumer_address_1',
                    name: 'consumer_address_1'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            order: [
                [0, 'asc']
            ]
        }).buttons()
            .container()
            .appendTo("#buttonExport2");

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
