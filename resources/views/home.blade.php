<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bkolpo Accounting | Home Page</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('Accounts/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('Accounts/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('Accounts/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .small-box>.inner {
            padding: 30px 10px;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="container">
        <section class="content p-4 mt-5">
            <div class="card card-outline card-primary shadow-lg">
                <div class="card-header text-center">
                    <a href="#" class="h1"><b>ERP</b></a>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <!-- Accounts -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <p class="m-0">Accounts</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- HR Management -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url('/hrm/dashboard') }}" class="text-white">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <p class="m-0">HR Management</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Attendanced & Payroll -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <p class="m-0">Attendanced & Payroll</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Inventory -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url('/inventory/dashboard') }}" class="text-white">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p class="m-0">Inventory</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Ecommerce -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <p class="m-0">Ecommerce</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Process -->
                        <div class="col-xl-4 col-md-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-secondary">
                                    <div class="inner">
                                        <p class="m-0">Process</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light px-3 py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-muted" style="font-size: 16px;">
                        <div class="mb-2 mb-md-0 text-center text-md-left">
                            <i class="far fa-copyright"></i>
                            {{ date('Y') }} <strong>Bkolpo Technologies</strong>. All rights reserved.
                        </div>
                        <div class="text-center text-md-right">
                            Developed by <strong>Bkolpo Technologies</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!-- jQuery -->
    <script src="{{ asset('Accounts/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('Accounts/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('Accounts/dist/js/adminlte.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // JavaScript to toggle password visibility
        document.getElementById('toggle-password').addEventListener('click', function() {
            var passwordField = document.getElementById('password');
            var passwordIcon = this;

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        });
    </script>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif

    @if (session()->get('warning'))
        <script>
            toastr.warning('{{ session()->get('warning') }}');
        </script>
    @endif

    @if (session()->get('success'))
        <script>
            toastr.success('{{ session()->get('success') }}');
        </script>
    @endif

    @if (session()->get('error'))
        <script>
            toastr.error('{{ session()->get('error') }}');
        </script>
    @endif
</body>

</html>
