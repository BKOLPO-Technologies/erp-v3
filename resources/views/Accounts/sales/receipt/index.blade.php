@extends('Accounts.layouts.admin', ['pageTitle' => 'Receive Payment List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
                                    <a href="{{ route('receipt.payment.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Receive
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>Invoice No</th>
                                            <th>Customer Name</th>
                                            {{-- <th>Total Amount</th> --}}
                                            <th>Pay Amount</th>
                                            {{-- <th>Due Amount</th> --}}
                                            <th>Payment Method</th>  
                                            <th>Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($receipts as $key => $receipt)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                {{-- <td>{{ $receipt->outcomingChalan->sale->invoice_no ?? 'N/A' }}</td> --}}
                                                <td>{{ $receipt->invoice_no ?? 'N/A' }}</td>
                                                <td>{{ $receipt->client->name ?? 'N/A' }}</td>
                                                {{-- <td>{{ number_format($payment->total_amount, 2) }}</td> --}}
                                                <td>{{ number_format($receipt->pay_amount, 2) }}</td>
                                                {{-- <td>{{ number_format($payment->total_amount-$payment->pay_amount, 2) }}</td> --}}
                                                <!-- Payment Method -->
                                                <td>
                                                    @if($receipt->payment_method == 'cash')
                                                        Cash
                                                    @elseif($receipt->payment_method == 'bank')
                                                        Bank
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $receipt->payment_date }}</td>
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
