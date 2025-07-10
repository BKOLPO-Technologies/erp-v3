@extends('Accounts.layouts.admin', ['pageTitle' => 'Journal Voucher Create'])
@section('admin')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                  </ol>
                </div>
              </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">

                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    <a href="{{ route('accounts.journal-voucher.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('accounts.journal-voucher.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Transaction Code (Auto Generated) -->
                                        <div class="col-lg-4 mb-3">
                                            <label for="transaction_code">Voucher No</label>
                                            <input type="text" id="transaction_code" name="transaction_code" class="form-control @error('transaction_code') is-invalid @enderror" value="{{ old('transaction_code', $transactionCode) }}" readonly />
                                            @error('transaction_code')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Company Select -->
                                        <div class="col-lg-4 mb-3">
                                            <label for="company_id">Company</label>
                                            <select name="company_id" id="company_id" class="form-control select2 @error('company_id') is-invalid @enderror">
                                                <option value="">Select Company</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('company_id')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Branch Select -->
                                        <div class="col-lg-4 mb-3">
                                            <label for="branch_id">Branch</label>
                                            <select name="branch_id" id="branch_id" class="form-control @error('branch_id') is-invalid @enderror">
                                                <option value="">Select Branch</option>
                                            </select>
                                            @error('branch_id')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="row">
                                        <!-- Date Input -->
                                        <div class="col-lg-12 mb-3">
                                            <label for="transaction_date">Date</label>
                                            <input type="text" id="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" />
                                            @error('transaction_date')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row p-1">
                                        <div class="table-responsive">
                                            <table class="table table-bordered border-secondary">
                                                <thead class="table-light">
                                                    <tr style="background:#dcdcdc; text-align:center;">
                                                        <th>Dr/Cr</th>
                                                        <th>Account</th>
                                                        <th>Reference No</th>
                                                        <th>Description</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                        <th>Add</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="transactionTableBody">
                                                    <!-- Debit Section -->
                                                    <tbody id="debitSection">
                                                        <tr>
                                                            <td><input type="text" class="form-control transaction-type" name="transaction_type[]" value="Debit" readonly></td>
                                                            <td>
                                                                <select class="form-control" name="ledger_id[]">
                                                                    <option value="">Select Account</option>
                                                                    @foreach($ledgers as $ledger)
                                                                        <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control" name="reference_no[]" placeholder="Enter Reference No"></td>
                                                            <td><textarea class="form-control" name="description[]" rows="1" placeholder="Enter Description"></textarea></td>
                                                            <td>
                                                                <input type="number" class="form-control text-end debit" name="debit[]" placeholder="Enter Debit Amount">
                                                            </td>
                                                            <td>
                                                                <input type="hidden" class="form-control text-end debit" name="credit[]" value="0">
                                                            </td>

                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-success add-row add-debit-row"><i class="fa fa-plus"></i></button>
                                                                <button type="button" class="btn btn-sm btn-danger remove-row remove-debit-row" style="display:none;"><i class="fa fa-minus"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                
                                                    <!-- Credit Section -->
                                                    <tbody id="creditSection">
                                                        <tr>
                                                            <td><input type="text" class="form-control transaction-type" name="transaction_type[]" value="Credit" readonly></td>
                                                            <td>
                                                                <select class="form-control" name="ledger_id[]">
                                                                    <option value="">Select Account</option>
                                                                    @foreach($ledgers as $ledger)
                                                                        <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control" name="reference_no[]" placeholder="Enter Reference No"></td>
                                                            <td><textarea class="form-control" name="description[]" rows="1" placeholder="Enter Description"></textarea></td>
                                                            <td>
                                                                <input type="hidden" class="form-control text-end debit" name="debit[]" value="0">
                                                            </td>
                                                            <td><input type="number" class="form-control text-end credit" name="credit[]" placeholder="Enter Credit Amount"></td>

                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-success add-row add-credit-row"><i class="fa fa-plus"></i></button>
                                                                <button type="button" class="btn btn-sm btn-danger remove-row remove-credit-row" style="display:none;"><i class="fa fa-minus"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </tbody>
                                                
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" style="text-align: right; padding-right: 1rem;"><strong>Total:</strong></td>
                                                        <td style="text-align: right;"><strong id="debitTotal">৳0.00</strong></td>
                                                        <td style="text-align: right;"><strong id="creditTotal">৳0.00</strong></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-save"></i> Save Journal Voucher
                                            </button>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
