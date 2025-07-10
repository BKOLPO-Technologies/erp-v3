@extends('Accounts.layouts.admin', ['pageTitle' => 'Receive Payment'])
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
                            <a href="{{ route('accounts.project.receipt.payment.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('accounts.project.receipt.payment.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                {{-- Project --}}
                                <div class="col-md-6 mb-3">
                                    <label for="project_id" class="form-label">Project:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select class="form-control select2" name="project_id" id="project_id">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Sales Invoice No -->
                                {{-- <div class="col-md-6 mb-3">
                                    <label for="invoice_no" class="form-label">Sales Invoice No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                        <select class="form-control select2" name="invoice_no" id="invoice_no">
                                            <option value="">Select Invoice No</option>
                                        </select>
                                    </div>
                                </div> --}}

                                <!-- Total Amount (Display) -->
                                <div class="col-md-6 mb-3">
                                    <label for="total_amount" class="form-label">Total Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="text" name="total_amount" class="form-control" id="total_amount" readonly>
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
                                            <option value="cash" {{ old('payment_method', $payment->payment_method ?? '') === 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="bank" {{ old('payment_method', $payment->payment_method ?? '') === 'bank' ? 'selected' : '' }}>Bank</option>
                                        </select>
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
        $('#client_id').on('change', function () {
            let clientId = $(this).val();
            
            // Clear Out Coming Chalan and Total Amount
            $('#invoice_no').html('<option value="">Select Invoice No</option>');
            $('#total_amount').val(''); // Clear total amount field
            $('#pay_amount').val('');
            $('#due_amount').val('');

            // When chalan is selected, update total amount
            if (clientId) {
                $('#invoice_no').html('<option value="">Loading...</option>'); // Show loading state
                
                let totalAmount = $(this).find(':selected').data('amount') || 0;
                $('#total_amount').val(totalAmount);
                $('#pay_amount').val(''); // Reset pay amount
                $('#due_amount').val(totalAmount); // Default due = total at first

                $.ajax({
                    url: "{{ route('receipt.payment.get.chalans.by.client') }}", // Make sure this route exists
                    type: "GET",
                    data: { client_id: clientId },
                    success: function (response) {
                        //console.log(response);

                        let options = '<option value="">Select Invoice No</option>';

                        // Loop through `response.sales` instead of `response.chalans`
                        response.sales.forEach(sale => {
                            options += `<option value="${sale.invoice_no}" data-amount="${sale.total_amount}">${sale.invoice_no}</option>`;
                        });

                        $('#invoice_no').html(options);
                    }
                });
            }
        });

        // Show Total Amount when Chalan is selected
        $('#invoice_no').on('change', function () {
            let totalAmount = $(this).find(':selected').data('amount') || '';
            $('#total_amount').val(totalAmount);
        });
        
    });

    // When pay amount is entered, calculate due amount
    $('#pay_amount').on('keyup change', function () {
        let totalAmount = parseFloat($('#total_amount').val()) || 0;
        let payAmount = parseFloat($(this).val()) || 0;

        if (payAmount > totalAmount) {
            toastr.error('Pay amount cannot be greater than Total Amount!');
            $(this).val(totalAmount); // Reset pay amount to total amount
            payAmount = totalAmount; // Prevent further calculation issues
        }

        let dueAmount = totalAmount - payAmount;
        $('#due_amount').val(dueAmount.toFixed(2));
    });

    
    
    // ledger group wise change
    $(document).ready(function() {
        $('.select2').select2();

        $('#ledger_group_id').on('change', function() {
            let groupId = $(this).val();
            $('#ledger_id').html('<option value="">Loading...</option>');

            if (groupId) {
                $.ajax({
                    url: "{{ route('accounts.sale.payment.get.ledgers.by.group') }}",
                    type: "GET",
                    data: { ledger_group_id: groupId },
                    success: function(response) {
                        let options = '<option value="">Choose Ledger</option>';
                        response.ledgers.forEach(ledger => {
                            options += `<option value="${ledger.id}">${ledger.name}</option>`;
                        });
                        $('#ledger_id').html(options);
                    }
                });
            } else {
                $('#ledger_id').html('<option value="">Choose Ledger</option>');
            }
        });
    });
</script>
@endpush
