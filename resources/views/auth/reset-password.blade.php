<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bkolpo Accounting | Reset Password</title>
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

<body class="hold-transition login-page"
    style="background: url('Accounts/login.jpg') no-repeat center center / cover; overflow: hidden;">
    <div class="login-box">
        <div class="card card-outline card-primary shadow-lg">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>ERP</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Reset your password?</p>

                <form action="{{ route('password.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Disabled Email --}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}"
                            class="form-control" placeholder="Enter Email" disabled required>
                    </div>

                    {{-- Password --}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="New Password"
                            id="password">
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password" onclick="togglePassword('password', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password" id="password_confirmation">
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password"
                                onclick="togglePassword('password_confirmation', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('login') }}" class="btn btn-success btn-block">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Login
                    </a>
                </p>
            </div>

        </div>
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
        function togglePassword(id, el) {
            const input = document.getElementById(id);
            const icon = el.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
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
