@extends('layouts.sidebar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4>Route > Route List</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>All Route</h3>
                <div class="row">
                    <div class="card card-body">
                        <div style="float: left">
                            <div id="buttonExport" style="float: left"></div><br><br>
                            <table id="allRoute" class="table table-bordered table-striped">
                                <thead class="table-smrs">
                                    <tr class="column-filtering">
                                        <th>Route ID</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Sub</th>
                                        <th>Branch Code</th>
                                        <th>Assigned Handheld</th>
                                        <th>Address</th>
                                        <th>Total Account</th>
                                        {{-- <th>Assinged Handheld</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($handheldRoutes as $handheldRoute)
                                        <tr>
                                            <td>{{ $handheldRoute->route->route }}</td>
                                            <td>{{ $handheldRoute->route->comment }}</td>
                                            <td>
                                                @if ($handheldRoute->route->status == 1)
                                                    <span class="badge badge-primary">Active</span>
                                                @else
                                                    <span class="badge badge-primary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $handheldRoute->route->sub }}</td>
                                            <td>{{ $handheldRoute->route->branch->name }}({{ $handheldRoute->route->branch->code }})
                                            </td>
                                            <td>{{ $handheldRoute->handheld->label }}
                                                ({{ $handheldRoute->handheld->uuid }})</td>
                                            <td>{{ $handheldRoute->route->address }}</td>
                                            <td>{{ count($handheldRoute->route->Consumers) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <br>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script type="text/javascript">
            $(document).ready(function() {
                var allRoute = $('#allRoute').DataTable({
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                    "dom": "Blfrtip",
                    buttons: {
                        buttons: [
                            {
                                extend : 'pdf',
                                orientation: 'landscape',
                                className: 'btn-smrs',
                                title: function(){
                                    return 'List of Route'
                                }
                            },
                            {
                                extend : 'excel',
                                className: 'btn-smrs',
                                filename: function () {
                                    return 'List of Route';
                                }
                            }
                        ],
                        dom: {
                            button: {
                                className: 'btn'
                            },
                        },
                    },
                })
            .buttons()
                    .container()
                    .appendTo("#buttonExport");
            });
        </script>
    @endsection
