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
                        <li class="breadcrumb-item"><a href="{{ route('inventory.order.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('inventory.order.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Edit Order</h4>
                                    <div>
                                        <a href="{{ route('inventory.order.index')}}" class="btn btn-sm btn-danger rounded-0">
                                            <i class="fa-solid fa-arrow-left"></i> Back To List
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Order Header -->
                                <div class="row mb-4">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Customer</label>
                                            <select name="customer_id" class="form-control select2">
                                                <option value="">Select Customer</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }} ({{ $customer->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Order Date</label>
                                            <input type="date" name="order_date" class="form-control" value="{{ $order->order_date->format('Y-m-d') }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Expected Delivery Date</label>
                                            <input type="date" name="expected_delivery_date" class="form-control" 
                                                value="{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Order Status</label>
                                            <select name="order_status" class="form-control">
                                                @foreach($orderStatuses as $key => $status)
                                                    <option value="{{ $key }}" {{ $order->order_status == $key ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Payment Status</label>
                                            <select name="payment_status" class="form-control">
                                                @foreach($paymentStatuses as $key => $status)
                                                    <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Payment Method</label>
                                            <input type="text" name="payment_method" class="form-control" placeholder="Enter payment method" value="{{ $order->payment_method }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Invoice-like Order Items -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Product</th>
                                                        <th width="160">Price</th>
                                                        <th width="160">Quantity</th>
                                                        <th width="140">Total</th>
                                                        <th width="40"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cartItems">
                                                    @foreach($order->items as $item)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $item->product->name }}</strong><br>
                                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="items[{{ $item->product_id }}][price]" 
                                                                class="form-control price-input" 
                                                                value="{{ $item->unit_price }}" min="0" step="0.01">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="items[{{ $item->product_id }}][quantity]" 
                                                                class="form-control quantity-input" 
                                                                value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity + $item->quantity }}">
                                                        </td>
                                                        <td class="text-right line-total">{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-danger remove-item" data-id="{{ $item->product_id }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Order Summary -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea name="notes" class="form-control" rows="3" placeholder="Enter notes">{{ $order->notes }}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Shipping Address</label>
                                            <textarea name="shipping_address" class="form-control" rows="3" placeholder="Enter shipping address">{{ $order->shipping_address }}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Billing Address</label>
                                            <textarea name="billing_address" class="form-control" rows="3" placeholder="Enter billing address">{{ $order->billing_address }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="order-totals">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span id="subtotalDisplay">{{ number_format($order->subtotal, 2) }}</span>
                                                <input type="hidden" name="subtotal" id="subtotal" value="{{ $order->subtotal }}">
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>VAT (%):</span>
                                                <span>
                                                    <input type="number" name="vat_rate" class="form-control form-control-sm d-inline-block w-auto vat-rate" 
                                                        value="{{ $order->vat_rate }}" min="0" step="0.01" placeholder="Enter vat">
                                                </span>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>VAT Amount:</span>
                                                <span id="vatAmount">{{ number_format($order->vat_amount, 2) }}</span>
                                                <input type="hidden" name="vat_amount" id="vatAmountValue" value="{{ $order->vat_amount }}">
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Tax (%):</span>
                                                <span>
                                                    <input type="number" name="tax_rate" class="form-control form-control-sm d-inline-block w-auto tax-rate" 
                                                        value="{{ $order->tax_rate }}" min="0" step="0.01" placeholder="Enter tax">
                                                </span>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Tax Amount:</span>
                                                <span id="taxAmount">{{ number_format($order->tax_amount, 2) }}</span>
                                                <input type="hidden" name="tax_amount" id="taxAmountValue" value="{{ $order->tax_amount }}">
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Discount:</span>
                                                <span>
                                                    <input type="number" name="discount" class="form-control form-control-sm d-inline-block w-auto discount" 
                                                        value="{{ $order->discount }}" min="0" step="0.01">
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between font-weight-bold mt-3 pt-2 border-top">
                                                <span>Total Amount:</span>
                                                <span id="totalAmountDisplay">{{ number_format($order->total_amount, 2) }}</span>
                                                <input type="hidden" name="total_amount" id="totalAmount" value="{{ $order->total_amount }}">
                                            </div>
                                            
                                            <div class="d-flex justify-content-between font-weight-bold mt-2">
                                                <span>Paid Amount:</span>
                                                <span>
                                                    <input type="number" name="paid_amount" class="form-control form-control-sm d-inline-block w-auto paid-amount" 
                                                        value="{{ $order->paid_amount }}" min="0" step="0.01">
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between font-weight-bold mt-2 text-danger">
                                                <span>Due Amount:</span>
                                                <span id="dueAmountDisplay">{{ number_format($order->due_amount, 2) }}</span>
                                                <input type="hidden" name="due_amount" id="dueAmount" value="{{ $order->due_amount }}">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary rounded-0 mt-3" style="float: right;">
                                            <i class="fas fa-save"></i> Update Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@push('css')
<style>
    .product-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .product-card .card-body {
        padding: 10px;
    }
    .product-card p {
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .product-card h6 {
        font-size: 16px;
        font-weight: bold;
    }
    #cartItems tr td {
        vertical-align: middle;
        padding: 8px;
    }
    .search-container {
        width: 300px;
    }
    .order-totals {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px 10px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .table-bordered thead th {
        border-bottom-width: 1px;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });
        
        // Calculate line total when quantity or price changes
        $(document).on('input', '.price-input, .quantity-input', function() {
            let row = $(this).closest('tr');
            let price = parseFloat(row.find('.price-input').val()) || 0;
            let quantity = parseInt(row.find('.quantity-input').val()) || 0;
            let lineTotal = (price * quantity).toFixed(2);
            
            row.find('.line-total').text(lineTotal);
            calculateOrderTotals();
        });
        
        // Calculate totals when tax, vat, discount or paid amount changes
        $(document).on('input', '.vat-rate, .tax-rate, .discount, .paid-amount', function() {
            calculateOrderTotals();
        });
        
        // Remove item from order
        $(document).on('click', '.remove-item', function() {
            let productId = $(this).data('id');
            let orderId = {{ $order->id }};
            let row = $(this).closest('tr');
            
            if(confirm('Are you sure you want to remove this item?')) {
                // Show loading indicator
                $(this).html('<i class="fas fa-spinner fa-spin"></i>');
                
                // Make AJAX request to remove item
                $.ajax({
                    url: "{{ route('inventory.order.removeItem') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_id: orderId,
                        product_id: productId
                    },
                    success: function(response) {
                        if(response.success) {
                            // Remove row from table
                            row.remove();
                            // Recalculate totals
                            calculateOrderTotals();
                            // Show success message
                            toastr.success(response.message);
                        } else {
                            // Show error message
                            toastr.error(response.message);
                            // Reset button icon
                            row.find('.remove-item').html('<i class="fas fa-trash"></i>');
                        }
                    },
                    error: function(xhr) {
                        // Show error message
                        toastr.error('An error occurred while removing the item');
                        // Reset button icon
                        row.find('.remove-item').html('<i class="fas fa-trash"></i>');
                    }
                });
            }
        });
        
        // Calculate all order totals
        function calculateOrderTotals() {
            let subtotal = 0;
            
            // Calculate subtotal from all items
            $('#cartItems tr').each(function() {
                let price = parseFloat($(this).find('.price-input').val()) || 0;
                let quantity = parseInt($(this).find('.quantity-input').val()) || 0;
                subtotal += (price * quantity);
            });
            
            // Get rates and amounts
            let vatRate = parseFloat($('.vat-rate').val()) || 0;
            let taxRate = parseFloat($('.tax-rate').val()) || 0;
            let discount = parseFloat($('.discount').val()) || 0;
            let paidAmount = parseFloat($('.paid-amount').val()) || 0;
            
            // Calculate VAT and Tax amounts
            let vatAmount = (subtotal * vatRate / 100);
            let taxAmount = (subtotal * taxRate / 100);
            
            // Calculate total after discount
            let totalBeforeDiscount = subtotal + vatAmount + taxAmount;
            let totalAmount = totalBeforeDiscount - discount;
            
            // Calculate due amount
            let dueAmount = Math.max(0, totalAmount - paidAmount);
            
            // Update all displayed values
            $('#subtotalDisplay').text(subtotal.toFixed(2));
            $('#subtotal').val(subtotal.toFixed(2));
            
            $('#vatAmount').text(vatAmount.toFixed(2));
            $('#vatAmountValue').val(vatAmount.toFixed(2));
            
            $('#taxAmount').text(taxAmount.toFixed(2));
            $('#taxAmountValue').val(taxAmount.toFixed(2));
            
            $('#totalAmountDisplay').text(totalAmount.toFixed(2));
            $('#totalAmount').val(totalAmount.toFixed(2));
            
            $('#dueAmountDisplay').text(dueAmount.toFixed(2));
            $('#dueAmount').val(dueAmount.toFixed(2));
        }
        
        // Initial calculation when page loads
        calculateOrderTotals();
    });
</script>
@endpush