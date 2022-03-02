@extends('layouts.sidebar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4>Consumer > View Consumer</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h4>Consumer</h4>
                <!-- Content Row -->
                <div class="card card-body">
                    <div class="row" style="font-size: 18px">
                        <div class="col-md-5"><a>Account No: {{ $consumer->consumer_name }} ({{ $consumer->consumer_number }})</a><br></div>
                        <!--  <div class="col-md-2"><a>Consumption Trend From</a></div>
                        <div class="col-md-2"><a><input id="text" type="text" class="form-control" name="text" value=""></a></div> to
                        <div class="col-md-2"><a><input id="text2" type="text2" class="form-control" name="text" value=""></a></div> -->
                    </div>
                    <div id="container"></div>
                    <br>
                    <h3>Meter</h3>
                    <table id="allMeter" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                            <tr>
                                <th>#</th>
                                <th>Meter Number</th>
                                <th>Meter Sequence Number</th>
                                <th>Dial Length</th>
                                <th>Meter Type</th>
                                <!-- <th>Last Reading</th> -->
                                <!-- <th>Created At</th> -->
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( !empty($allMeter) ){ ?>
                            <?php foreach( $allMeter as $meter ){?>
                            <tr>
                                <td>{{ $meter->id }}</td>
                                <td>{{ $meter->meter_number }}</td>
                                <td>{{ $meter->meter_sequence_number }}</td>
                                <td>{{ $meter->dial_length }}</td>
                                <td>{{ $meter->meter_type }}</td>
                                <!-- <td>{{ $meter->last_reading }}</td> -->
                                <!-- <td>{{ $meter->created_at->format('d-M-Y') }}</td> -->
                                <!--                               <td align="center"><a href="/meter/editmeter/{{ $meter->id }}" style="width: 90%" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> Edit</i></a><br>
                                  <a onclick="remove({{ $meter->id }})" class="btn btn-danger" style="margin-top: 3%; width: 90%;" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-edit text-white"> Remove</i></a></td> -->
                            </tr>
                            <?php }?>
                            <?php }?>
                        </tbody>
                    </table>
                    <br>
                    <h3>Bill</h3>
                    <table id="allBill" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                            <tr>
                                <th>#</th>
                                <th>Deposit Amount</th>
                                <th>Current Bill</th>
                                <th>Arrears</th>
                                <th>Current Total</th>
                                <th>Bill Code</th>
                                <!-- <th>Last Reading</th> -->
                                <!-- <th>Created At</th> -->
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( !empty($allBill) ){ ?>
                            <?php foreach( $allBill as $bill ){?>
                            <tr>
                                <td>{{ $bill->id }}</td>
                                <td>{{ $bill->deposti_amount }}</td>
                                <td>{{ $bill->current_bill }}</td>
                                <td>{{ $bill->arrears }}</td>
                                <td>{{ $bill->current_total }}</td>
                                <td>{{ $bill->bill_code }}</td>
                                <!-- <td>{{ $bill->last_reading }}</td> -->
                                <!-- <td>{{ $bill->created_at->format('d-M-Y') }}</td> -->
                                <!--                               <td align="center"><a href="/bill/editbill/{{ $bill->id }}" style="width: 90%" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> Edit</i></a><br>
                                  <a onclick="remove({{ $bill->id }})" class="btn btn-danger" style="margin-top: 3%; width: 90%;" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-edit text-white"> Remove</i></a></td> -->
                            </tr>
                            <?php }?>
                            <?php }?>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>



@endsection

@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- Page level custom scripts -->
    <script type="text/javascript">
        var allMeter = $('#allMeter').DataTable({
            aaSorting: [
                [0, 'desc']
            ],
            "dom": "Bfrtip",
            buttons: {
                buttons: [{
                    extend: 'pdf',
                    className: 'btn-smrs'
                }],
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

        var allBill = $('#allBill').DataTable({
            aaSorting: [
                [0, 'desc']
            ],
            "dom": "Bfrtip",
            buttons: {
                buttons: [{
                    extend: 'pdf',
                    className: 'btn-smrs'
                }],
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

        Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                // text: 'New User Growth, 2020'
                text: ''
            },
            subtitle: {
                // text: 'Source: positronx.io'
            },
            xAxis: {
                categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ]
            },
            yAxis: {
                title: {
                    text: 'Consumption (sm3)'
                },
                min: 0,
            },
            // legend: {
            //     layout: 'vertical',
            //     align: 'right',
            //     verticalAlign: 'middle'
            // },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y} sm3'
                    }
                }
            },
            series: @json($arr),
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    </script>
@endsection
