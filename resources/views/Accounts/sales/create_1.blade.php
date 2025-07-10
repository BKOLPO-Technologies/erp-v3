{{-- Create Page with Category, Product wise table format and calculation --}}


@extends('Accounts.layouts.admin', ['pageTitle' => 'Sales'])
@section('admin')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('accounts.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline shadow-lg">
                    <div class="card-header py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                            <a href="{{ route('accounts.sale.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('accounts.sale.store') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="product_ids" id="product_ids">
                            <input type="hidden" name="quantities" id="quantities">
                            <input type="hidden" name="prices" id="prices">
                            <input type="hidden" name="discounts" id="discounts">

                            <div class="row">
                                <!-- Client Select -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="supplier">Client</label>
                                    <div class="input-group">
                                        <select name="client" id="client" class="form-control select2 @error('client') is-invalid @enderror" required>
                                            <option value="" disabled {{ old('client') ? '' : 'selected' }}>Select Client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" 
                                                    data-name="{{ $client->name }}" 
                                                    data-company="{{ $client->company }}" 
                                                    data-phone="{{ $client->phone }}" 
                                                    data-email="{{ $client->email }}"
                                                    {{ old('client') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                           <!-- Button to trigger modal for adding a new client -->
                                            <button class="btn btn-danger" type="button" id="addClientBtn" data-toggle="modal" data-target="#createClientModal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('client')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Project Select with Search Feature -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="project_id">Project</label>
                                    <div class="input-group">
                                        <select name="project_id" id="project_id" class="form-control select2 @error('project_id') is-invalid @enderror" style="width: 100%;">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}">
                                                    {{ $project->project_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('project_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Invoice No -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="invoice_no">Invoice No</label>
                                    <input type="text" id="invoice_no" name="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no', $invoice_no) }}" readonly />
                                    @error('invoice_no')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Customer Details Table -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <table class="table table-bordered" id="client-details-table" style="display: none;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Company Name</th>
                                                <th>Group Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody id="client-details-body"></tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Product Table -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="product-table" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Item Description</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    {{-- <th>Subtotal</th> --}}
                                                    {{-- <th>Discount</th> --}}
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-tbody">
                                                <tr>
                                                    <td style="width:15% !important;">
                                                        <select name="category_id" id="category_id" class="form-control select2 category-select @error('category_id') is-invalid @enderror" style="width: 100%;">
                                                            <option value="all">All Categories</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td style="width:17% !important;">
                                                        <select name="products" id="product" class="form-control select2 @error('products') is-invalid @enderror product-select" style="width: 100%;" required>
                                                            <option value="">Select Product</option>
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}" data-category="{{ $product->category_id }}" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-unit="{{ $product->unit->name }}">
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="unit_price[]" class="form-control unit-price" min="0" required readonly>
                                                    </td>
                                                    <td style="width:15% !important;">
                                                        <input type="number" name="quantity[]" class="form-control quantity" min="1" placeholder="Enter Quantity" required>
                                                    </td>
                                                    <td style="width:7% !important;">
                                                        <input type="text" name="order_unit[]" class="form-control unit-input" required readonly>
                                                    </td>
                                                    {{-- <td style="width:8% !important;">
                                                        <input type="text" name="subtotal[]" class="form-control subtotal"  readonly>
                                                    </td> --}}
                                                    {{-- <td style="width:11% !important;">
                                                        <input type="number" name="discount[]" class="form-control product-discount" placeholder="Enter Discount" step="0.01" min="0">
                                                    </td> --}}
                                                    <td>
                                                        <input type="text" name="total[]" class="form-control total" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-success btn-sm add-row"><i class="fas fa-plus"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end flex-column align-items-end">
                                <!-- First Row: Subtotal and Total Discount -->
                                <div class="row w-100">
                                    <div class="col-12 col-lg-6 mb-2">
                                    </div>
                                    <div class="col-12 col-lg-6 mb-2">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <!-- Subtotal and Discount Row -->
                                                <tr>
                                                    <td><label for="subtotal">Total Amount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="text" id="subtotal" name="subtotal" class="form-control" value="0" readonly />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="total_discount">Discount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="number" id="total_discount" name="total_discount" class="form-control" step="0.01" placeholder="Enter Discount" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="total_netamount">Net Amount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="number" id="total_netamount" name="total_netamount" class="form-control" step="0.01" readonly placeholder="0.00" />
                                                        </div>
                                                    </td>
                                                </tr>
                                
                                                <!-- Include VAT and TAX Checkboxes -->
                                                <tr>
                                                    <td>
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" name="include_tax" id="include_tax">
                                                           <!-- Include TAX -->
                                                            <label for="include_tax" class="me-3">
                                                                Include TAX (%)
                                                                <input type="number" name="tax" id="tax" value="0" min="0"
                                                                    class="form-control form-control-sm d-inline-block"
                                                                    step="0.01" placeholder="Enter TAX"
                                                                    style="width: 70px; margin-left: 10px;" disabled />
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-12 col-lg-12 tax-fields">
                                                            <input type="text" id="tax_amount" name="tax_amount" class="form-control" readonly placeholder="TAX Amount" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" name="include_vat" id="include_vat">
                                                            <label for="include_vat">
                                                                Include VAT (%)
                                                                <input type="number" id="vat" name="vat" value="0" min="0"
                                                                       class="form-control form-control-sm vat-input"
                                                                       step="0.01" placeholder="Enter VAT"
                                                                       style="width: 70px; display: inline-block; margin-left: 10px;" disabled />
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-12 col-lg-12 vat-fields">
                                                            <input type="text" id="vat_amount" name="vat_amount" class="form-control" readonly placeholder="VAT Amount" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <!-- Grand Total Row -->
                                                <tr>
                                                    <td><label for="grand_total">Grand Total</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="text" id="grand_total" name="grand_total" class="form-control" value="0" readonly />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>

                            <!-- Description -->
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter the description"></textarea>
                            </div>
                            <div class="row text-right">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </section>
</div>

<!-- Modal for creating a new client -->
@include('Accounts.inventory.client.client_modal')

@endsection
@push('js')
<script>
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();

        // Supplier selection event
        $('#client').on('change', function () {
            const selectedOption = $(this).find(':selected');
            const clientId = selectedOption.val();
            const supplierName = selectedOption.data('name') || 'N/A';
            const supplierCompany = selectedOption.data('company') || 'N/A';
            const supplierPhone = selectedOption.data('phone') || 'N/A';
            const supplierEmail = selectedOption.data('email') || 'N/A';

            if (clientId) {
                $('#client-details-table').show();
                //$('#client-details-body').empty(); // Clear previous selection

                //const supplierRow = `
                $('#client-details-body').html(`
                    <tr id="supplier-row">
                        <td>${supplierName}</td>
                        <td>${supplierCompany}</td>
                        <td>${supplierPhone}</td>
                        <td>${supplierEmail}</td>
                    </tr>
                `);

                //$('#client-details-body').append(supplierRow);
            } else {
                $('#client-details-table').hide();
            }
        });
        
    
        $('#createClientForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            let formData = $(this).serialize(); // Get form data

            $.ajax({
                url: '{{ route('accounts.client2.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    //console.log(response);
                    // Check if the supplier was created successfully
                    if (response.success) {
                        // Close the modal
                        $('#createClientModal').modal('hide');
                        
                        // Clear form inputs
                        $('#createClientForm')[0].reset();

                        // // Append new supplier to the supplier select dropdown
                        // $('#client').append(new Option(response.client.name, response.client.id));

                        // // Re-initialize the select2 to refresh the dropdown
                        // $('#client').trigger('change');

                        // Create a new option with data attributes
                        let newOption = $('<option>', {
                            value: response.client.id,
                            text: response.client.name,
                            'data-name': response.client.name,
                            'data-company': response.client.company,
                            'data-phone': response.client.phone,
                            'data-email': response.client.email
                        });

                        // Insert new supplier AFTER "Select Vendor" option
                        $('#client option:first').after(newOption);

                        // Select the newly added supplier
                        $('#client').val(response.client.id).trigger('change');

                        // Show success message
                        toastr.success('Client added successfully!');
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function(response) {
                    // Handle error (validation errors, etc.)
                    let errors = response.responseJSON.errors;
                    for (let field in errors) {
                        $(`#new_client_${field}`).addClass('is-invalid');
                        $(`#new_client_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    }
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {

        function addToHiddenFields(productId, quantity, price, discount) {
            let productIds = $('#product_ids').val() ? $('#product_ids').val().split(',') : [];
            let quantities = $('#quantities').val() ? $('#quantities').val().split(',') : [];
            let prices = $('#prices').val() ? $('#prices').val().split(',') : [];
            let discounts = $('#discounts').val() ? $('#discounts').val().split(',') : [];

            let index = productIds.indexOf(productId);

            if (index !== -1) {
                quantities[index] = quantity;
                prices[index] = price;
                discounts[index] = discount;
            } else {
                productIds.push(productId);
                quantities.push(quantity);
                prices.push(price);
                discounts.push(discount);
            }

            $('#product_ids').val(productIds.join(','));
            $('#quantities').val(quantities.join(','));
            $('#prices').val(prices.join(','));
            $('#discounts').val(discounts.join(','));
        }

        function removeFromHiddenFields(productId) {
            let productIds = $('#product_ids').val().split(',');
            let quantities = $('#quantities').val().split(',');
            let prices = $('#prices').val().split(',');
            let discounts = $('#discounts').val().split(',');

            let index = productIds.indexOf(productId);
            if (index !== -1) {
                productIds.splice(index, 1);
                quantities.splice(index, 1);
                prices.splice(index, 1);
                discounts.splice(index, 1);
            }

            $('#product_ids').val(productIds.join(','));
            $('#quantities').val(quantities.join(','));
            $('#prices').val(prices.join(','));
            $('#discounts').val(discounts.join(','));
        }

        function loadProductsByCategory(categoryId, row) {
            var $productSelect = row.find('.product-select');

            if (!categoryId) {
                $productSelect.empty().append('<option value="">Select a category first</option>');
                return;
            }

            $productSelect.empty().append('<option value="">Loading products...</option>');

            $.ajax({
                url: '/admin/product/products-by-category/' + encodeURIComponent(categoryId),
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    $productSelect.empty().append('<option value="">Select Product</option>');
                    if (Array.isArray(response.products) && response.products.length > 0) {
                        response.products.forEach(function (product) {
                            let unitName = product.unit && product.unit.name ? product.unit.name : 'N/A';
                            $productSelect.append(`
                                <option value="${product.id}" 
                                        data-id="${product.id}" 
                                        data-name="${product.name}" 
                                        data-price="${product.price}" 
                                        data-unit="${unitName}">
                                    ${product.name}
                                </option>
                            `);
                        });
                    } else {
                        $productSelect.append('<option value="">No products found</option>');
                    }

                    if (response.category) {
                        let tax = response.category.tax ?? 0;
                        let vat = response.category.vat ?? 0;

                        $('#tax').val(tax);
                        $('#vat').val(vat);
                    } else {
                        $('#tax').val(0);
                        $('#vat').val(0);
                    }

                    $productSelect.trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    $productSelect.empty().append('<option value="">Error fetching products</option>');
                }
            });
        }

        $(document).on('change', '.category-select', function () {
            var categoryId = $(this).val();
            var row = $(this).closest('tr');
            loadProductsByCategory(categoryId, row);
        });

        $(document).on('change', '.product-select', function () {
            let selectedOption = $(this).find(':selected');
            let productId = selectedOption.val();
            let productPrice = selectedOption.data('price') || 0;
            let productUnit = selectedOption.data('unit') || '';

            let isDuplicate = false;
            $('.product-select').not(this).each(function () {
                if ($(this).val() === productId) {
                    isDuplicate = true;
                    return false;
                }
            });

            if (isDuplicate) {
                toastr.error('This product is already added!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000
                });
                $(this).val('').trigger('change');
                return;
            }

            if (productId) {
                let row = $(this).closest('tr');

                row.find('.unit-price').val(productPrice);
                row.find('.quantity').val(1);
                row.find('.subtotal').val(productPrice);
                row.find('.total').val(productPrice);
                row.find('.unit-input').val(productUnit);
                row.find('.product-discount').val(0);

                addToHiddenFields(productId, 1, productPrice, 0);
            }

            calculateTotal();
        });

        $(document).on('input', '.quantity, .product-discount', function () {
            let row = $(this).closest('tr');
            let productId = row.find('.product-select').val();
            let unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
            let quantity = parseFloat(row.find('.quantity').val()) || 1;
            let discount = parseFloat(row.find('.product-discount').val()) || 0;

            let subtotal = unitPrice * quantity;
            let total = subtotal - discount;

            row.find('.subtotal').val(subtotal.toFixed(2));
            row.find('.total').val(total.toFixed(2));

            addToHiddenFields(productId, quantity, unitPrice, discount);

            calculateTotal();
        });

        function calculateTotal() {
            let subtotal = 0;

            $(".total").each(function () {
                subtotal += parseFloat($(this).val()) || 0;
            });

            $("#subtotal").val(subtotal.toFixed(2));
            calculateNetAmount(); // Now integrated
        }

        function calculateNetAmount() {
            let subtotal = parseFloat($('#subtotal').val()) || 0;
            let discount = parseFloat($('#total_discount').val()) || 0;
            let netAmount = subtotal - discount;

            $('#total_netamount').val(netAmount.toFixed(2));

            // TAX
            let taxPercent = parseFloat($('#tax').val()) || 0;
            let taxAmount = $('#include_tax').is(':checked') ? (netAmount * taxPercent / 100) : 0;
            $('#tax_amount').val(taxAmount.toFixed(2));

            // Calculate the sum of net amount and tax amount
            let netAmountWithTax = netAmount + taxAmount;

            // VAT
            let vatPercent = parseFloat($('#vat').val()) || 0;
            let vatAmount = $('#include_vat').is(':checked') ? (netAmountWithTax * vatPercent / 100) : 0;
            $('#vat_amount').val(vatAmount.toFixed(2));

            // Grand total
            let grandTotal = netAmountWithTax + vatAmount;
            $('#grand_total').val(grandTotal.toFixed(2));
        }

        $(document).on("input", "#total_discount, #vat, #tax", function () {
            calculateNetAmount();
        });

        $(document).on('change', '#include_vat', function () {
            $('#vat').prop('disabled', !this.checked);
            calculateNetAmount();
        });

        $(document).on('change', '#include_tax', function () {
            $('#tax').prop('disabled', !this.checked);
            calculateNetAmount();
        });

        $(document).on('click', '.add-row', function () {
            let newRow = `
                <tr>
                    <td>
                        <select name="category_id" class="form-control category-select select2" style="width: 100%;">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="products[]" class="form-control select2 product-select" style="width: 100%;">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-unit="{{ $product->unit->name }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="unit_price[]" class="form-control unit-price" readonly></td>
                    <td><input type="number" name="quantity[]" class="form-control quantity" value="1"></td>
                    <td><input type="text" name="order_unit[]" class="form-control unit-input" readonly></td>
                    <td><input type="text" name="total[]" class="form-control total" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            $('#product-tbody').append(newRow);
            $('.select2').select2();
        });

        $(document).on('click', '.remove-row', function () {
            let row = $(this).closest('tr');
            let productId = row.find('.product-select').val();
            row.remove();
            removeFromHiddenFields(productId);
            calculateTotal();
        });
    });
</script>

@endpush