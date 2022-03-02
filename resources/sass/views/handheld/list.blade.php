@extends('layouts.sidebar')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h4>Handheld</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>Handheld List</h3>
                <div class="row">
                    <div class="card card-body">
                        <div style="float: left">
                        </div><br>
                        <div style="float: right">
                            <a href="/handheld/addhandheld" class="btn btn-smrs" style="width: 20%; float: right">Add
                                New</a>
                        </div><br>
                        <table id="allRoute" class="table table-bordered table-striped">
                            <thead class="table-smrs">
                                <tr>
                                    <th>#</th>
                                    <th>UUID</th>
                                    <th>Label Tag</th>
                                    <th>Type</th>
                                    <th>Branch ID</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($handhelds))
                                    @foreach ($handhelds as $handheld)
                                        <tr>
                                            <td>{{ $handheld->id }}</td>
                                            <td>{{ $handheld->uuid }}</td>
                                            <td>{{ $handheld->label }}</td>
                                            <td>{{ $handheld->type }}</td>
                                            <td>{{ $handheld->branch->code }} ({{ $handheld->branch->name }})</td>
                                            @if ($handheld->status == 1)
                                                <td><span class="badge badge-success">Active</span></td>
                                            @else
                                                <td><span class="badge badge-danger">Inactive</span></td>
                                            @endif
                                            <td>{{ $handheld->created_at->format('d-M-Y') }}</td>
                                            <td align="center"><a href="/handheld/edithandheld/{{ $handheld->id }}"
                                                    style="width: 90%" class="btn btn-smrs" data-toggle="tooltip"
                                                    data-placement="top">Edit</a><br>
                                                <a onclick="remove({{ $handheld->id }})" class="btn btn-danger"
                                                    style="margin-top: 3%; width: 90%;" data-toggle="tooltip"
                                                    data-placement="top">Remove</a>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var allRoute = $('#allRoute').DataTable({
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

        function remove(handheldId) {
            swal({
                    text: "Are you sure you want to delete?",
                    icon: "warning",
                    buttons: ['NO', 'YES'],
                    dangerMode: true
                })
                .then(function(value) {
                    if (value == true) {
                        window.location.href = "/handheld/removehandheld/" + handheldId;
                    }
                });
        }
    </script>
@endsection
