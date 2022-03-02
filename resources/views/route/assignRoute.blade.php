@extends('layouts.sidebar')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h4>Route > Assign Route</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <h3>Assign Route to Handheld</h3>
                        <div class="card card-body">
                            <div style="float: left">
                            </div><br>
                            <!--  <select class="js-data-example-ajax"></select> -->
                            <table id="data-table" class="table table-bordered table-striped">
                                <thead class="table-smrs">
                                <tr>
                                    <th>Handheld Tag</th>
                                    <th>Branch Code</th>
                                    <th>Assigned Routes</th>
                                    <th>Route</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($handhelds))
                                    @foreach ($handhelds as $handheld)
                                        <tr>

                                            <td>
                                                {{ $handheld->label }} ({{ $handheld->uuid }})
                                            </td>
                                            <td>
                                                {{ $handheld->branch->name }} ({{ $handheld->branch->code }})
                                            </td>
                                            <td>
                                                @if (!empty($handheldRoutes))
                                                    @foreach ($handheldRoutes as $handheldRoute)
                                                        @if ($handheld->id == $handheldRoute->handheld_id)
                                                            {{ $handheldRoute->route->route }}
                                                                <button onclick="unassignSingleRoute({{ $handheldRoute->id }})" class="badge badge-pill badge-danger">
                                                                    <i class="fas fa-times" style="color:white"></i>
                                                                </button>
                                                            <!--<a href="unassignSingleRoute///$handheldRoute->id "
                                                                 class="badge badge-pill badge-danger">
                                                                    <i class="fas fa-times" style="color:white"></i>
                                                            </a>-->
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('assignSingleRoute') }}" method="POST">
                                                    @csrf
                                                    <div class="input-group">
                                                        <input type="hidden" value="{{ $handheld->id }}" name="id">
                                                            <select name="route" class="selectpicker" data-live-search="true" required>
                                                                <option value="">Select...</option>
                                                                @foreach ($routes as $route)
                                                                    @if ($handheld->branch->code == $route->branch_code)
                                                                        <option value="{{ $route->id }}">
                                                                            {{ $route->route }}
                                                                            @if ($route->sub)
                                                                                ({{ $route->sub }})
                                                                            @endif
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        <span>
                                                            <button type="submit" class="btn btn-primary">Assign Route</button>
                                                        </span>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <br>
                            <div class="col-md-12">
                                {{-- <a style="float: left" id="button" class="btn btn-smrs col-md-4">Assign</a> --}}
                                <a style="float: right" id="buttonAssignAll" class="btn btn-success">Restore
                                    Default Setting</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var table = $('#allRoute').DataTable();

        function unassignSingleRoute(id) {
            swal({
                text: "Are you sure you want to remove this assignation?",
                icon: "info",
                buttons: ['NO', 'YES'],
                dangerMode: true
            })
                .then(function(value) {
                    if (value == true) {
                        window.location.href = "/route/unassignSingleRoute/" + id;
                    }
                });
        }

        $('#button').click(function() {

            var table = $("#allRoute tbody");
            var routes = [];

            table.find('tr').each(function(i) {
                var $tds = $(this).find('td'),
                    Routeid = $tds.eq(3).find('input,select').val();
                routes.push(Routeid);
            });

            var table1 = $('#allRoute').DataTable();
            var ids = table1.cells('.id', 0).data().toArray();

            window.location.href = "/route/newassignroute/" + routes + "/" + ids;
        });

        $('#assignedRoute tbody').on('click', 'tr', function() {
            $(this).toggleClass('selected');
        });

        $('#button2').click(function() {

            if (assignedRoute.cells('.selected', 0).data().toArray() == '') {
                swal("Oh no!", "Please highlight row on table on the right!", "error");
            } else {
                var array = assignedRoute.cells('.selected', 0).data().toArray();
                var data = [];
                $.each(array, function(idx2, val2) {
                    var str = val2;
                    data.push(str);
                });

                // alert(data);
                window.location.href = "/route/unassign/" + data;
            }

        });

        $('#buttonAssignAll').click(function() {

            swal({
                text: "Are you sure you want to Assign Previous Setting?",
                icon: "info",
                buttons: ['NO', 'YES'],
                dangerMode: true
            })
                .then(function(value) {
                    if (value == true) {
                        window.location.href = "/route/assignrouteprev";
                    }
                });

        });
    </script>
@endsection
