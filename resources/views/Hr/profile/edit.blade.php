@extends('Hr.layouts.admin')
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
                        <li class="breadcrumb-item"><a href="{{ route('hr.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow"> {{-- Add card and shadow class --}}
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Edit Profile</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Display current profile image --}}
                        <div class="form-group">
                            <label>Current Profile Image</label><br>
                            <img 
                                id="imagePreview"
                                src="{{ $user->profile_image ? asset($user->profile_image) : asset('Accounts/dist/img/avatar5.png') }}" 
                                alt="Profile Image" 
                                class="img-thumbnail" 
                                width="120"
                            >
                        </div>

                        {{-- Upload new profile image --}}
                        <div class="form-group mt-3">
                            <label for="profile_image">Change Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" id="profileImageInput">
                            @error('profile_image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group mt-4">
                            <label for="name">Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Enter name" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="Enter email" disabled required>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.getElementById('profileImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                preview.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
