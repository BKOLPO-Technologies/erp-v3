@extends('Inventory.layouts.admin')
@section('admin')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $pageTitle ?? '' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.stockinward.index') }}">Stock Inward</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Add New Stock Inward</h4>
                                <a href="{{ route('inventory.stockinward.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        
                        <form role="form" action="{{ route('inventory.stockinward.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <label for="vendor_id">Vendor <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('vendor_id') is-invalid @enderror" 
                                                id="vendor_id" name="vendor_id" required>
                                            <option value="">Select Vendor</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                    {{ $vendor->name }} ({{ $vendor->company }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vendor_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <label for="product_id">Product <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('product_id') is-invalid @enderror" 
                                                id="product_id" name="product_id" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} ({{ $product->product_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('product_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="total_price">Total Cost <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control @error('total_price') is-invalid @enderror" 
                                               id="total_price" name="total_price" min="0" 
                                               value="{{ old('total_price') }}" placeholder="Enter total cost" required >
                                        @error('total_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                               id="quantity" name="quantity" min="1" 
                                               value="{{ old('quantity',1) }}" placeholder="Enter quantity" required>
                                        @error('quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="unit_price">Unit Cost <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror" 
                                               id="unit_price" name="unit_price" min="0" 
                                               value="{{ old('unit_price',0) }}" placeholder="Enter unit price" required readonly>
                                        @error('unit_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
              
                                    <div class="col-md-12 mb-3">
                                        <label for="comments">Comments</label>
                                        <textarea class="form-control @error('comments') is-invalid @enderror" 
                                                  id="comments" name="comments" rows="3" placeholder="Enter comments">{{ old('comments') }}</textarea>
                                        @error('comments')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary float-right">
                                            <i class="fas fa-save"></i> Save Stock Inward
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
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Calculate unit price based on total cost and quantity
        const totalCostInput = document.getElementById('total_price');
        const quantityInput = document.getElementById('quantity');
        const unitPriceInput = document.getElementById('unit_price');

        function calculateUnitPrice() {
            const totalCost = parseFloat(totalCostInput.value) || 0;
            const quantity = parseFloat(quantityInput.value) || 0;

            if (quantity > 0) {
                const unitPrice = totalCost / quantity;
                unitPriceInput.value = unitPrice.toFixed(2); // rounding to 2 decimal places
            } else {
                unitPriceInput.value = "0.00";
            }
        }

        // Add event listeners for both input and change events
        totalCostInput.addEventListener('input', calculateUnitPrice);
        quantityInput.addEventListener('input', calculateUnitPrice);
        
        // Also calculate on page load if values exist
        calculateUnitPrice();

    });
</script>
@endpush