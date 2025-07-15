@extends('Inventory.layouts.admin')
@section('admin')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Create</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.product.index') }}" style="text-decoration: none; color: black;">Product</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline shadow-lg">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Add New Product</h4>
                    <a href="{{ route('inventory.product.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
            
              <form role="form" action="{{ route('inventory.product.store') }}" method="POST" enctype="multipart/form-data">
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
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Enter Product Name" name="name" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="category_id" class="form-label">Category Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>
                                <select name="category_id" id="category_id" class="form-control select2">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" id="addCategoryBtn" data-toggle="modal" data-target="#createCategoryModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Price
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter Product Price" name="price" value="{{ old('price') }}">

                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Purchases Price
                                    @error('purchase_price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter Purchases Price" name="purchase_price" value="{{ old('purchase_price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Selling Price
                                    @error('selling_price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter Selling Pirce" name="selling_price" value="{{ old('selling_price') }}">
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity
                                    @error('quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter Product Quantity" name="quantity" value="{{ old('quantity') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alert Quantity
                                    @error('alert_quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter Alert Quantity" name="alert_quantity" value="{{ old('alert_quantity', 5) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Code
                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="code" value="{{ old('code', $productCode) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="unit_id" class="form-label">Unit Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>
                                <select name="unit_id" id="unit_id" class="form-control select2">
                                    <option value="">Select unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" id="addUnitBtn" data-toggle="modal" data-target="#createUnitModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @error('unit_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <div class="col-md-6">
                            <label for="group_name" class="form-label">Group Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-users"></i></span>
                                <select name="group_name" id="group_name" class="form-control select2">
                                    <option value="">Select Group</option>
                                    <option value="sales" {{ old('group_name') == 'sales' ? 'selected' : '' }}>Sales</option>
                                    <option value="purchases" {{ old('group_name') == 'purchases' ? 'selected' : '' }}>Purchases</option>
                                </select>
                            </div>
                            @error('group_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>                    

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <textarea name="description" placeholder="Enter Product Description" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Main Image Upload -->
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Main Image</label>
                                <input type="file" class="form-control" name="image" id="imageInput">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <img id="imagePreview" src="" alt="Image Preview" style="display:none; margin-top: 10px; height:80px; height:70px;">
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
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

                    <!-- Multiple Image Upload Section -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Additional Images</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="multipleImageInput" name="product_images[]" multiple>
                                    <label class="custom-file-label" for="multipleImageInput">Choose multiple images</label>
                                </div>
                                <small class="form-text text-muted">
                                    Maximum 5 images allowed (Max 2MB each)
                                </small>
                            </div>
                            
                            <!-- Image preview container -->
                            <div class="row mt-2" id="multipleImagePreview">
                                <!-- Preview thumbnails will be added here -->
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                <i class="fas fa-plus"></i> Add Product
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

<!-- Modal for creating a new branch -->
@include('Accounts.unit.unit_modal')
@include('Accounts.category.category_modal')
@endsection

@push('js')
<script>
    // Main image preview
    document.getElementById('imageInput').addEventListener('change', function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    // Multiple image upload functionality
    document.getElementById('multipleImageInput').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('multipleImagePreview');
        previewContainer.innerHTML = ''; // Clear previous previews
        
        if (this.files) {
            // Limit to 5 images
            const files = Array.from(this.files).slice(0, 5);
            
            files.forEach((file, index) => {
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    toastr.error(`File "${file.name}" is too large (max 2MB)`);
                    return;
                }
                
                // Validate image type
                if (!file.type.match('image.*')) {
                    toastr.error(`File "${file.name}" is not an image`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview element
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-md-2 mb-3 position-relative';
                    colDiv.innerHTML = `
                        <div class="thumbnail-container">
                            <img src="${e.target.result}" class="img-thumbnail" style="height: 100px; width: 100%; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: -10px; right: -10px;" onclick="removeImagePreview(this, ${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(colDiv);
                }
                reader.readAsDataURL(file);
            });
        }
    });

    // Function to remove image preview
    function removeImagePreview(button, index) {
        // Remove the preview
        button.closest('.col-md-2').remove();
        
        // Create a new DataTransfer object to update the file input
        const dataTransfer = new DataTransfer();
        const input = document.getElementById('multipleImageInput');
        
        // Add all files except the one being removed
        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dataTransfer.items.add(file);
            }
        });
        
        // Update the files property of the input
        input.files = dataTransfer.files;
        
        // Update the file input label
        updateFileInputLabel();
    }

    // Update the file input label with selected file count
    function updateFileInputLabel() {
        const input = document.getElementById('multipleImageInput');
        const label = input.nextElementSibling;
        const files = input.files;
        
        if (files.length > 0) {
            label.textContent = `${files.length} file(s) selected`;
        } else {
            label.textContent = 'Choose multiple images';
        }
    }

    // Initialize file input label on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateFileInputLabel();
        
        // Update label when files are selected
        document.getElementById('multipleImageInput').addEventListener('change', updateFileInputLabel);
    });
</script>

<script> 
    $('#createUnitForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: '{{ route('accounts.unit.store2') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#createUnitModal').modal('hide');
                    $('#createUnitForm')[0].reset();
                    $('#unit_id').append(new Option(response.unit.name, response.unit.id));
                    $('#unit_id').trigger('change');
                    toastr.success('Unit added successfully!');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            error: function(response) {
                let errors = response.responseJSON.errors;
                for (let field in errors) {
                    $(`#new_unit_${field}`).addClass('is-invalid');
                    $(`#new_unit_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        });
    });
</script>

<script> 
    $('#createCategoryForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: '{{ route('accounts.category.store2') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#createCategoryModal').modal('hide');
                    $('#createCategoryForm')[0].reset();
                    $('#category_id').append(new Option(response.category.name, response.category.id));
                    $('#category_id').trigger('change');
                    toastr.success('Category added successfully!');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            error: function(response) {
                let errors = response.responseJSON.errors;
                for (let field in errors) {
                    $(`#new_category_${field}`).addClass('is-invalid');
                    $(`#new_category_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        });
    });
</script>

@endpush

@push('css')
<style>
    .thumbnail-container {
        position: relative;
        margin: 5px;
    }
    .custom-file-input ~ .custom-file-label::after {
        content: "Browse";
    }
    #multipleImagePreview img {
        cursor: pointer;
        transition: all 0.3s;
    }
    #multipleImagePreview img:hover {
        opacity: 0.8;
    }
</style>
@endpush