<script>

    // company to branch show
    $(document).ready(function () {
        $('#company_id').on('change', function () {
            let companyId = $(this).val();
            let branchSelect = $('#branch_id');

            // Clear previous options
            branchSelect.empty();
            branchSelect.append('<option value="">Select Branch</option>');

            if (companyId) {
                $.ajax({
                    url: '/admin/journal-voucher/get-branches/' + companyId, // Backend route
                    type: 'GET',
                    success: function (response) {
                        //console.log(response); // Debug: Check the response in the console
                        
                        // Clear existing options in the branch dropdown
                        branchSelect.empty();
                        branchSelect.append('<option value="">Select Branch</option>');

                        // Check if the response is successful
                        if (response.success) {
                            const branch = response.branch; // Single branch
                            branchSelect.append(
                                `<option value="${branch.id}">${branch.name}</option>`
                            );
                            // toastr.success('Branch loaded successfully!');
                        } else {
                            toastr.warning(response.message || 'No branch found for this company.');
                        }
                    },
                    error: function () {
                        toastr.error('Error retrieving branches. Please try again.');
                    },
                });
            } else {
                // Clear branch dropdown if no company is selected
                branchSelect.empty();
                branchSelect.append('<option value="">Select Branch</option>');
                toastr.info('Please select a company to load branches.');
            }
        });
    });

    $(document).ready(function () {

        function updateRowControls(sectionClass) {
            let rows = $(sectionClass + " tr");

            // Hide all + buttons except for the last row
            rows.find(".add-row").hide();
            rows.last().find(".add-row").show();

            // Hide all - buttons except for the last row (if multiple rows exist)
            rows.find(".remove-row").hide();
            if (rows.length > 1) {
                rows.last().find(".remove-row").show();
            }
        }

        function addNewRow(sectionClass, transactionType) {
            let lastRow = $(sectionClass + " tr").last();
            let newRow = lastRow.clone();

            // Clear input values for the new row
            newRow.find("input[type='text'], input[type='number'], textarea").val("");

            // Ensure the transaction type remains the same
            newRow.find(".transaction-type").val(transactionType);

            // Append the new row
            lastRow.after(newRow);

            // Update button visibility
            updateRowControls(sectionClass);
        }

        function removeRow(sectionClass) {
            let rows = $(sectionClass + " tr");

            // Only allow deleting the last row if there are at least two rows
            if (rows.length > 1) {
                rows.last().remove();
                updateRowControls(sectionClass);
                calculateTotals();
            }
        }

        // Auto-fill first Credit row based on first Debit row input
        $(document).on("input", "#debitSection tr:first .debit", function () {
            let debitValue = $(this).val();
            $("#creditSection tr:first .credit").val(debitValue);
            calculateTotals();
        });

        // Add debit row
        $(document).on("click", ".add-debit-row", function () {
            addNewRow("#debitSection", "Debit");
        });

        // Add credit row
        $(document).on("click", ".add-credit-row", function () {
            addNewRow("#creditSection", "Credit");
        });

        // Remove only the last row
        $(document).on("click", ".remove-debit-row", function () {
            removeRow("#debitSection");
        });

        $(document).on("click", ".remove-credit-row", function () {
            removeRow("#creditSection");
        });

        function calculateTotals() {
            let totalDebit = 0, totalCredit = 0;

            $(".debit").each(function () {
                totalDebit += parseFloat($(this).val()) || 0;
            });

            $(".credit").each(function () {
                totalCredit += parseFloat($(this).val()) || 0;
            });

            $("#debitTotal").text(formatCurrency(totalDebit));
            $("#creditTotal").text(formatCurrency(totalCredit));
        }

        function formatCurrency(amount) {
            return '৳' + new Intl.NumberFormat('en-BD', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            }).format(amount);
        }

        $(document).on("keyup", ".debit, .credit", function () {
            calculateTotals();
        });

        // Initial update to set button visibility
        updateRowControls("#debitSection");
        updateRowControls("#creditSection");

    });

</script>
@endpush