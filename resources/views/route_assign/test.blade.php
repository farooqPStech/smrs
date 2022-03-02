@extends('layouts.sidebar')

@section('content')

    <body>
        <div class="row">
            <div class="col-md-12">
                <form action="/assignRouteUpdate" method="post">

                    @csrf
                    @foreach ($routes as $route)
                        <input type="hidden" name="user" value="{{ $user }}">

                        @if ($route->branch_code == $branch)
                            <input type="checkbox" name="routes[]" value="{{ $route->route }}" id="color_red" />
                            <label for="color_red">{{ $route->route }}</label>
                        @endif

                    @endforeach
                    <input type="submit" value="Submit">
                </form>
            </div>
        </div>
    </body>
@endsection
