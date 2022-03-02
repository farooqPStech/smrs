@extends('layouts.sidebar')

@section('content')

    <body>
        <div class="row">
            <div class="col-md-12">
                <div class="card-body ">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h4>Dashboard</h4>
                    <!-- <button class="btn btn-smrs">
                            <i class="">Download From JDE</i>
                        </button>
                        <form action="/home/reader" method="post" enctype="multipart/form-data">
                          @csrf
                          Select image to upload:
                          <input type="file" name="fileToUpload" id="fileToUpload">
                          <button type="submit" name="submit"> REad From JDE </button>
                        </form> -->
                    {{-- <div class="row">
                        <div class="col-xl-1">
                            <form action="/home/reader" method="post" enctype="multipart/form-data">
                                <a href="/home/uploadtojde" class="btn btn-smrs">
                                    <i class="">Upload to JDE</i>
                                </a>
                            </form>
                        </div>
                        <!--  <button class="btn btn-smrs">
                                <i class="">Download From UBIS</i>
                            </button> -->
                        <div class="col-xl-1">
                            <form action="/home/reader" method="post" enctype="multipart/form-data">
                                <a class="btn btn-smrs">
                                    <i class="">Upload to UBIS</i>
                                </a>
                            </form>
                        </div>
                    </div> --}}
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Number of Meter</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMeter }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tachometer-alt fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Read Meter ({{ $currentMonth }})</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReading }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tachometer-alt fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Unread Meter ({{ $currentMonth }})</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unread }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tachometer-alt fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Faulty Meter</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $faulty }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tachometer-alt fa-2x text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    {{-- Live Wire Home Component --}}
                    @livewire('branch')
                    {{-- Live Wire Home Component Ends --}}
                </div>
            </div>
        </div>
    </body>
@endsection
