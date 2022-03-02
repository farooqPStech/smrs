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
            <div class="col-md-12">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h4>Meter</h4>

                    <div class="row">
                        <div class="card card-body">
                            <div class="tab">
                                <button class="tablinks" onclick="openType(event, 'Industrial')"
                                    id="defaultOpen">Industrial</button>
                                <button class="tablinks" onclick="openType(event, 'Residential')">Residential</button>
                            </div>

                            <div id="Industrial" class="tabcontent">
                                <h3>Gas Malaysia Distribution SDN BHD</h3>
                                <div class="tab">
                                    <?php if( !empty($branches) ){ ?>
                                    <?php foreach( $branches as $branch ){?>
                                    @if (auth()->user()->branch_id == $branch->id)
                                        <button class="tabsublinks" onclick="openSubType(event, '{{ $branch->name }}')"
                                            <?php if (auth()->user()->branch_id == $branch->id) {
                                                print ' id="subDefaultOpen" ';
                                            } ?>>{{ $branch->name }}</button>
                                    @elseif(auth()->user()->type == 2 || auth()->user()->type == 1 ||
                                        auth()->user()->type == 5)
                                        <button class="tabsublinks" onclick="openSubType(event, '{{ $branch->name }}')"
                                            <?php if (auth()->user()->branch_id == $branch->id) {
                                                print ' id="subDefaultOpen" ';
                                            } ?>>{{ $branch->name }}</button>

                                    @endif
                                    <?php }?>
                                    <?php }?>
                                </div>

                                <?php if( !empty($branches) ){ ?>
                                <?php foreach( $branches as $branch ){?>

                                @if (auth()->user()->type == 2 || auth()->user()->type == 1 || auth()->user()->type == 5)

                                    <div id="{{ $branch->name }}" class="tabsubcontent"
                                        style="overflow-x: auto; overflow-y: auto;">
                                        <h3>{{ $branch->name }} Meter Reading</h3>
                                        <table id="{{ $branch->name }}Table" class="table table-bordered table-striped">
                                            <thead class="table-smrs">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Account Number</th>
                                                    <th>Meter Number</th>
                                                    <th>Consumer Name</th>
                                                    <th>Area</th>
                                                    <th>Meter Location</th>
                                                    <th>Meter Type</th>
                                                    <th>Obstacle Code</th>
                                                    <th>Reading (sm3)</th>
                                                    <th>MMBTU (sm3)</th>
                                                    <th>Issue Code</th>
                                                    <th>Image</th>
                                                    <th>Trend</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach( $readings as $reading ){?>
                                                @if ($reading->branch->name == $branch->name)
                                                    <tr>
                                                        <td>{{ $reading->id }}</td>
                                                        <td>{{ $reading->consumer->consumer_number }}</td>
                                                        <td>{{ $reading->meter->meter_number }}</td>
                                                        <td>{{ $reading->consumer->consumer_name }}</td>
                                                        <td>{{ $reading->consumer->area_code }}</td>
                                                        <td>{{ $reading->meter_location->description }}</td>
                                                        <td>{{ $reading->meter->meter_type }}</td>
                                                        <td>
                                                            @if ($reading->obstacle_code)
                                                                {{ $reading->obstacle_code->description }}                                                            
                                                            @endif

                                                        </td>
                                                        <td>{{ $reading->reading }}</td>
                                                        <td>{{ $reading->billable }}</td>
                                                        <td>
                                                          @if ($reading->issue_code)
                                                               {{ $reading->issue_code->description }}
                                                          @endif
                                                         
                                                        </td>
                                                        <!-- The Modal -->

                                                        <td><a onclick="image({{ $reading->id }})"><img
                                                                    id="{{ $reading->name }}myImg"
                                                                    style="height: 100px; width: 100px"
                                                                    src="data:image/gif;base64,{{ $reading->image }}"></a>
                                                        </td>
                                                        <td align="center"><a href="viewtrend/{{ $reading->meter_id }}"
                                                                class="btn btn-smrs" data-toggle="tooltip"
                                                                data-placement="top" title="Edit"><i
                                                                    class="fa fa-edit text-white"> View Trend</i></a></td>

                                                    </tr>
                                                @endif
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>

                                @else

                                    @if (auth()->user()->branch_id == $branch->id)

                                        <div id="{{ $branch->name }}" class="tabsubcontent"
                                            style="overflow-x: auto; overflow-y: auto;">
                                            <h3>{{ $branch->name }} Meter Reading</h3>
                                            <table id="{{ $branch->name }}Table"
                                                class="table table-bordered table-striped">
                                                <thead class="table-smrs">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Account Number</th>
                                                        <th>Meter Number</th>
                                                        <th>Consumer Name</th>
                                                        <th>Area</th>
                                                        <th>Meter Location</th>
                                                        <th>Meter Type</th>
                                                        <th>Obstacle Code</th>
                                                        <th>Reading (sm3)</th>
                                                        <th>MMBTU (sm3)</th>
                                                        <th>Issue Code</th>
                                                        <th>Image</th>
                                                        <th>Trend</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach( $readings as $reading ){?>
                                                    @if ($reading->branch->name == $branch->name)
                                                        <tr>
                                                            <td>{{ $reading->id }}</td>
                                                            <td>{{ $reading->consumer->consumer_number }}</td>
                                                            <td>{{ $reading->meter->meter_number }}</td>
                                                            <td>{{ $reading->consumer->consumer_name }}</td>
                                                            <td>{{ $reading->consumer->area_code }}</td>
                                                            <td>{{ $reading->meter_location->description }}</td>
                                                            <td>{{ $reading->meter->meter_type }}</td>
                                                            <td>{{ $reading->obstacle_code->description }}</td>
                                                            <td>{{ $reading->reading }}</td>
                                                            <td>{{ $reading->billable }}</td>
                                                            <td>{{ $reading->issue_code->description }}</td>
                                                            <!-- The Modal -->

                                                            <td><a onclick="image({{ $reading->id }})"><img
                                                                        id="{{ $reading->name }}myImg"
                                                                        style="height: 100px; width: 100px"
                                                                        src="data:image/gif;base64,{{ $reading->image }}"></a>
                                                            </td>
                                                            <td align="center"><a
                                                                    href="viewtrend/{{ $reading->meter_id }}"
                                                                    class="btn btn-smrs" data-toggle="tooltip"
                                                                    data-placement="top" title="Edit"><i
                                                                        class="fa fa-edit text-white"> View Trend</i></a>
                                                            </td>


                                                        </tr>
                                                    @endif
                                                    <?php }?>
                                                </tbody>
                                            </table>
                                        </div>

                                    @endif
                                @endif
                                <?php }?>
                                <?php }?>

                            </div>
                            <div id="Residential" class="tabcontent" style="overflow-x: auto; overflow-y: auto;">
                                <h3>GAS Malaysia Energy Services SDN BHD</h3>
                                <div style="overflow-x: auto; overflow-y: auto;">
                                    <table id="residentialTable" class="table table-bordered table-striped"
                                        style="width: 100px;">
                                        <thead class="table-smrs">
                                            <tr>
                                                <th>#</th>
                                                <th>Account Number</th>
                                                <th>Meter Number</th>
                                                <th>Consumer Name</th>
                                                <th>Area</th>
                                                <th>Meter Location</th>
                                                <th>Meter Type</th>
                                                <th>Obstacle Code</th>
                                                <th>Reading (sm3)</th>
                                                <th>MMBTU (sm3)</th>
                                                <th>Issue Code</th>
                                                <th>Image</th>
                                                <th>Trend</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach( $readingResidents as $reading ){?>
                                            @if ($reading->consumer->consumer_type == 01)
                                                <tr>
                                                    <td>{{ $reading->id }}</td>
                                                    <td>{{ $reading->consumer->consumer_number }}</td>
                                                    <td>{{ $reading->meter->meter_number }}</td>
                                                    <td>{{ $reading->consumer->consumer_name }}</td>
                                                    <td>{{ $reading->consumer->area_code }}</td>
                                                    <td>{{ $reading->meter_location->description }}</td>
                                                    <td>{{ $reading->meter->meter_type }}</td>
                                                    <td>{{ $reading->obstacle_code->description }}</td>
                                                    <td>{{ $reading->reading }}</td>
                                                    <td>{{ $reading->billable }}</td>
                                                    <td>{{ $reading->issue_code->description }}</td>
                                                    <!-- The Modal -->

                                                    <td><a onclick="image({{ $reading->id }})"><img
                                                                id="{{ $reading->name }}myImg"
                                                                style="height: 100px; width: 100px"
                                                                src="data:image/gif;base64,{{ $reading->image }}"></a>
                                                    </td>
                                                    <td align="center"><a href="viewtrend/{{ $reading->meter_id }}"
                                                            class="btn btn-smrs" data-toggle="tooltip"
                                                            data-placement="top" title="Edit"><i
                                                                class="fa fa-edit text-white"> View Trend</i></a></td>


                                                </tr>
                                            @endif
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Bootstrap core JavaScript-->
        <!-- <script src="vendor/jquery/jquery.min.js"></script> -->

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script type="text/javascript">
            var branches = {!! json_encode($branches->toArray()) !!};

            var readings = {!! json_encode($readings->toArray()) !!};

            // console.log(branches);

            for (var i = 0; i < branches.length; i++) {
                // var branch = branches[i];
                // console.log(branches[i]['name']+'Table');
                $('#' + branches[i]['name'] + 'Table').DataTable({
                    aaSorting: [
                        [0, 'desc']
                    ],
                    "dom": "Bfrtip",
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "iDisplayLength": 10,
                });

            }

            for (var i = 0; i < readings.length; i++) {
                // var branch = branches[i];
                // console.log(branches[i]['name']+'Table');
                $('#' + readings[i]['name'] + 'Table').DataTable({
                    aaSorting: [
                        [0, 'desc']
                    ],
                    "dom": "Bfrtip",
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "iDisplayLength": 10,
                });
            }

            var residentialTable = $('#residentialTable').DataTable({
                aaSorting: [
                    [0, 'desc']
                ],
                "dom": "Bfrtip",
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bAutoWidth": true,
                "iDisplayLength": 10,
            });

            function verify(readingId, userType, userId) {
                swal({
                        text: "Are you sure you want to Verify?",
                        icon: "info",
                        buttons: ['NO', 'YES'],
                        dangerMode: true
                    })
                    .then(function(value) {
                        if (value == true) {
                            window.location.href = "/home/verify/" + readingId + "/" + userType + "/" + userId;
                        }
                    });
            }

            function revoke(readingId, userType, userId) {
                swal({
                        text: "Are you sure you want to Revoke?",
                        icon: "info",
                        buttons: ['NO', 'YES'],
                        dangerMode: true
                    })
                    .then(function(value) {
                        if (value == true) {
                            window.location.href = "/home/revoke/" + readingId + "/" + userType + "/" + userId;
                        }
                    });
            }


            function image(readingId) {
                fetch('/home/image/' + readingId)
                    .then(results => {
                        return results.json();
                    })
                    .then(json => {
                        const movie = json;
                        if (!movie) {
                            return swal("No movie was found!");
                        }
                        const imageURL = movie;
                        swal({
                            button: false,
                            icon: "data:image/gif;base64," + imageURL,
                        });
                    })
                    .catch(err => {
                        if (err) {
                            swal("Oh noes!", "The AJAX request failed!", "error");
                        } else {
                            swal.stopLoading();
                            swal.close();
                        }
                    });
            }

            function reject(readingId, userType, userId) {
                swal({
                        text: 'Please State The Reason',
                        content: "input",
                        button: {
                            text: "Send",
                            closeModal: false,
                        },
                        dangerMode: true
                    })
                    .then(value => {
                        if (value) {
                            // console.log(value)
                            window.location.href = "/home/reject/" + readingId + "/" + userType + "/" + userId + "/" +
                                value;
                        } else {
                            swal({
                                icon: "warning",
                                text: 'Please State Reason!',
                            });
                        }
                    });
            }

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
            document.getElementById("subDefaultOpen").click();
        </script>

    </body>
@endsection
