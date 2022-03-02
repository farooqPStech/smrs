@extends('layouts.sidebar')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h4>Message Broadcast</h4>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <h3>Message List</h3>
                <div class="row">
                    <div class="card card-body">
                        <div style="float: right">
                            <a href="/broadcast/addbroadcast" class="btn btn-smrs" style="width: 20%; float: right">Add
                                New</a>
                        </div><br>
                        <table id="allRoute" class="table table-bordered table-striped">
                            <thead class="table-smrs">
                                <tr>
                                    {{-- <th>#</th> --}}
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Deliver To</th>
                                    <th>Created_at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if( !empty($broadcasts) ){ ?>
                                <?php foreach( $broadcasts as $broadcast ){?>
                                <tr>
                                    {{-- <td>{{ $broadcast->id }}</td> --}}
                                    <td>{{ $broadcast->title }}</td>
                                    <td>{{ $broadcast->content }}</td>
                                    <td>{{ $broadcast->send_to }}</td>
                                    <td>{{ $broadcast->created_at }}</td>
                                    <td align="center">
                                        <a href="/broadcast/edit/{{ $broadcast->id }}" style="width: 70%"
                                            class="btn btn-smrs" data-toggle="tooltip" data-placement="top"
                                            title="Edit">Edit
                                        </a><br>
                                        <a href="/broadcast/sendbroadcast/{{ $broadcast->id }}"
                                            style="margin-top: 3%; width: 70%" class="btn btn-warning" data-toggle="tooltip"
                                            data-placement="top" title="Send Broadcast">
                                            Send Broadcast
                                        </a><br>
                                        <a href="/broadcast/remove/{{ $broadcast->id }}" class="btn btn-danger"
                                            style="margin-top: 3%; width: 70%;" data-toggle="tooltip" data-placement="top"
                                            title="Remove">
                                            Remove
                                        </a>
                                    </td>
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


@endsection

@section('script')
    <script type="text/javascript">
        var allRoute = $('#allRoute').DataTable({
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
            "sScrollX": "100%",
            "sScrollXInner": "100%",
            "bScrollCollapse": true
        });
    </script>
@endsection
