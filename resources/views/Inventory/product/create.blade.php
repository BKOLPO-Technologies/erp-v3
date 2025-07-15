@extends('Inventory.layouts.admin')
@section('admin')
@push('css')
<link rel="stylesheet" href="{{ asset('Accounts/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
       .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ff1190 !important
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff !important;
    }
</style>
@endpush
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
                            <label for="category_id" class="form-label">Category</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>
                                <select name="category_id" id="category_id" class="form-control select2" data-placeholder="Select Category">
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

                        {{-- tags select --}}
                        <div class="col-md-6 mb-2">
                            <label for="tag_id" class="form-label">Tag</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tags"></i></span>
                                <select name="tag_id[]" id="tag_id" class="form-control select2"  multiple="multiple" data-placeholder="Select Tag">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_id', [])) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" id="addTagBtn" data-toggle="modal" data-target="#createTagModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- brands select --}}
                        <div class="col-md-6 mb-2">
                            <label for="brand_id" class="form-label">Brand</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>
                                <select name="brand_id" id="brand_id" class="form-control select2" data-placeholder="Select Brand">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" id="addBrandBtn" data-toggle="modal" data-target="#createBrandModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @error('brand_id')
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
                                    <input type="number" class="form-control" placeholder="Enter Alert Quantity" name="alert_quantity" value="{{ old('alert_quantity', 10) }}">
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
                                    <input type="text" class="form-control" name="product_code" value="{{ old('code', $productCode) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="unit_id" class="form-label">Unit</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>
                                <select name="unit_id" id="unit_id" class="form-control select2" data-placeholder="Select Unit">
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
                            <label for="group_name" class="form-label">Group</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-users"></i></span>
                                <select name="group_name" id="group_name" class="form-control select2" data-placeholder="Select Group">
                                    <option value="">Select</option>
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

                    <!-- Product Specification Section -->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label>Product Specifications</label>

                            <div id="specificationContainer">
                                <div class="row specification-row mb-2">
                                    <div class="col-md-3">
                                        <input type="text" name="specifications[0][title]" class="form-control" placeholder="Enter Title">
                                    </div>
                                   <div class="col-md-5">
                                        <textarea name="specifications[0][description]" class="form-control" rows="1" placeholder="Enter Description"></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="specifications[0][status]" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center">
                                        <button type="button" class="btn btn-success btn-sm add-specification me-1">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <!-- First row has no remove button -->
                                    </div>
                                </div>
                            </div>

                            <small class="form-text text-muted">You can add multiple specifications for this product.</small>
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
@include('Inventory.tag.tag_modal')
@include('Inventory.brand.brand_modal')
@endsection

@push('js')
<script>
    // select 2
    $(document).ready(function() {
        $('.select2').each(function () {
            $(this).select2({
                placeholder: $(this).data('placeholder'),
                allowClear: true
            });
        });
    });

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
    // category modal
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

    // tags modal
    $('#createTagForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: '{{ route('inventory.tag.store2') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#createTagModal').modal('hide');
                    $('#createTagForm')[0].reset();

                    // ✅ Clear existing options to prevent duplicates
                    $('#tag_id').empty();

                    // ✅ Add "Select Tag" placeholder (optional)
                    $('#tag_id').append(new Option("Select Tag", ""));

                    // ✅ Append the latest tag list
                    response.all_tags.forEach(tag => {
                        $('#tag_id').append(new Option(tag.name, tag.id));
                    });

                    // ✅ Preselect the newly added tag
                    $('#tag_id').val(response.tag.id).trigger('change');

                    toastr.success('Tag added successfully!');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }

            },
            error: function(response) {
                let errors = response.responseJSON.errors;
                for (let field in errors) {
                    $(`#new_tag_${field}`).addClass('is-invalid');
                    $(`#new_tag_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        });
    });

    // brands modal
    $('#createBrandForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: '{{ route('inventory.brand.store2') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#createBrandModal').modal('hide');
                    $('#createBrandForm')[0].reset();

                    // ✅ Clear existing options to prevent duplicates
                    $('#brand_id').empty();

                    // ✅ Add "Select brand" placeholder (optional)
                    $('#brand_id').append(new Option("Select Brand", ""));

                    // ✅ Append the latest brand list
                    response.all_brands.forEach(brand => {
                        $('#brand_id').append(new Option(brand.name, brand.id));
                    });

                    // ✅ Preselect the newly added brand
                    $('#brand_id').val(response.brand.id).trigger('change');

                    toastr.success('Brand added successfully!');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }

            },
            error: function(response) {
                let errors = response.responseJSON.errors;
                for (let field in errors) {
                    $(`#new_brand_${field}`).addClass('is-invalid');
                    $(`#new_brand_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        });
    });
</script>
<script>
    let specIndex = 1;

    $(document).on('click', '.add-specification', function () {
        let row = `
        <div class="row specification-row mb-2">
            <div class="col-md-3">
                <input type="text" name="specifications[${specIndex}][title]" class="form-control" placeholder="Enter Title">
            </div>
            <div class="col-md-5">
                <textarea name="specifications[${specIndex}][description]" class="form-control" rows="1" placeholder="Enter Description"></textarea>
            </div>
            <div class="col-md-3">
                <select name="specifications[${specIndex}][status]" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-success btn-sm add-specification mr-1">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm remove-specification">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>`;
        $('#specificationContainer').append(row);
        specIndex++;
    });

    $(document).on('click', '.remove-specification', function () {
        $(this).closest('.specification-row').remove();
    });
</script>
@endpush