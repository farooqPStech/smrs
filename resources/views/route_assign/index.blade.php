@extends('layouts.sidebar')

@section('content')

    <body>
        <div class="row">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <table class="table table-striped table-light">
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Supervisor Name </th>
                        <th>Supervisor Assigned Branch</th>
                        <th>Assigned Routes </th>
                        <th>Action</th>
                    </tr>

                @foreach ($branches as $branch)

                 @if(Auth::user()->branch_id == $branch->branchid)
                        <tr>
                            <td>{{ $branch->userid }}</td>
                            <td>{{ $branch->username }}</td>
                            <td>{{ $branch->fullname }}</td>
                            <td>{{ $branch->name }}</td>

                            <td>
                                @foreach ($routes as $route)
                                    @if ($route->branch_code == $branch->code )
                                        @if($branch->userid == $route->user_id)
                                            {{ $route->route }}
                                            <!--<a href="route_assign/{{ $route->route }}">
                                                <span class="badge badge-pill badge-danger">
                                                    <i class="fas fa-times" style="color:white"></i>
                                                </span>
                                            </a>-->
                                            <button onclick="routeUnassign({{ $route->id }})"
                                                    class="badge badge-pill badge-danger">
                                                <i class="fas fa-times" style="color:white"></i>
                                            </button>
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <form action="{{ route('assignRouteUpdate') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="userid" value="{{ $branch->userid }}">
                                    <select name="route" class="selectpicker" data-live-search="true" required>
                                        <option value="">Select...</option>
                                        @foreach ($routes as $route)
                                            @if ($route->branch_code == $branch->code )
                                                @if ($route->user_id==NULL)
                                                    <option value="{{ $route->id }}">
                                                        {{ $route->route }}
                                                        @if ($route->sub)
                                                            ({{ $route->sub }})
                                                        @endif
                                                    </option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                    <span>
                                        <button type="submit" class="btn btn-primary">Assign Route</button>
                                    </span>
                                </form>
                            </td>
                        </tr>
                 @endif
                @endforeach
                </table>
            </div>
        </div>
    </body>
@endsection
@section('script')
    <script type="text/javascript">

        function routeUnassign(id) {
            swal({
                text: "Are you sure you want to remove this assignation?",
                icon: "info",
                buttons: ['NO', 'YES'],
                dangerMode: true
            })
                .then(function(value) {
                    if (value) {
                        window.location.href = "/route_assign/" + id;
                    }
                });
        }
    </script>
@endsection
