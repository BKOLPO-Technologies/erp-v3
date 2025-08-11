@extends('Accounts.layouts.admin', ['pageTitle' => 'Payment'])
@section('admin')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? ''}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('accounts.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? ''}}</li>
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
                            <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                            <a href="{{ route('accounts.sale.payment.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('accounts.sale.payment.store') }}" method="POST">
                            @csrf
                            <div class="row">
                               
                                <!-- Client Selection -->
                                <div class="col-md-6 mb-3">
                                    <label for="supplier_id" class="form-label">Vendor:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select class="form-control select2" name="supplier_id" id="supplier_id">
                                            <option value="">Select Vendor</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Purchase Invoice No -->
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_no" class="form-label">PO No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                        <select class="form-control select2" name="invoice_no" id="invoice_no">
                                            <option value="">Select PO No</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Total Amount -->
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Total Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="number" class="form-control" id="total_amount" readonly>
                                    </div>
                                </div>

                                <!-- Total Due Amount (Display) -->
                                <div class="col-md-6 mb-3">
                                    <label for="total_amount" class="form-label">Total Due Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="text" name="total_amount" class="form-control" id="total_due_amount" readonly>
                                    </div>
                                </div>

                                <!-- Pay Amount -->
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Pay Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="number" class="form-control" id="pay_amount" name="pay_amount"  step="0.01" required>
                                    </div>
                                </div>

                                <!-- Due Amount (Automatically Calculated) -->
                                <div class="col-md-6 mb-3">
                                    <label for="due_amount" class="form-label">Due Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                                        <input type="number" class="form-control" id="due_amount" name="due_amount" step="0.01" readonly>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-md-6 mb-3">
                                    <label for="ledger_group_id" class="form-label">Select Payment Method:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <select class="form-control" name="payment_method" id="payment_method" required>
                                            <option value="">Choose Payment Method</option>
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->id }}" data-type="{{ $ledger->type }}">{{ $ledger->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Payment Mood -->
                                <div class="col-md-6 mb-3">
                                    <label for="ledger_group_id" class="form-label">Select Payment Mood:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <select class="form-control" name="payment_mood" id="payment_mood">
                                            <option value="">Choose Payment Mood</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cheque">Cheque Payment</option>
                                            <option value="bkash">Bkash</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Bank Account Number (hidden initially) -->
                                <div class="col-md-6 mb-3" id="bank_account_div" style="display:none;">
                                    <label for="bank_account_no" class="form-label">Bank Account No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <input type="text" class="form-control" name="bank_account_no" placeholder="Enter Bank Account No" id="bank_account_no">
                                    </div>
                                </div>

                               <!-- Batch Number (hidden initially) -->
                                <div class="col-md-6 mb-3" id="bank_batch_div" style="display:none;">
                                    <label for="bank_batch_no" class="form-label">Batch No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <input type="text" class="form-control" name="bank_batch_no" placeholder="Enter Batch No" id="bank_batch_no">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3" id="bank_date_div" style="display:none;">
                                    <label for="bank_date" class="form-label">Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" name="bank_date" id="bksh_date">
                                    </div>
                                </div>


                                <!-- Cheque Number (hidden initially) -->
                                <div class="col-md-6 mb-3" id="cheque_no_div" style="display:none;">
                                    <label for="cheque_no" class="form-label">Cheque No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check"></i></span>
                                        <input type="text" class="form-control" name="cheque_no" placeholder="Enter Cheque No" id="cheque_no">
                                    </div>
                                </div>

                                <!-- Cheque Date (hidden initially) -->
                                <div class="col-md-6 mb-3" id="cheque_date_div" style="display:none;">
                                    <label for="cheque_date" class="form-label">Cheque Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" name="cheque_date" id="to_date">
                                    </div>
                                </div>

                                <!-- Bkash Number -->
                                <div class="col-md-6 mb-3" id="bkash_number_div" style="display:none;">
                                    <label for="bkash_number" class="form-label">Bkash Number:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                        <input type="text" class="form-control" name="bkash_number" placeholder="Enter Bkash Number" id="bkash_number">
                                    </div>
                                </div>

                                <!-- Reference Number -->
                                <div class="col-md-6 mb-3" id="reference_no_div" style="display:none;">
                                    <label for="reference_no" class="form-label">Reference Number:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                        <input type="text" class="form-control" name="reference_no" placeholder="Enter Reference Number" id="reference_no">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3" id="bkash_date_div" style="display:none;">
                                    <label for="bkash_date_div" class="form-label">Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" name="bkash_date" id="from_date">
                                    </div>
                                </div>

                                <!-- Payment Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="payment_date" class="form-label">Payment Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" id="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>


                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label for="description">Note</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter some note"></textarea>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- Submit Button (Right-Aligned) -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>

                    <hr/>

                    {{--  --}}
                    <!-- Section to Display Purchase Details -->
                    <div class="card-body" id="purchase-details" class="mt-4">
                        
                        <!-- Invoice No and Supplier (Moved to top) -->
                        <p><strong>PO No:</strong> <span id="invoice_no_display"></span></p>
                        <p><strong>Supplier:</strong> <span id="supplier_display"></span></p>

                        <hr/>

                        <h5>Purchase Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="purchase-products"></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="1" class="text-end">Total:</th>
                                        <th id="total_quantity">0</th>
                                        <th></th>
                                        <th id="total_purchase_amount">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <hr/>

                        <h5>Payment Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Payment Date</th>
                                    <th>Payment Method</th>
                                    <th>Bank Account No</th>
                                    <th>Cheque No</th>
                                    <th>Pay Amount</th>
                                </tr>
                                <tbody id="payment-details"></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total Paid:</th>
                                        <th id="total_paid_amount">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <hr/>
                        <!-- Grand Total -->
                        <div class="d-flex justify-content-end1">
                            <div class="text-end1">
                                <h5>Grand Total</h5>
                                <p><strong>Total Purchase Amount:</strong> <span id="grand_total_purchase">0.00</span></p>
                                <p><strong>Total Paid Amount:</strong> <span id="grand_total_paid">0.00</span></p>
                                <p><strong>Due Amount:</strong> <span id="grand_due_amount">0.00</span></p>
                            </div>
                        </div>
                        
                    </div>
                    {{--  --}}
                </div>
            </div> 
        </div>
    </section>
