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
                        <li class="breadcrumb-item"><a href="{{ route('inventory.stockoutward.index') }}">Stock Outward</a></li>
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
                                <h4 class="mb-0">Create New Stock Outward Order</h4>
                                <a href="{{ route('inventory.stockoutward.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        
                        <form role="form" action="{{ route('inventory.stockoutward.store') }}" method="POST" id="stockOutwardForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <label for="customer_id">Customer <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('customer_id') is-invalid @enderror" 
                                                id="customer_id" name="customer_id" required>
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} ({{ $customer->company }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                        <label for="order_id">Order Reference (Optional)</label>
                                        <select class="form-control select2" id="order_id" name="order_id">
                                            <option value="">Select Order</option>
                                            @if(old('customer_id'))
                                                @foreach(Order::where('customer_id', old('customer_id'))->get() as $order)
                                                    <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                        {{ $order->order_number }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <h5 class="mt-4 mb-3">Products</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="productTable">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th width="30%">Product</th>
                                                        <th width="15%">Available</th>
                                                        <th width="15%">Quantity</th>
                                                        <th width="15%">Unit Price</th>
                                                        <th width="15%">Total</th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Initial empty row -->
                                                    <tr>
                                                        <td>
                                                            <select class="form-control select2 product-select" name="products[0][product_id]" required>
                                                                <option value="">Select Product</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{ $product->id }}" 
                                                                        data-stock="{{ $product->stock->quantity ?? 0 }}"
                                                                        data-price="{{ $product->price }}">
                                                                        {{ $product->name }} ({{ $product->product_code }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="available-stock">0</td>
                                                        <td>
                                                            <input type="number" class="form-control quantity" 
                                                                   name="products[0][quantity]" min="1" value="1" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control unit-price" 
                                                                   name="products[0][unit_price]" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control total-price" 
                                                                   name="products[0][total_price]" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                                                        <td>
                                                            <input type="number" class="form-control grand-total" 
                                                                   name="grand_total" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-sm add-row">
                                                                <i class="fas fa-plus"></i> Add
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="comments">Comments</label>
                                        <textarea class="form-control @error('comments') is-invalid @enderror" 
                                                  id="comments" name="comments" rows="3" placeholder="Enter any additional comments">{{ old('comments') }}</textarea>
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
                                            <option value="1" {{ old('status', 1) == '1' ? 'selected' : '' }}>Active</option>
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
                                            <i class="fas fa-save"></i> Save Stock Outward
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

    // Load orders when customer changes
    $('#customer_id').change(function() {
        let customerId = $(this).val();
        $('#order_id').empty().append('<option value="">Loading...</option>');

        if (customerId) {
            $.ajax({
                url: '/inventory/stockoutward/get-orders/' + customerId,
                method: 'GET',
                success: function(data) {
                    $('#order_id').empty().append('<option value="">Select Order</option>');
                    $.each(data, function(i, order) {
                        $('#order_id').append('<option value="'+order.id+'">'+order.order_number+'</option>');
                    });
                }
            });
        }
    });

    // Add new product row
    let rowCount = 1;
    $('.add-row').click(function() {
        let newRow = `
        <tr>
            <td>
                <select class="form-control select2 product-select" name="products[${rowCount}][product_id]" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                            data-stock="{{ $product->stock->quantity ?? 0 }}"
                            data-price="{{ $product->price }}">
                            {{ $product->name }} ({{ $product->product_code }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="available-stock">0</td>
            <td>
                <input type="number" class="form-control quantity" 
                       name="products[${rowCount}][quantity]" min="1" value="1" required>
            </td>
            <td>
                <input type="number" class="form-control unit-price" 
                       name="products[${rowCount}][unit_price]" readonly>
            </td>
            <td>
                <input type="number" class="form-control total-price" 
                       name="products[${rowCount}][total_price]" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
        $('#productTable tbody').append(newRow);
        $('.select2').select2({ theme: 'bootstrap4' });
        rowCount++;
    });

    // Remove product row
    $(document).on('click', '.remove-row', function() {
        if ($('#productTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        } else {
            alert('You must have at least one product!');
        }
    });

    // Product selection change
    $(document).on('change', '.product-select', function() {
        let row = $(this).closest('tr');
        let selectedOption = $(this).find('option:selected');
        let availableStock = selectedOption.data('stock');
        let unitPrice = selectedOption.data('price');

        row.find('.available-stock').text(availableStock);
        row.find('.unit-price').val(unitPrice);
        row.find('.quantity').attr('max', availableStock);
        calculateRowTotal(row);
    });

    // Quantity change
    $(document).on('input', '.quantity', function() {
        let row = $(this).closest('tr');
        let max = parseInt(row.find('.quantity').attr('max'));
        let entered = parseInt($(this).val());

        if (entered > max) {
            alert('Quantity cannot exceed available stock!');
            $(this).val(max);
        }
        calculateRowTotal(row);
    });

    // Calculate row total
    function calculateRowTotal(row) {
        let quantity = parseInt(row.find('.quantity').val()) || 0;
        let unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        let total = quantity * unitPrice;
        row.find('.total-price').val(total.toFixed(2));
        calculateGrandTotal();
    }

    // Calculate grand total
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.total-price').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('.grand-total').val(grandTotal.toFixed(2));
    }

    // Form submission validation
    $('#stockOutwardForm').submit(function(e) {
        let valid = true;
        $('.product-select').each(function() {
            if (!$(this).val()) {
                alert('Please select a product for all rows');
                valid = false;
                return false;
            }
        });
        return valid;
    });

    // Initialize first row
    $('.product-select').first().trigger('change');
});
</script>
@endpush