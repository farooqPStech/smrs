

@extends('layouts.sidebar')

@section('content')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.52/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  border: 1px solid #888;
  width: 80%;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}
</style>
<body>
<div class="row">
    <div class="col-md-12" >
        <h4>Route > Adjust Route</h4>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

                <div class="row">
                  <div class="card card-body">
                    <div class="tab">
                      <button class="tablinks" onclick="openType(event, 'CombineRoute')" id="defaultOpen">Combine Route</button>
                      <button class="tablinks" onclick="openType(event, 'SplitRoute')">Split Route</button>
                      <button class="tablinks" onclick="openType(event, 'ChangeHandheld')">Change HandHeld</button>
                    </div>

                        <!-- <a id="myBtn" type="submit" class="btn btn-primary btn-user btn-block">Search Address</a> -->

                        <!-- <a id="myBtn2" type="submit" class="btn btn-primary btn-user btn-block">Change Sender</a> -->

                        <!-- The Modal -->
                        <div id="myModal2" class="modal">

                          <!-- Modal content -->
                          <div class="modal-content">
                            <div class="modal-header">
                              <span class="close2">&times;</span>
                              <h2>Route Address List</h2>
                            </div>
                            <div class="modal-body" style="height: auto">
                                <br><br>
                              <table id="splitRight" class="table table-bordered table-striped">
                                <thead class="table-smrs">
                                  <tr>
                                    <th>#</th>
                                    <th>Route Code</th>
                                    <th>Sub Route Code</th>
                                  </tr>
                                </thead>
                                <br>
                              {{-- {{ $items->links() }} --}} 
                              </table> 
                            </div>
                            <div class="modal-footer">
                            <a  type="submit" class="btn btn-primary btn-user btn-block addressBookSender">SELECT</a>
                              <h3></h3>
                            </div>
                          </div>

                        </div>



                    <div id="CombineRoute" class="tabcontent">
                      <h3>Combine Route</h3>
                      <div class="row">
                        <div class="card card-body">
                          <div style="float: left">
                          </div><br>
                          <div style="overflow-x: auto; overflow-y: auto;">
                            <table id="combineRoute" class="table table-bordered table-striped">
                              <thead class="table-smrs">
                                <tr>
                                  <th>#</th>
                                  <th>Route Code</th>
                                  <th>Sub Route Code</th>
                                  <th>Comment</th>
                                </tr>
                              </thead>
                              <br>
                            {{-- {{ $items->links() }} --}} 
                            </table> 
                          </div>
                          <br>
                          <div class="col-md-12">
                            <a id="combine" style="float: left;" class="btn btn-smrs col-md-4">Combine</a> 
                            <a id="uncombine" style="float: right;" class="btn btn-warning col-md-4">Uncombine</a> 
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="SplitRoute" class="tabcontent">
                      <h3>Split Route</h3>
                      <div class="row">
                        <br>
                          <div class="col-md-12">
                            <h6>Route ID:</h6>
                              <select id='routeButton' name='routeButton' class="form-control col-md-4">
                                <option value='0'>Select Route ID</option>
                                <?php if( !empty($notRoutes) ){ ?>
                                    <?php foreach( $notRoutes as $route ){?>
                                      <?php if( empty($route->sub) ){ ?>
                                        <?php if( empty($route->combine) ){ ?>
                                         <option value='{{ $route->id }}'>{{ $route->route }}</option>
                                        <?php }?>
                                      <?php }?>
                                    <?php }?>
                                <?php }?>
                              </select>
                          </div>
                          <div class="col-md-5">
                            <br>
                              <h6>Available Route:</h6>
                              <div style="overflow-x: auto; overflow-y: auto;">
                                <table style="font-size: 14px; width: 100%" id="routeID" name="routeID" class="table table-bordered table-striped">
                                  <thead class="table-smrs">
                                    <tr>
                                       <th>#</th>
                                      <th>Consumer Name</th>
                                      <th>Consumer Number</th>
                                    </tr>
                                  </thead>
                                   {{-- {{ $items->links() }} --}} 
                                </table> 
                              </div><br>
                              <div class="row">
                                <div class="col-md-6">
                                  <label>Start Seq</label>
                                  <input id="startSeq" type="number" class="form-control" name="startSeq" value="">
                                </div>
                                <div class="col-md-6">
                                  <label>End Seq</label>
                                  <input id="endSeq" type="number" class="form-control" name="endSeq" value="">
                                </div>
                               
                              </div>
                            </div>
                            <div class="col-md-2" style="margin: auto">
                              <div class="row">
                                <a id="left" style="margin: auto; font-size: 30px" class="btn btn-smrs col-md-4"><b><</b></a> 
                              </div>
                              <div class="row">
                                <a id="right" style="margin: auto; font-size: 30px" class="btn btn-smrs col-md-4"><b>></b></a> 
                              </div>
                            </div>
                            <div class="col-md-5">
                              <h6>Split Route(s):</h6>
                              <div style="overflow-x: auto; overflow-y: auto;">
                                <table style="font-size: 14px; width: 100%" id="splitRoute" class="table table-bordered table-striped">
                                  <thead class="table-smrs">
                                    <tr>
                                       <th>#</th>
                                      <th>Route</th>
                                      <th>Sub</th>
                                    </tr>
                                  </thead>
                                  <br>
                                {{-- {{ $items->links() }} --}} 
                                </table> 
                              </div>
                              <br>
                            </div>
                      </div>
                    </div>
                    <div id="ChangeHandheld" class="tabcontent">
                      <h3>Change HandHeld</h3>
                      <div class="row">
                        <div class="card card-body">
                          <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-5">
                                <label>Route</label>
                                 <select class="form-control" name="route[]" id="route">
                                      <option value="">Select...</option>
                                    <?php foreach( $routes as $route ){?>
                                      <option value="{{$route->id}}">{{$route->route}}</option>
                                    <?php }?>
                                  </select>
                              </div>
                              <div class="col-md-5">
                                <label>Handheld</label>
                                <select class="form-control" name="handheld[]" id="handheld">
                                      <option value="">Select...</option>
                                    <?php foreach( $handhelds as $handheld ){?>
                                      <option value="{{$handheld->id}}">{{$handheld->uuid}}</option>
                                    <?php }?>
                                  </select>
                                <!-- <input id="handheld" type="text" class="form-control" name="handheld" value=""> -->
                              </div>
                                <a id="change" class="btn btn-smrs col" style="margin-top: auto">Confirm</a> 
                            </div>
                          </div>
                          <br>
                          <br>
                          <div style="overflow-x: auto; overflow-y: auto;">
                            <table id="changeHandheld" class="table table-bordered table-striped">
                              <thead class="table-smrs">
                                <tr>
                                  <th>#</th>
                                  <th>Handheld UUID</th>
                                  <th>Branch Code</th>
                                  <th>Route</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if( !empty($handheldRoutes) ){ ?>
                                  <?php foreach( $handheldRoutes as $handheldRoute ){?>
                                    <tr>
                                      <td>{{$handheldRoute->id}}</td>
                                      <td>{{$handheldRoute->handheld}}</td>
                                      <td>{{$handheldRoute->branch_code}}</td>
                                      <td>{{$handheldRoute->route}}</td>
                                    </tr>
                                    <?php }?>
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
    </div>
</div>
<script type="text/javascript">


    var combineRoute = $('#combineRoute').DataTable({
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
        url:  "/route/combineroutetable",
        type: 'GET',
        },
        columns: [
                { data: 'id', name: 'id'},
                { data: 'route', name: 'route' },
                { data: 'sub', name: 'sub' },
                { data: 'comment', name: 'comment', orderable: false, searchable: false},
            ],
        order: [[0, 'desc']]
    });
  
  $('#routeButton').change(function(){
    reload()
    
    $('#routeID tbody').on( 'click', 'tr', function () {
    $(this).toggleClass('selected');
    } );

    $('#splitRoute tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );

  });

    var changeHandheld = $('#changeHandheld').DataTable({
        aaSorting : [[0, 'desc']],
        "dom": "Bfrtip",
         buttons: {
            buttons: [
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
    });

    $('#combineRoute tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );


    $('#combine').click( function () {

      if(combineRoute.cells('.selected',0).data().toArray() == '' )
      {
                swal({
                icon: "warning",
                text: 'Please Highlight Row!',
                });
        // alert("Please Highlight Row");
      }
      else
      {
        var routes = combineRoute.cells('.selected',0).data().toArray();   

        window.location.href = "/route/assigncombineroute/" + routes;
      }
    });

    $('#uncombine').click( function () {

      if(combineRoute.cells('.selected',0).data().toArray() == '' )
      {
                swal({
                icon: "warning",
                text: 'Please Highlight Row!',
                });
        // alert("Please Highlight Row");
      }
      else
      {
        var routes = combineRoute.cells('.selected',0).data().toArray();   

        window.location.href = "/route/unassigncombineroute/" + routes;
      }
    });

      $('#change').click( function () {

      var route = document.getElementById('route').value;   
      var handheld = document.getElementById('handheld').value;   

      if (route == '' || handheld == '') {
                swal({
                icon: "warning",
                text: 'Please Select Route and Handheld!',
                });
        // alert('Please Select Route and Handheld');
      }
      else
      {
        window.location.href = "/route/changehandheld/" + route + "/" + handheld;
      }

    } );

    $('#left').click( function () {
    // var id = $('#routeButton').val();
     var splitRoute = $('#splitRoute').DataTable();
      if(splitRoute.cells('.selected',0).data().toArray() == '' )
      {
                swal({
                icon: "warning",
                text: 'Please Highlight Row!',
                });
        // alert("Please Highlight Row");
      }
      else
      {
        var routes = splitRoute.cells('.selected',0).data().toArray();   
         // alert(routes);
        jQuery.ajax({
        type: "GET",
        url: 'updatesplitrouteselected/'+routes,
        dataType: 'json',
        data: {functionname: 'add', arguments: [1, 2]},

        success: function (obj, textstatus) {
                      if( !(obj.error) ) {
                          // yourVariable = obj.result;
                          reload();

                      }
                      else {
                          console.log(obj.error);
                      }
                }
        });
      }

    } );

    $('#right').click( function () {
     var id = $('#routeButton').val();
     var startSeq = $('#startSeq').val();
     var endSeq = $('#endSeq').val();
     var routeID = $('#routeID').DataTable();
     if ((startSeq == null || startSeq == '') && (endSeq == null || endSeq == '')) {

        if(routeID.cells('.selected',0).data().toArray() == '' )
        {
                swal({
                icon: "warning",
                text: 'Please Highlight Row!',
                });
          // alert("Please Highlight Row");
        }
        else
        {
          var routes = routeID.cells('.selected',0).data().toArray();   
          // alert(routes);
          jQuery.ajax({
          type: "GET",
          url: 'updatesubrouteselected/'+routes+'/'+id,
          dataType: 'json',
          data: {functionname: 'add', arguments: [1, 2]},

          success: function (obj, textstatus) {
                        if( !(obj.error) ) {
                            // yourVariable = obj.result;
                            reload();

                        }
                        else {
                            console.log(obj.error);
                        }
                  }
          });
          // window.location.href = "/route/assigncombineroute/" + routes;
        }

     }
     else{

      if (startSeq == null || startSeq == '' || endSeq == null || endSeq == '') {

                swal({
                icon: "warning",
                text: 'Please put start and end Sequence!',
                });
        // alert('Please put start and end Sequence');
      }
      else{

       if(routeID.cells('.selected',0).data().toArray() != '' )
        {
                swal({
                icon: "warning",
                text: 'Please Empty the sequence number or unselect the row!',
                });
          // alert("Please Empty the sequence number or unselect the row");
        }
        else
        {
          if (startSeq>endSeq) {
                swal({
                icon: "warning",
                text: 'Start sequence must be smaller than end sequence!',
                });
            // alert('Start sequence must be smaller than end sequence');
          }
          else
          {
            jQuery.ajax({
            type: "GET",
            url: 'updatesubroute/'+id+'/'+startSeq+'/'+endSeq,
            dataType: 'json',
            data: {functionname: 'add', arguments: [1, 2]},

            success: function (obj, textstatus) {
                          if( !(obj.error) ) {
                              // yourVariable = obj.result;
                              reload();

                          }
                          else {
                              console.log(obj.error);
                          }
                    }
            });
          }

        }



      }


      // var id = $(this).val();

   
     }
       // window.location.href = "/route/changehandheld/" + route + "/" + handheld;

    } );


    function reload()
    {
     var id = $('#routeButton').val();
       var routeID = $('#routeID').DataTable({
        destroy: true,
        aaSorting : [[0, 'desc']],
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
        // processing: true,
        serverSide: true,
        ajax: {
        url: 'getroute/'+id,
           type: 'GET',
           dataType: 'json',
        },
        columns: [
                { data: 'id', name: 'id'},
                { data: 'consumer_name', name: 'consumer_name',},
                { data: 'consumer_number', name: 'consumer_number' },
            ],
        order: [[0, 'asc']]
    });

    var splitRoute = $('#splitRoute').DataTable({
        destroy: true,
        aaSorting : [[0, 'desc']],
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
        // processing: true,
        serverSide: true,
        ajax: {
        url: 'getsubroute/'+id,
           type: 'GET',
           dataType: 'json',
        },
        columns: [
                { data: 'id', name: 'id'},
                { data: 'route', name: 'route',},
                { data: 'sub', name: 'sub' },
            ],
        order: [[0, 'asc']]
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
    document.getElementById("defaultOpen").click();


// $('#routeID').change(function(){
//  var id = $(this).val();
//   // $('#sel_emp').find('option').not(':first').remove();
//         $.ajax({
//            url: 'getroute/'+id,
//            type: 'get',
//            dataType: 'json',
//            success: function(response){
//                   $('#routeID tbody').html(response['data']); 
//            }
//         });
//         });

</script>
</body>
@endsection