</div>

@endsection



@push('js')
<script>
   $(document).ready(function () {
        // When customer changes, reset fields
        $('#supplier_id').on('change', function () {
            let supplierId = $(this).val();
            //alert(supplierId);
            
            // Clear Incoming Chalan and Total Amount
            $('#invoice_no').html('<option value="">Select PO No</option>');
            $('#total_amount').val(''); // Clear total amount field
            $('#total_due_amount').val(''); 
            $('#pay_amount').val('');
            $('#due_amount').val('');

            // When chalan is selected, update total amount
            if (supplierId) {
                $('#invoice_no').html('<option value="">Loading...</option>'); // Show loading state
                
                let totalAmount = $(this).find(':selected').data('amount') || 0;
                $('#total_amount').val(totalAmount);
                $('#total_due_amount').val(totalAmount);
                $('#pay_amount').val(''); // Reset pay amount
                $('#due_amount').val(totalAmount); // Default due = total at first

                $.ajax({
                    url: "{{ route('accounts.sale.payment.get.chalans.by.supplier') }}", // Make sure this route exists
                    type: "GET",
                    data: { supplier_id: supplierId },
                    success: function (response) {
                        console.log(response); // Log the response to the console

                        let options = '<option value="">Select PO No</option>';

                        response.purchases.forEach(purchase => {
                            options += `<option value="${purchase.invoice_no}" data-amount="${purchase.total_amount}" data-due="${purchase.total_due_amount}">${purchase.invoice_no}</option>`;
                        });

                        $('#invoice_no').html(options);
                    }
                });
            }
        });

        // Show Total Amount when Chalan is selected
        $('#invoice_no').on('change', function () {
            // let totalDueAmount = $(this).find(':selected').data('amount') || '';
            // $('#total_due_amount').val(totalDueAmount);
            let selectedOption = $(this).find(':selected');
        
            let totalAmount = selectedOption.data('amount') || 0;
            let totalDueAmount = selectedOption.data('due') || 0;

            // console.log("Total Amount:", totalAmount); // Debugging
            // console.log("Total Due Amount:", totalDueAmount); // Debugging

            $('#total_amount').val(totalAmount);
            $('#total_due_amount').val(totalDueAmount);

            let invoiceId = $(this).val();
            if (invoiceId) {
                $.ajax({
                    url: "{{ route('accounts.sale.payment.get.purchase.details') }}",
                    type: "GET",
                    data: { invoice_id: invoiceId },
                    success: function (response) {
                        //console.log(response);

                        if (response.success) {
                            let products = response.purchase.items;
                            let purchase = response.purchase;
                            // console.log(purchase);
                            let payments = response.payments;

                            let vat = parseFloat(response.purchase.vat_amount) || 0;
                            let tax = parseFloat(response.purchase.tax_amount) || 0;
                            let transportCost = parseFloat(response.purchase.transport_cost) || 0;
                            let carryingCharge = parseFloat(response.purchase.carrying_charge) || 0;
                            let discount = parseFloat(response.purchase.discount) || 0;

                            let vatTax = (vat + tax + transportCost + carryingCharge - discount).toFixed(2);
                            vatTax = parseFloat(vatTax);

                            //console.log(vatTax);


                            // let vatTax = ((response.purchase.vat) + (response.purchase.tax) + (response.purchase.transport_cost) + (response.purchase.carrying_charge) - (response.purchase.discount));

                            // console.log(response.purchase.vat);
                            // console.log(response.purchase.tax);
                            // console.log(response.purchase.transport_cost);
                            // console.log(response.purchase.carrying_charge);
                            // console.log(response.purchase.discount);
                            // console.log(vatTax);

                            // vatTax = parseFloat(vatTax.toFixed(2));
                            //console.log(vatTax);

                            // Clear previous data
                            $('#purchase-products').empty();
                            $('#payment-details').empty();

                            // Set Invoice No and Supplier Name at the top
                            // $('#invoice_no_display').text(payments.length > 0 ? payments[0].invoice_no : 'N/A');
                            // $('#supplier_display').text(payments.length > 0 ? (payments[0].supplier?.name || 'N/A') : 'N/A');
                            $('#invoice_no_display').text(purchase.invoice_no || 'N/A');
                            $('#supplier_display').text(purchase.supplier?.name || 'N/A');

                            // Variables for totals
                            let totalQuantity = 0;
                            let totalPurchaseAmount = 0;
                            let totalPaidAmount = 0;

                            // Populate purchase products
                            products.forEach(product => {

                                let total = ( (product.quantity * product.price) - product.discount );
                                totalQuantity += product.quantity;
                                totalPurchaseAmount += total;
                                
                                $('#purchase-products').append(`
                                    <tr>
                                        <td>${product.product?.name || 'N/A'}</td>
                                        <td>${product.quantity}</td>
                                        <td>${product.price}</td>
                                        <td>${product.quantity * product.price}</td>
                                    </tr>
                                `);
                            });

                            finalTotalPurchaseAmount = totalPurchaseAmount + vatTax;
                            //console.log(finalTotalPurchaseAmount);

                            // Populate payment details
                            payments.forEach(payment => {

                                totalPaidAmount += parseFloat(payment.pay_amount || 0);

                                $('#payment-details').append(`
                                    <tr>
                                        <td>${payment.payment_date}</td>
                                        <td>${payment.payment_method}</td>
                                        <td>${payment?.bank_account_no || ''}</td>
                                        <td>${payment?.cheque_no || ''}</td>
                                        <td>${payment.pay_amount}</td>
                                    </tr>
                                `);
                            });

                            // Update Totals
                            $('#total_quantity').text(totalQuantity);
                            $('#total_purchase_amount').text(finalTotalPurchaseAmount.toFixed(2));
                            $('#total_paid_amount').text(totalPaidAmount.toFixed(2));

                            // Update Grand Totals
                            $('#grand_total_purchase').text(finalTotalPurchaseAmount.toFixed(2));
                            $('#grand_total_paid').text(totalPaidAmount.toFixed(2));
                            $('#grand_due_amount').text((finalTotalPurchaseAmount - totalPaidAmount).toFixed(2));
                        } else {
                            alert(response.message || 'No data found for this invoice.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", xhr.responseText);
                        alert('Error fetching data.');
                    }
                });
            } else {
                $('#purchase-products').empty();
                $('#payment-method').text('');

                 // Reset totals
                $('#total_quantity').text(0);
                $('#total_purchase_amount').text('0.00');
                $('#total_paid_amount').text('0.00');
                $('#grand_total_purchase').text('0.00');
                $('#grand_total_paid').text('0.00');
                $('#grand_due_amount').text('0.00');

                $('#invoice_no_display').text('N/A');
                $('#supplier_display').text('N/A');
            }
        });

        // // When customer changes, reset fields
        // $('#supplier_id').on('change', function () {
        //     let supplierId = $(this).val();
        //     // alert(supplierId);
            
        //     // Clear Incoming Chalan and Total Amount
        //     $('#incoming_chalan_id').html('<option value="">Select Chalan</option>');
        //     $('#total_amount').val(''); // Clear total amount field
        //     $('#pay_amount').val('');
        //     $('#due_amount').val('');

        //     // When chalan is selected, update total amount
        //     if (supplierId) {
        //         $('#incoming_chalan_id').html('<option value="">Loading...</option>'); // Show loading state
                
        //         let totalAmount = $(this).find(':selected').data('amount') || 0;
        //         $('#total_amount').val(totalAmount);
        //         $('#pay_amount').val(''); // Reset pay amount
        //         $('#due_amount').val(totalAmount); // Default due = total at first

        //         $.ajax({
        //             url: "{{ route('accounts.sale.payment.get.chalans.by.supplier') }}", // Make sure this route exists
        //             type: "GET",
        //             data: { supplier_id: supplierId },
        //             success: function (response) {
        //                 console.log(response); // Log the response to the console

        //                 let options = '<option value="">Select Chalan</option>';
        //                 response.chalans.forEach(chalan => {
        //                     options += `<option value="${chalan.id}" data-amount="${chalan.total_amount}">${chalan.invoice_no}</option>`;
        //                 });
        //                 $('#incoming_chalan_id').html(options);
        //             }
        //         });
        //     }
        // });

        // // Show Total Amount when Chalan is selected
        // $('#incoming_chalan_id').on('change', function () {
        //     let selectedOption = $(this).find(':selected'); // Get the selected option
        //     let totalAmount = $(this).find(':selected').data('amount') || '';
        //     let invoiceNo = selectedOption.text(); // Get invoice number from option text
        //     $('#total_amount').val(totalAmount);
        //     $('#invoice_no').val(invoiceNo); // Store invoice_no in a hidden field
        // });
    });

    // When pay amount is entered, calculate due amount
    $('#pay_amount').on('keyup change', function () {
        let totalDueAmount = parseFloat($('#total_due_amount').val()) || 0;
        let payAmount = parseFloat($(this).val()) || 0;

        if (payAmount > totalDueAmount) {
            toastr.error('Pay amount cannot be greater than Total Amount!');
            $(this).val(totalDueAmount); // Reset pay amount to total amount
            payAmount = totalDueAmount; // Prevent further calculation issues
        }

        let dueAmount = totalDueAmount - payAmount;
        $('#due_amount').val(dueAmount.toFixed(2));
    });

    // // ledger group wise change
    // $(document).ready(function() {
    //     $('.select2').select2();

    //     $('#ledger_group_id').on('change', function() {
    //         let groupId = $(this).val();
    //         $('#ledger_id').html('<option value="">Loading...</option>');

    //         if (groupId) {
    //             $.ajax({
    //                 url: "{{ route('accounts.sale.payment.get.ledgers.by.group') }}",
    //                 type: "GET",
    //                 data: { ledger_group_id: groupId },
    //                 success: function(response) {
    //                     let options = '<option value="">Choose Ledger</option>';
    //                     response.ledgers.forEach(ledger => {
    //                         options += `<option value="${ledger.id}">${ledger.name}</option>`;
    //                     });
    //                     $('#ledger_id').html(options);
    //                 }
    //             });
    //         } else {
    //             $('#ledger_id').html('<option value="">Choose Ledger</option>');
    //         }
    //     });
    // });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const paymentMethodSelect = document.getElementById('payment_method');
        const paymentMoodSelect = document.getElementById('payment_mood');

        const bankAccountDiv = document.getElementById('bank_account_div');
        const bankBatchDiv = document.getElementById('bank_batch_div');
        const chequeNoDiv = document.getElementById('cheque_no_div');
        const chequeDateDiv = document.getElementById('cheque_date_div');
        const bankDateDiv = document.getElementById('bank_date_div');
        const bkashNumberDiv = document.getElementById('bkash_number_div');
        const referenceNoDiv = document.getElementById('reference_no_div');
        const bkashDateDiv = document.getElementById('bkash_date_div');

        // Hide payment mood initially
        paymentMoodSelect.closest('.mb-3').style.display = 'none';

        // Handle Payment Method Change
        paymentMethodSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const paymentType = selectedOption.getAttribute('data-type');

            if (paymentType === 'Bank') {
                paymentMoodSelect.closest('.mb-3').style.display = 'block';
            } else {
                paymentMoodSelect.closest('.mb-3').style.display = 'none';
                hideAllPaymentMoodFields();
            }
        });

        // Handle Payment Mood Change
        paymentMoodSelect.addEventListener('change', function () {
            hideAllPaymentMoodFields();

            const mood = this.value;
            switch (mood) {
                case 'bank_transfer':
                    bankAccountDiv.style.display = 'block';
                    bankBatchDiv.style.display = 'block';
                    bankDateDiv.style.display = 'block';
                    break;
                case 'cheque':
                    bankAccountDiv.style.display = 'block';
                    chequeNoDiv.style.display = 'block';
                    chequeDateDiv.style.display = 'block';
                    break;
                case 'bkash':
                    bkashNumberDiv.style.display = 'block';
                    referenceNoDiv.style.display = 'block';
                    bkashDateDiv.style.display = 'block';
                    break;
            }
        });

        // Function to Hide All Optional Fields
        function hideAllPaymentMoodFields() {
            bankAccountDiv.style.display = 'none';
            bankBatchDiv.style.display = 'none';
            chequeNoDiv.style.display = 'none';
            chequeDateDiv.style.display = 'none';
            bankDateDiv.style.display = 'none';
            bkashDateDiv.style.display = 'none';
            bkashNumberDiv.style.display = 'none';
            referenceNoDiv.style.display = 'none';
        }
    });
</script>

    
@endpush
