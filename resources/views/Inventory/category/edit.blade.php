@extends('Inventory.layouts.admin')
@section('admin')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Category Edit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.category.index') }}" style="text-decoration: none; color: black;">Category</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                    <h4 class="mb-0">Edit Category</h4>
                    <a href="{{ route('inventory.category.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="{{ route('inventory.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name 
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Category Name" 
                                       value="{{ old('name', $category->name) }}" name="name">
                            </div>
                        </div>
                        {{-- image --}}
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="image" class="form-label">Image
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-image"></i></span>
                                    <input type="file" class="form-control" id="image" name="image" onchange="previewImage(this)">
                                </div>
                                <small class="form-text text-muted">Upload new image to replace existing one</small>
                                <div class="mt-2">
                                    @if($category->image)
                                        <img id="imagePreview" src="{{ asset('upload/Inventory/categories/'.$category->image) }}" 
                                             alt="Current Image" style="max-height: 150px;" class="img-thumbnail">
                                    @else
                                        <img id="imagePreview" src="" alt="Image Preview" 
                                             style="max-height: 150px; display: none;" class="img-thumbnail">
                                    @endif
                                </div>
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
                                    <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                <i class="fas fa-save"></i> Update Category
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
    // Image preview function
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.addEventListener('load', function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            });
            
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush