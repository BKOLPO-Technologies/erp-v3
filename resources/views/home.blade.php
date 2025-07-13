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
</head>

<body class="hold-transition login-page">
    <div class="container-fluid">
        <section class="content p-4">
            <!-- Dashboard Card -->
            <div class="card card-outline card-primary shadow-lg">
                <div class="card-header text-center">
                    <a href="#" class="h1"><b>Accounting</b> Management</a>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <!-- Box 1 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <p>Accounts</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Box 2 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>HR Management</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- <!-- Box 3 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <p>Inventory</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Box 4 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <p>Sales</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Box 5 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <p>Purchases</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Box 6 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-secondary">
                                    <div class="inner">
                                        <p>Expenses</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Box 7 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <p>Reports</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Box 8 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Settings</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Box 9 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <p>Banking</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Box 10 -->
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('login') }}" class="text-white">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <p>Audit</p>
                                    </div>
                                </div>
                            </a>
                        </div> --}}
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
