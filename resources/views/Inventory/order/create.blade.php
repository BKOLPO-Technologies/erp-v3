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
                        <li class="breadcrumb-item"><a href="{{ route('inventory.order.index') }}">Order</a></li>
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
                <div class="col-md-8">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Products</h4>
                                <div class="search-container">
                                    <input type="text" class="form-control" placeholder="Search by Product Name/Barcode" id="productSearch">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select class="form-control select2" id="categoryFilter">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control select2" id="brandFilter">
                                        <option value="">All Brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="resetFilters" class="btn btn-danger w-100">
                                        <i class="fas fa-redo"></i> Reset Filters
                                    </button>
                                </div>
                            </div>

                            <div class="row" id="productGrid">
                                @foreach($products as $product)
                                    <div class="col-md-3 mb-3 product-card"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}"
                                        data-category="{{ $product->category_id}}"
                                        data-brand="{{ $product->brand_id }}"
                                        data-quantity="{{ $product->quantity }}">
                                        <div class="card shadow h-100">
                                            <div class="card-body text-center">
                                                <span class="badge badge-{{ $product->quantity > 0 ? 'success' : 'danger' }}">
                                                    In stock: {{ $product->quantity }}
                                                </span>
                                                <img src="{{ asset('upload/Inventory/products/' . ($product->image ?? 'default.png')) }}"
                                                    class="img-fluid my-2"
                                                    alt="Product Image"
                                                    style="height: 100px; object-fit: contain;">
                                                
                                                {{-- Show category name --}}
                                                <span class="badge badge-warning mb-1">{{ $product->category->name ?? 'No Category' }}</span>

                                                <p class="mb-1">{{ $product->name }}</p>
                                                <h6 class="mb-2">{{ number_format($product->price, 3) }}</h6>
                                                <button type="button" class="btn btn-primary btn-sm add-to-cart"
                                                        data-id="{{ $product->id }}"
                                                        {{ $product->quantity < 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div id="noProductsMessage" class="alert alert-warning text-center" style="display: none;">
                                No products found.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-success card-outline shadow-lg">
                        <div class="card-header">
                            <h4 class="mb-0">Order Summary</h4>
                        </div>
                        <form action="{{ route('inventory.order.store') }}" method="POST" id="orderForm">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select class="form-control select2" name="customer_id" id="customerSelect" required>
                                        <option value="" {{ old('customer_id') == '' ? 'selected' : '' }}>Walk In Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" 
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="cart-summary">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th width="20%">Qty</th>
                                                <th width="25%">Total</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartItems">
                                            @if(!empty($cart))
                                                @foreach($cart as $item)
                                                    <tr data-id="{{ $item['id'] }}">
                                                        <td>
                                                            {{ $item['name'] }}
                                                            <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $item['id'] }}">
                                                            <input type="hidden" name="products[{{ $loop->index }}][price]" value="{{ $item['price'] }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control quantity-input" 
                                                                name="products[{{ $loop->index }}][quantity]"
                                                                value="{{ $item['quantity'] }}" min="1" 
                                                                max="{{ $item['max_quantity'] }}"
                                                                data-id="{{ $item['id'] }}">
                                                        </td>
                                                        <td>{{ number_format($item['price'] * $item['quantity'], 3) }}</td>
                                                        <td>
                                                            <button class="btn btn-danger btn-sm remove-item" 
                                                                    data-id="{{ $item['id'] }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No items in cart</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="order-totals mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">0.000</span>
                                        <input type="hidden" name="subtotal" id="subtotalValue" value="0">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>VAT (%):</span>
                                        <input type="number" class="form-control form-control-sm w-50" 
                                            id="vatRateInput" name="vat_rate" value="{{ old('vat_rate', 0) }}" min="0" max="100" step="0.1">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>VAT Amount:</span>
                                        <span id="vatAmount">0.000</span>
                                        <input type="hidden" name="vat_amount" id="vatAmountValue" value="0">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax (%):</span>
                                        <input type="number" class="form-control form-control-sm w-50" 
                                            id="taxRateInput" name="tax_rate" value="{{ old('tax_rate', 0) }}" min="0" max="100" step="0.1">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax Amount:</span>
                                        <span id="taxAmount">0.000</span>
                                        <input type="hidden" name="tax_amount" id="taxAmountValue" value="0">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Discount:</span>
                                        <input type="number" class="form-control form-control-sm w-50" 
                                            id="discountInput" name="discount" value="{{ old('discount', 0) }}" min="0" step="0.001">
                                    </div>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Total:</span>
                                        <span id="totalAmount">0.000</span>
                                        <input type="hidden" name="total_amount" id="totalAmountValue" value="0">
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="notes">Order Notes</label>
                                    <textarea class="form-control" name="notes" id="notes" rows="2" placeholder="Enter note">{{ old('notes') }}</textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" id="clearCartBtn">
                                        <i class="fas fa-trash"></i> Clear
                                    </button>
                                    <button type="submit" class="btn btn-success" id="placeOrderBtn">
                                        <i class="fas fa-check"></i> Place Order
                                    </button>
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
    .quantity-input {
        width: 60px;
        display: inline-block;
    }
    .search-container {
        width: 300px;
    }
    .order-totals {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        // Product search and filter
        $('#productSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            const categoryFilter = $('#categoryFilter').val();
            const brandFilter = $('#brandFilter').val();

            $('.product-card').each(function() {
                const productName = $(this).data('name').toLowerCase();
                const productCategory = $(this).data('category').toString();
                const productBrand = $(this).data('brand').toString();

                const matchesSearch = productName.includes(searchTerm);
                const matchesCategory = !categoryFilter || productCategory === categoryFilter;
                const matchesBrand = !brandFilter || productBrand === brandFilter;

                $(this).toggle(matchesSearch && matchesCategory && matchesBrand);
            });

            toggleNoProductsMessage();
        });

        $('#categoryFilter, #brandFilter').change(function() {
            $('#productSearch').trigger('keyup');
        });

        $('#resetFilters').on('click', function() {
            $('#categoryFilter').val('').trigger('change');
            $('#brandFilter').val('').trigger('change');
            $('#productSearch').val('');

            $('.product-card').show(); 
            toggleNoProductsMessage();
        });


        function toggleNoProductsMessage() {
            const visibleProducts = $('.product-card:visible').length;
            if (visibleProducts === 0) {
                $('#noProductsMessage').show();
            } else {
                $('#noProductsMessage').hide();
            }
        }

        // Add to cart
        $(document).on('click', '.add-to-cart', function() {
            const productId = $(this).data('id');
            
            $.ajax({
                url: "{{ route('inventory.order.addToCart') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadCart();
                    }
                },
                error: function(xhr) {
                    toastr.error('Error adding product to cart');
                }
            });
        });

        // Update cart quantity
        $(document).on('keyup change', '.quantity-input', function() {
            const productId = $(this).data('id');
            const quantity = $(this).val();
            
            if (quantity > 0) {
                $.ajax({
                    url: "{{ route('inventory.order.updateCart') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            loadCart();
                        } else {
                            toastr.error(response.message);
                            loadCart();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error updating cart');
                        loadCart();
                    }
                });
            }
        });

        // Remove from cart
        $(document).on('click', '.remove-item', function() {
            const productId = $(this).data('id');
            
            $.ajax({
                url: "{{ route('inventory.order.removeFromCart') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadCart();
                    }
                },
                error: function(xhr) {
                    toastr.error('Error removing product from cart');
                }
            });
        });

        // Clear cart
        $('#clearCartBtn').click(function() {
            if (confirm('Are you sure you want to clear the cart?')) {
                $.ajax({
                    url: "{{ route('inventory.order.clearCart') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Cart cleared');
                            loadCart();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error clearing cart');
                    }
                });
            }
        });

        // Calculate totals when inputs change - FIXED SELECTOR HERE
        $('#vatRateInput, #taxRateInput, #discountInput, #shippingInput').on('keyup change', function() {
            calculateTotals();
        });

        // Load cart via AJAX
        function loadCart() {
            $.get("{{ route('inventory.order.create') }}", function(data) {
                $('#cartItems').html($(data).find('#cartItems').html());
                calculateTotals();
            });
        }

        // Calculate order totals
        function calculateTotals() {
            let subtotal = 0;

            // Calculate subtotal from cart items
            $('#cartItems tr').each(function() {
                // Skip the empty cart message row
                if ($(this).find('td').length > 1) {
                    const priceText = $(this).find('td:eq(2)').text().replace(/,/g, '');
                    const price = parseFloat(priceText) || 0;
                    subtotal += price;
                }
            });

            // Get rates and amounts with NaN protection
            const vatRate = parseFloat($('#vatRateInput').val()) || 0;
            const taxRate = parseFloat($('#taxRateInput').val()) || 0;
            const discount = parseFloat($('#discountInput').val()) || 0;
            const shipping = parseFloat($('#shippingInput').val()) || 0;
            
            // Calculate VAT and Tax amounts
            const vatAmount = subtotal * (vatRate / 100);
            const taxAmount = subtotal * (taxRate / 100);
            
            // Calculate total
            const total = subtotal + vatAmount + taxAmount + shipping - discount;
            
            // Format numbers with 3 decimal places
            const formatNumber = num => num.toFixed(3);
            
            // Update displayed values
            $('#subtotal').text(formatNumber(subtotal));
            $('#vatAmount').text(formatNumber(vatAmount));
            $('#taxAmount').text(formatNumber(taxAmount));
            $('#totalAmount').text(formatNumber(total));
            
            // Update hidden inputs
            $('#subtotalValue').val(subtotal.toFixed(3));
            $('#vatAmountValue').val(vatAmount.toFixed(3));
            $('#taxAmountValue').val(taxAmount.toFixed(3));
            $('#totalAmountValue').val(total.toFixed(3));
        }

        // Form validation
        $('#orderForm').submit(function(e) {
            // Check for actual products (rows with data-id attribute)
            const hasProducts = $('#cartItems tr[data-id]').length > 0;
            
            if (!hasProducts) {
                e.preventDefault();
                toastr.error('Please add at least one product to the cart');
                return false;
            }
            
            if (!$('#customerSelect').val()) {
                e.preventDefault();
                toastr.error('Please select a customer');
                return false;
            }
            
            return true;
        });
        // Initial calculation
        calculateTotals();
    });
</script>
@endpush