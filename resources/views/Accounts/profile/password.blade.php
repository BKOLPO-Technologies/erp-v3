@extends('Accounts.layouts.admin')
@section('admin')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Password Change</h3>
                    </div>
                    <div class="card-body">
                       <form action="{{ route('accounts.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            {{-- Current Password --}}
                            <div class="form-group position-relative">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control pr-5" placeholder="Enter current password" value="{{ old('current_password') }}" required>
                                <span toggle="#current_password" class="fas fa-eye toggle-password position-absolute"
                                    style="top: 38px; right: 15px; cursor: pointer;"></span>
                                @error('current_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="form-group mt-3 position-relative">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control pr-5"
                                    placeholder="Enter new password" value="{{ old('new_password') }}"  required>
                                <span toggle="#new_password" class="fas fa-eye toggle-password position-absolute"
                                    style="top: 38px; right: 15px; cursor: pointer;"></span>
                                @error('new_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="form-group mt-3 position-relative">
                                <label for="new_password_confirmation">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="form-control pr-5" placeholder="Enter confirm new password" value="{{ old('new_password_confirmation') }}"  required>
                                <span toggle="#new_password_confirmation"
                                    class="fas fa-eye toggle-password position-absolute"
                                    style="top: 38px; right: 15px; cursor: pointer;"></span>
                                <small id="matchMessage" class="mt-1 d-block"></small>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success">Change Password</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(item => {
        item.addEventListener('click', function () {
            const input = document.querySelector(this.getAttribute('toggle'));
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });

    // Real-time password match check
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const matchMessage = document.getElementById('matchMessage');

    function checkMatch() {
        if (!confirmPassword.value) {
            matchMessage.textContent = '';
            confirmPassword.classList.remove('is-valid', 'is-invalid');
            return;
        }

        if (newPassword.value === confirmPassword.value) {
            confirmPassword.classList.remove('is-invalid');
            confirmPassword.classList.add('is-valid');
            matchMessage.textContent = '✔ Passwords match';
            matchMessage.classList.remove('text-danger');
            matchMessage.classList.add('text-success');
        } else {
            confirmPassword.classList.remove('is-valid');
            confirmPassword.classList.add('is-invalid');
            matchMessage.textContent = '✘ Passwords do not match';
            matchMessage.classList.remove('text-success');
            matchMessage.classList.add('text-danger');
        }
    }

    newPassword.addEventListener('keyup', checkMatch);
    confirmPassword.addEventListener('keyup', checkMatch);
</script>
@endpush

