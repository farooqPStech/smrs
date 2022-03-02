

@extends('layouts.sidebar')

@section('content')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="row">
    <div class="col-md-12" >
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <h4>Alert</h4>
                <div class="row">
                  <div class="card card-body">
                    <div class="tab">
                      <button class="tablinks" onclick="openType(event, 'Industrial')" id="defaultOpen">Industrial</button>
                      <button class="tablinks" onclick="openType(event, 'Residential')">Residential</button>
                    </div>

                    <div id="Industrial" class="tabcontent">
                      <h3>Gas Malaysia Distribution SDN BHD</h3>
                      <div class="tab">
                        <?php if( !empty($branches) ){ ?>
                            <?php foreach( $branches as $branch ){?>
                                    @if(auth()->user()->branch_id == $branch->id)

                                        <button name="{{$branch->id}}" class="tabsublinks alert{{$branch->id}}" onclick="openSubType(event, '{{$branch->name}}')" <?php if(auth()->user()->branch_id==$branch->id){ print ' id="subDefaultOpen" '; }?>>{{$branch->name}}</button>


                                    @elseif(auth()->user()->type == 2 || auth()->user()->type == 1 || auth()->user()->type == 5)
                                     <button name="{{$branch->id}}" class="tabsublinks alert{{$branch->id}}" onclick="openSubType(event, '{{$branch->name}}')" <?php if(auth()->user()->branch_id==$branch->id){ print ' id="subDefaultOpen" '; }?>>{{$branch->name}}</button>

                                    @endif
                         <?php }?>
                          <?php }?>
                      </div>

                      <?php if( !empty($branches) ){ ?>
                            <?php foreach( $branches as $branch ){?>

                            @if (auth()->user()->type == 2 || auth()->user()->type == 1 || auth()->user()->type == 5)

                              <div id="{{$branch->name}}" class="tabsubcontent"  style="overflow-x: auto; overflow-y: auto;">
                                <h3>{{$branch->name}} Meter Reading</h3>
                                <table id="{{$branch->name}}Table" class="table table-bordered table-striped">
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
                                      <th>Z Factor</th>
                                      <th>Actual CF</th>
                                      <th>Actual Consumption Variant</th>
                                      <th>Average 3 Month Consumption</th>
                                      <th>MMBTU (sm3)</th>
                                      <th>Issue Code</th>
                                      <th>Image</th>
                                      <th>Trend</th>
                                      <th>Supervisor</th>
                                      <th>Superuser / Final Approver</th>
                                    </tr>
                                  </thead>
                                   <tbody>
                                  <?php foreach( $readings as $reading ){?>
                                    @if($reading->branch->name == $branch->name)
                                    <tr>
                                        <td>{{$reading->id}}</td>
                                        <td>{{$reading->consumer->consumer_number}}</td>
                                        <td>{{$reading->meter->meter_number}}</td>
                                        <td>{{$reading->consumer->consumer_name}}</td>
                                        <td>{{$reading->consumer->area_code}}</td>
                                        <td>{{$reading->meter_location->description}}</td>
                                        <td>{{$reading->meter->meter_type}}</td>
                                        <td>{{$reading->obstacle_code->description}}</td>
                                        <td>{{$reading->reading}}</td>
                                        <td>{{$reading->meter->z_factor}}</td>
                                        <td>{{$reading->actual_cf}}</td>
                                        <td>{{$reading->actualConsVar}}</td>
                                        <td>{{$reading->actual_average}}</td>
                                        <td>{{$reading->billable}}</td>
                                        <td>{{$reading->issue_code->description}}</td>
                                        <!-- The Modal -->
                                      
                                        <td><a onclick="image({{$reading->id}})"><img id="{{$reading->name}}myImg" style="height: 100px; width: 100px" src="data:image/gif;base64,{{$reading->image}}"></a></td>
                                         <td align="center"><a href="viewtrend/{{$reading->meter_id}}" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> View Trend</i></a></td>



                                        @if(auth()->user()->type == 3 || auth()->user()->type == 6)
                                            @if($reading->accepted2 == 0)
                                            <td>
                                              <input class="fa text-white btn btn-smrs" type = "button" onclick = "verify({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Verify"> <br><br>
                                              <input class="fa text-white btn btn-danger " type = "button" onclick = "reject({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Reject">
                                            </td>
                                            @elseif($reading->accepted2 == 1)
                                            <td>Rejected.<br>Reason: {{$reading->reason2}}</td>
                                            @elseif($reading->accepted2 == 2)
                                            <td>Accepted <br><br><input class="fa text-white btn btn-danger" type = "button" onclick = "revoke({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Revoke"> </td>
                                            @endif
                                        @else
                                            <td>Only Supervisor Can Change This Reading</td>
                                        @endif


                                        @if(auth()->user()->type == 2 || auth()->user()->type == 5)
                                            @if($reading->accepted1 == 0)
                                                @if($reading->accepted2 == 2)
                                                <td>
                                                  <input class="fa text-white btn btn-smrs" type = "button" onclick = "verify({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Verify"> <br><br>
                                                  <input class="fa text-white btn btn-danger " type = "button" onclick = "reject({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Reject">
                                                </td>
                                                @elseif($reading->accepted2 == 1)
                                                <td>Reading Rejected By Supervisor<br>Reason: {{$reading->reason2}}</td>
                                                @else
                                                <td>Waiting for the Supervisor to Approve</td>
                                                @endif
                                            @elseif($reading->accepted1 == 1)
                                            <td>Rejected.<br>Reason: {{$reading->reason1}}</td>
                                            @elseif($reading->accepted1 == 2)
                                            <td>Accepted <br><br><input class="fa text-white btn btn-danger" type = "button" onclick = "revoke({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Revoke"></td>
                                            @endif
                                        @else
                                            <td>Only Superuser/Final Approver Can Change This Reading</td>
                                        @endif


                                    </tr>
                                    @endif
                                  <?php }?>
                                  </tbody>
                                </table> 
                             </div>

                             @else

                                @if(auth()->user()->branch_id == $branch->id)

                                    <div id="{{$branch->name}}" class="tabsubcontent" style="overflow-x: auto; overflow-y: auto;">
                                    <h3>{{$branch->name}} Meter Reading</h3>
                                    <table id="{{$branch->name}}Table" class="table table-bordered table-striped">
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
                                          <th>Supervisor</th>
                                          <th>Superuser / Final Approver</th>
                                        </tr>
                                      </thead>
                                       <tbody>
                                      <?php foreach( $readings as $reading ){?>
                                        @if($reading->branch->name == $branch->name)
                                        <tr>
                                          <td>{{$reading->id}}</td>
                                          <td>{{$reading->consumer->consumer_number}}</td>
                                          <td>{{$reading->meter->meter_number}}</td>
                                          <td>{{$reading->consumer->consumer_name}}</td>
                                          <td>{{$reading->consumer->area_code}}</td>
                                          <td>{{$reading->meter_location->description}}</td>
                                          <td>{{$reading->meter->meter_type}}</td>
                                          <td>{{$reading->obstacle_code->description}}</td>
                                          <td>{{$reading->reading}}</td>
                                          <td>{{$reading->billable}}</td>
                                          <td>{{$reading->issue_code->description}}</td>
                                          <!-- The Modal -->
                                          
                                            <td><a onclick="image({{$reading->id}})"><img id="{{$reading->name}}myImg" style="height: 100px; width: 100px" src="data:image/gif;base64,{{$reading->image}}"></a></td>
                                             <td align="center"><a href="viewtrend/{{$reading->meter_id}}" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> View Trend</i></a></td>


                                          @if(auth()->user()->type == 3 || auth()->user()->type == 6)
                                               @if($reading->accepted2 == 0)
                                                <td>
                                                  <input class="fa text-white btn btn-smrs" type = "button" onclick = "verify({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Verify"> <br><br>
                                                  <input class="fa text-white btn btn-danger " type = "button" onclick = "reject({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Reject">
                                                </td>
                                                @elseif($reading->accepted2 == 1)
                                                <td>Rejected.<br>Reason: {{$reading->reason2}}</td>
                                                @elseif($reading->accepted2 == 2)
                                                <td>Accepted <br><br><input class="fa text-white btn btn-danger" type = "button" onclick = "revoke({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Revoke"> </td>
                                                @endif
                                           @else
                                                <td>Only Supervisor Can Change This Reading</td>
                                           @endif


                                          @if(auth()->user()->type == 2 || auth()->user()->type == 5)
                                            @if($reading->accepted1 == 0)
                                                @if($reading->accepted2 == 2)
                                                <td>
                                                  <input class="fa text-white btn btn-smrs" type = "button" onclick = "verify({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Verify"> <br><br>
                                                  <input class="fa text-white btn btn-danger " type = "button" onclick = "reject({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Reject">
                                                </td>
                                                @elseif($reading->accepted2 == 1)
                                                <td>Reading Rejected By Supervisor<br>Reason: {{$reading->reason2}}</td>
                                                @else
                                                <td>Waiting for the Supervisor to Approve</td>
                                                @endif
                                            @elseif($reading->accepted1 == 1)
                                            <td>Rejected.<br>Reason: {{$reading->reason1}}</td>
                                            @elseif($reading->accepted1 == 2)
                                            <td>Accepted <br><br><input class="fa text-white btn btn-danger" type = "button" onclick = "revoke({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Revoke"></td>
                                            @endif
                                         @else
                                                <td>Only Superuser/Final Approver Can Change This Reading</td>
                                         @endif


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
                    <div id="Residential" class="tabcontent">
                      <h3>GAS Malaysia Energy Services SDN BHD</h3>
                      <div style="overflow-x: auto; overflow-y: auto;">
                        <table id="residentialTable" class="table table-bordered table-striped" style="width: 100px;">
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
                                      <th>Supervisor</th>
                                      <th>Superuser / Final Approver</th>
                                    </tr>
                                  </thead>
                                   <tbody>
                                  <?php foreach( $readingResidents as $reading ){?>
                                    @if($reading->consumer->consumer_type == 01)
                                    <tr>
                                      <td>{{$reading->id}}</td>
                                      <td>{{$reading->consumer->consumer_number}}</td>
                                      <td>{{$reading->meter->meter_number}}</td>
                                      <td>{{$reading->consumer->consumer_name}}</td>
                                      <td>{{$reading->consumer->area_code}}</td>
                                      <td>{{$reading->meter_location->description}}</td>
                                      <td>{{$reading->meter->meter_type}}</td>
                                      <td>{{$reading->obstacle_code->description}}</td>
                                      <td>{{$reading->reading}}</td>
                                      <td>{{$reading->billable}}</td>
                                      <td>{{$reading->issue_code->description}}</td>
                                      <!-- The Modal -->
                                      
                                        <td><a onclick="image({{$reading->id}})"><img id="{{$reading->name}}myImg" style="height: 100px; width: 100px" src="data:image/gif;base64,{{$reading->image}}"></a></td>
                                         <td align="center"><a href="viewtrend/{{$reading->meter_id}}" class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit text-white"> View Trend</i></a></td>

                                          @if(auth()->user()->type == 3)
                                               @if($reading->accepted2 == 0)
                                                <td>
                                                  <input class="fa text-white btn btn-smrs" type = "button" onclick = "verify({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Verify"> <br><br>
                                                  <input class="fa text-white btn btn-danger " type = "button" onclick = "reject({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Reject">
                                                </td>
                                                @elseif($reading->accepted2 == 1)
                                                <td>Rejected.<br>Reason: {{$reading->reason2}}</td>
                                                @elseif($reading->accepted2 == 2)
                                                <td>Accepted <br><br><input class="fa text-white btn btn-danger" type = "button" onclick = "revoke({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Revoke"> </td>
                                                @endif
                                           @else
                                                <td>Only Supervisor Can Change This Reading</td>
                                           @endif


                                          @if(auth()->user()->type == 2)
                                            @if($reading->accepted1 == 0)
                                                @if($reading->accepted2 == 2)
                                                <td>
                                                  <input class="fa text-white btn btn-smrs" type = "button" onclick = "verify({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Verify"> <br><br>
                                                  <input class="fa text-white btn btn-danger " type = "button" onclick = "reject({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Reject">
                                                </td>
                                                @elseif($reading->accepted2 == 1)
                                                <td>Reading Rejected By Supervisor<br>Reason: {{$reading->reason2}}</td>
                                                @else
                                                <td>Waiting for the Supervisor to Approve</td>
                                                @endif
                                            @elseif($reading->accepted1 == 1)
                                            <td>Rejected.<br>Reason: {{$reading->reason1}}</td>
                                            @elseif($reading->accepted1 == 2)
                                            <td>Accepted <br><br><input class="fa text-white btn btn-danger" type = "button" onclick = "revoke({{$reading->id}},{{auth()->user()->type}},{{auth()->user()->id}})" value = "Revoke"></td>
                                            @endif
                                         @else
                                                <td>Only Superuser/Final Approver Can Change This Reading</td>
                                         @endif

                                   
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
    <!-- <script src="vendor/jquery-easing/jquery.easing.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <!-- <script src="js/sb-admin-2.min.js"></script> -->

    <!-- Page level plugins -->
    <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <script type="text/javascript">
      
        var branches = {!! json_encode($branches->toArray()) !!};

        var readings = {!! json_encode($readings->toArray()) !!};

        var noti = {!! json_encode($noti->toArray()) !!};


// console.log(branches);

      for (var i = 0; i < branches.length; i++) {
        // var branch = branches[i];
// console.log(branches[i]['name']+'Table');
        $('#'+branches[i]['name']+'Table').DataTable({
          aaSorting : [[0, 'desc']],
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
        $('#'+readings[i]['name']+'Table').DataTable({
          aaSorting : [[0, 'desc']],
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
      aaSorting : [[0, 'desc']],
      "dom": "Bfrtip",
      "bPaginate": true,
      "bLengthChange": false,
      "bFilter": true,
      "bSort": true,
      "bInfo": true,
      "bAutoWidth": true,
      "iDisplayLength": 10,
    });

     for (var i = 0; i < noti.length; i++) {



      for (var j = 0; j < branches.length; j++) {
      


        if (branches[j].id == noti[i].branch_id) {
          var alert = document.getElementsByClassName("alert"+(branches[j].id));
          // console.log(alert);
          if (alert[0]) {
            alert[0].style.backgroundColor = "red";
          }
        }




      }

// console.log(alert[0]);

    //   // if ({{$branch->id}} == noti[i].branch_id) {
    //       document.getElementsByName({{$branch->id}}).style.backgroundColor="transparent";
    //   // }
    //   // $('input[name$="1"]').css("backgroundColor", "red");

    //             // console.log(noti[i].meter_number);
    //     // $('#'+noti[i].meter_number).click(function() {
    //     //         // alert('tet');
    //     //     residentialTable.search(noti[i].meter_number).draw();
    //     // });
     }

      function verify(readingId,userType,userId) {
        Swal.fire({
            text: "Are you sure you want to Verify?",
            icon: "info",
            showDenyButton: true,
            confirmButtonText: `Yes, Verify it!`,
            denyButtonText: `No, Cancel it`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
               window.location.href = "/home/verify/" + readingId + "/" + userType + "/" + userId;
            } else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }
          })
          // .then(function(value) {
          //   if(value == true){
          //     window.location.href = "/home/verify/" + readingId + "/" + userType + "/" + userId;
          //   }
          // });
      }

      function revoke(readingId,userType,userId) {
        Swal.fire({
            text: "Are you sure you want to Revoke?",
            icon: "info",
            showDenyButton: true,
            confirmButtonText: `Yes, Revoke it!`,
            denyButtonText: `No, Cancel it`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
               window.location.href = "/home/revoke/" + readingId + "/" + userType + "/" + userId;
            } else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }
          })
          // .then(function(value) {
          //   if(value == true){
          //     window.location.href = "/home/revoke/" + readingId + "/" + userType + "/" + userId;
          //   }
          // });
      }


      function image(readingId) {
        fetch('/home/image/'+readingId)
        .then(results => {
          return results.json();
        })
        .then(json => {
          const movie = json;
          if (!movie) {
            return Swal.fire("No movie was found!");
          }
          const imageURL = movie;
          Swal.fire({
            imageUrl: "data:image/gif;base64,"+imageURL,
          });
        })
        .catch(err => {
          if (err) {
            Swal.fire("Oh no!", "The AJAX request failed!", "error");
          } else {
            Swal.stopLoading();
            Swal.close();
          }
        });
      }

    function reject(readingId,userType,userId) {
        Swal.fire({
          text: 'Please State The Reason',
          input: 'select',
          html: '<textarea id="swal-input1" class="swal2-input" placeholder="Insert text"></textarea>',
          inputOptions:  @json($reject_code),
          // button: {
          //   text: "Send",
          //   closeModal: false,
          // },
          // dangerMode: true
          inputPlaceholder: 'Select a Reason',
          showCancelButton: true,
          inputValidator: function (value) {
                return new Promise(function (resolve, reject) {
                  if (value != '') {
                    var freetext = document.getElementById('swal-input1').value;
                    window.location.href = "/home/reject/" + readingId + "/" + userType + "/" + userId + "/" + freetext + "/" + value;
                    // console.log(value);
                    // console.log(freetext);
                    resolve();
                  }else {
                     Swal.fire('You need to select one selection', '', 'info')
                      // reject('You need to select one tag')
                  }
                })
            }
          })
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
