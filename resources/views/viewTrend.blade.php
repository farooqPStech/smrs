

@extends('layouts.sidebar')

@section('content')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
</head>
<body>
<div class="row">
    <div class="col-md-12" >
      <h4>Meter > View Trend</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <h4>Meter</h4>
                    <!-- Content Row -->
                <div class="card card-body">
                  <div class="row" style="font-size: 14px">
                    <div class="col-md-5"><a>Account No: {{$consumer->consumer_name}}</a><br><a>Meter No: {{$meterDetail->meter_number}}</a></div> 
                   <!--  <div class="col-md-2"><a>Consumption Trend From</a></div>
                    <div class="col-md-2"><a><input id="text" type="text" class="form-control" name="text" value=""></a></div> to
                    <div class="col-md-2"><a><input id="text2" type="text2" class="form-control" name="text" value=""></a></div> -->
                  </div>
                  <div id="container"></div>


                </div>
        </div>
    </div>
</div>


    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- Page level custom scripts -->
    <script type="text/javascript">
      var userData = 6;

    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            // text: 'New User Growth, 2020'
            text:''
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
            }
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
        series: [{
            name: 'Consumption (sm3)',
            data: [{{$meter1 ?? '0'}},{{$meter2 ?? '0'}},{{$meter3 ?? '0'}},{{$meter4 ?? '0'}},{{$meter5 ?? '0'}},{{$meter6 ?? '0'}},{{$meter7 ?? '0'}},{{$meter8 ?? '0'}},{{$meter9 ?? '0'}},{{$meter10 ?? '0'}},{{$meter11 ?? '0'}},{{$meter12 ?? '0'}},],
            color: "#00af9f"
        }],
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

</body>
@endsection
