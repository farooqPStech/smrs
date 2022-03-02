<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Spot Meter Reading System</title>
        <link rel="icon" href="{{ asset('img/brand/icon.png') }}" type="image/png">

        <!-- Custom fonts for this template-->
        <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        @yield('css')

    </head>

    <body id="page-top" style="background-image: url('img/brand/SMRS_Background.png'); background-attachment:fixed; background-size: cover;">

        <!-- Page Wrapper -->

            <!-- Content Wrapper -->

                <!-- Main Content -->

                    <!-- Topbar -->
                    
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->              

                        <!-- Outer Row -->

                                            <!-- <div class="card-header">{{ __('Login') }}</div> -->

                                            <div class="card-body">
                                                <!-- Nested Row within Card Body -->
                                                <div class="row">
                                                    @yield('content')
                                                </div>
                                            </div>
                    <!-- /.container-fluid -->

                <!-- End of Main Content -->

                <!-- Footer -->
                <!-- End of Footer -->

            <!-- End of Content Wrapper -->

        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

        @yield('scripts')

    </body>

</html>