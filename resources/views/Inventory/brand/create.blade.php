@extends('Inventory.layouts.admin')
@section('admin')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Brand Create</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.brand.index') }}" style="text-decoration: none; color: black;">Brand</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline shadow-lg">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Add New Brand</h4>
                    <a href="{{ route('inventory.brand.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="{{ route('inventory.brand.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name 
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Brand Name" name="name">
                            </div>
                        </div>
                        {{-- logo --}}
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="logo" class="form-label">Logo
                                    @error('logo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-image"></i></span>
                                    <input type="file" class="form-control" id="logo" name="logo" onchange="previewLogo(this)">
                                </div>
                                <small class="form-text text-muted">Upload brand logo (optional)</small>
                                <div class="mt-2">
                                    <img id="logoPreview" src="" alt="Logo Preview" style="max-height: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                        {{-- description --}}
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="description" class="form-label">Description
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter brand description"></textarea>
                            </div>
                        </div>
                        {{-- status --}}
                        <div class="col-md-12 mb-2">
                            <label for="status" class="form-label">Status
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                <i class="fas fa-plus"></i>  Add Brand
                            </button>
                        </div>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection

@push('js')
<script>
    // Logo preview function
    function previewLogo(input) {
        const preview = document.getElementById('logoPreview');
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.addEventListener('load', function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            });
            
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
    
    // Initialize the preview if there's an existing image (for edit scenarios)
    document.addEventListener('DOMContentLoaded', function() {
        const logoInput = document.getElementById('logo');
        const preview = document.getElementById('logoPreview');
        
        // If you want to show an existing image when editing, you would set it here
        // For create forms, this will remain empty
    });
</script>
@endpush