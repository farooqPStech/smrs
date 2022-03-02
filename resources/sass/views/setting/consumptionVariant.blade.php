

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
        <h4>Setting > Consumption Variant</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <h3>Consumption Variant - Edit</h3>
                    <form method="POST" action="/setting/updatecv" enctype="multipart/form-data">
                        <div class="card card-body">
                          @csrf
                            <label>High Percentage Trigger</label>
                            <input type="text" class="" style="border:none" name="high" id="high" value="{{$cv->high}}"><br>
                            <label>Low Percentage Trigger</label>
                            <input type="text" class="" style="border:none" name="low" id="low" value="{{$cv->low}}"><br>
                            <input type="text" class="" style="border:none" name="id" id="id" value="{{$cv->id}}" hidden>
                            <div style="float: right;">
                              <button style="float: right;" class="btn btn-smrs col-md-3">Update</button>
                            </div>
                        </div>
                    </form><br>
                    <div class="card card-body">
                     <table id="allConsumer" class="table table-bordered table-striped">
                        <thead class="table-smrs">
                          <tr>
                            <th>#</th>
                            <th>Consumer Number</th>
                            <th>Consumer Name</th>
                            <th>Consumer Address</th>
                            <th>High Percentage Trigger</th>
                            <th>Low Percentage Trigger</th>
                            <th>Last Updated By</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if( !empty($cv2) ){ ?>
                            <?php foreach( $cv2 as $cvIndividual ){?>
                                @if($cvIndividual->consumer_id != 0)
                                  <tr>
                                    <td>{{$cvIndividual->id}}</td>
                                    <td>{{$cvIndividual->consumer->consumer_number}}</td>
                                    <td>{{$cvIndividual->consumer->consumer_name}}</td>
                                    <td>{{$cvIndividual->consumer->consumer_address_1}}, {{$cvIndividual->consumer->consumer_address_2}}</td>
                                    <td>{{$cvIndividual->high}}</td>
                                    <td>{{$cvIndividual->low}}</td>
                                    <td>{{$cvIndividual->updated_by}}</td>
                                    <td align="center"><a href="/setting/editcvindividual/{{$cvIndividual->id}}" style="width: 90%" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> Edit</i></a><br></td>  
                                  </tr>
                                @endif
                            <?php }?>
                          <?php }?>
                        </tbody>
                      </table> 
                     </div>

        </div>
    </div>
</div>
<script type="text/javascript">
 var allConsumer = $('#allConsumer').DataTable({
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
      "bScrollCollapse": true
    });

</script>
</body>
@endsection
