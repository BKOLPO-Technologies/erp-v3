@extends('Accounts.layouts.admin', ['pageTitle' => 'Sales List'])
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
                                    <a href="{{ route('accounts.sale.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Invoice
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Invoice No</th>
                                            {{-- <th>Reference No</th> --}}
                                            <th>Client Name</th>
                                            <th>Date</th>
                                            <th>Total Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Due Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sales as $sale)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $sale->invoice_no }}</td>
                                                    {{-- <td>{{ $sale->project->reference_no ?? '' }}</td> --}}
                                                    <td>{{ $sale->client->name ?? 'N/A' }}</td> 
                                                    <td>{{ \Carbon\Carbon::parse($sale->invoice_date)->format('d F Y') }}</td>
                                                    <td>{{ bdt() }} {{ number_format($sale->grand_total, 2) }}</td> 
                                                    <td>{{ bdt() }} {{ number_format($sale->paid_amount ?? 'N/A', 2) }}</td> 
                                                    <td>{{ bdt() }} {{ number_format($sale->grand_total-$sale->paid_amount, 2) }}</td> 
                                                    <!-- Status column with Badge -->
                                                    <td>
                                                        @if($sale->paid_amount >= $sale->grand_total)
                                                            <span class="badge bg-success">Paid</span>
                                                        @elseif($sale->paid_amount > 0)
                                                            <span class="badge bg-warning">Partially Paid</span>
                                                        @else
                                                            <span class="badge bg-danger">Pending</span>
                                                        @endif
                                                    </td>
                                            
                                                    <td class="col-2">
                                                        <!-- View Button -->
                                                        <a href="{{ route('accounts.sale.show', $sale->id) }}" class="btn btn-success btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <!-- Edit Button -->
                                                        <a href="{{ route('accounts.sale.edit', $sale->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <!-- Delete Button -->
                                                        <a href="{{ route('accounts.sale.destroy', $sale->id) }}" id="delete" class="btn btn-danger btn-sm">
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
