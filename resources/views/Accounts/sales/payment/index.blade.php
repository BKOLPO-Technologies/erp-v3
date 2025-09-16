@extends('Accounts.layouts.admin', ['pageTitle' => 'Payment List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('Accounts/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? ''}}</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? ''}}</li>
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
                                    <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                    <a href="{{ route('accounts.sale.payment.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Payment
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>PO No</th>
                                            <th>Vendor</th>
                                            {{-- <th>Total Amount</th> --}}
                                            <th>Pay Amount</th>
                                            {{-- <th>Due Amount</th> --}}
                                            <th>Payment Method</th>  
                                            <th>Payment Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $key => $payment)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                {{-- <td>{{ $payment->incomingChalan->purchase->invoice_no ?? 'N/A' }}</td> --}}
                                                <td>{{ $payment->invoice_no ?? '' }}</td>
                                                <td>{{ $payment->supplier->name ?? '' }}</td>
                                                {{-- <td>{{ number_format($payment->total_amount, 2) }}</td> --}}
                                                <td>{{ number_format($payment->pay_amount, 2) }}</td>
                                                {{-- <td>{{ number_format($payment->total_amount-$payment->pay_amount, 2) }}</td> --}}
                                                <!-- Payment Method -->
                                                <td>
                                                    @if($payment->payment_method == 'cash')
                                                        Cash
                                                    @elseif($payment->payment_method == 'bank')
                                                        Bank
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $payment->payment_date }}</td>
                                                {{-- <td>
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('accounts.sale.payment.destroy', $payment->id) }}" id="delete" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td> --}}
                                                <td>
                                                    <a href="{{ route('accounts.sale.payment.show', ['invoice_no' => $payment->invoice_no]) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('accounts.sale.payment.edit', $payment->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('accounts.sale.payment.destroy', $payment->id) }}" id="delete" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
