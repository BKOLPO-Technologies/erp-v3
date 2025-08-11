@extends('Accounts.layouts.admin', ['pageTitle' => 'Receive Payment List'])
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
                                    <a href="{{ route('accounts.sale.payment.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Project Name</th>
                                            <td>{{ $project->project_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td>{{ $project->project_location }}</td>
                                        </tr>
                                        <tr>
                                            <th>Coordinator</th>
                                            <td>{{ $project->project_coordinator }}</td>
                                        </tr>
                                        <tr>
                                            <th>Customer</th>
                                            <td>{{ $project->client->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Reference No</th>
                                            <td>{{ $project->reference_no }}</td>
                                        </tr>
                                        <tr>
                                            <th>Schedule Date</th>
                                            <td>{{ $project->schedule_date ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Project Type</th>
                                            <td>{{ ucfirst($project->project_type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $project->description ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terms & Conditions</th>
                                            <td>{!! $project->terms_conditions ?? '' !!}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($project->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($project->status == 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-info text-dark">Partially Paid</span>
                                                @endif
                                            </td>                                        
                                        </tr> --}}
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="card-header">
                                    {{-- <h4>Project Items</h4> --}}
                                </div>
                                <div class="card-body">
                                    {{-- <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Item Name</th>
                                                <th>Order Unit</th>
                                                <th>Unit Price</th>
                                                <th>Quantity</th>
                                                <th>Subtotal</th>
                                                <th>Discount</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $subtotal = 0;
                                                $grandTotal = 0;
                                            @endphp
                                            @foreach ($project->items as $index => $item)
                                                @php
                                                $productTotal = ($item->unit_price * $item->quantity) - $item->discount;
                                                $subtotal += $productTotal;
                                                $grandTotal = $subtotal - $project->total_discount + $project->transport_cost + $project->carrying_charge + $project->vat + $project->tax;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->items }}</td>
                                                    <td>{{ $item->unit->name ?? '' }}</td>
                                                    <td>{{ bdt() }} {{ number_format($item->unit_price, 2) }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ bdt() }} {{ number_format($item->subtotal, 2) }}</td>
                                                    <td>{{ bdt() }} {{ number_format($item->discount, 2) }}</td>
                                                    <td>{{ bdt() }} {{ number_format($item->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table> --}}
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Unit Price</th>
                                                    <th>Quantity</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php 
                                                    $total = 0; 
                                                @endphp
                            
                                                @foreach ($purchase->products as $product)
                                                    @php
                                                        $subtotal = (($product->pivot->price * $product->pivot->quantity) - $product->pivot->discount);
                                                        $total += $subtotal;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $product->name }}</td>
                                                        <td>{{ number_format($product->pivot->price, 2) }}</td>
                                                        <td>{{ $product->pivot->quantity }} ({{ $product->unit->name }})</td>
                                                        <td>{{ number_format($subtotal, 2) }}</td>
                                                    </tr>
                                                @endforeach
                            
                                                @php
                                                    $transportCost = $purchase->transport_cost ?? 0;
                                                    $carryingCharge = $purchase->carrying_charge ?? 0;
                                                    $vat = $purchase->vat_amount ?? 0;
                                                    $tax = $purchase->tax_amount ?? 0;
                                                    $totalDiscount = $purchase->discount ?? 0;
                            
                                                    $totalVatTax = ($transportCost + $carryingCharge + $vat + $tax) - $totalDiscount;
                                                    $totalTotal = $total + $totalVatTax;
                                                @endphp
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-right">Total:</th>
                                                    <th>{{ number_format($total, 2) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">VAT:</th>
                                                    <th>{{ number_format($vat, 2) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">TAX:</th>
                                                    <th>{{ number_format($tax, 2) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">Discount:</th>
                                                    <th>-{{ number_format($totalDiscount, 2) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">Grand Total:</th>
                                                    <th>{{ number_format($totalTotal, 2) }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>


                            <div class="mt-4">
                                <div class="card-header">
                                    <h4>Payment History</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Reference No</th>
                                                    <th>Total Amount</th>
                                                    <th>Pay Amount</th>
                                                    <th>Due Amount</th>
                                                    <th>Payment Method</th>
                                                    <th>Payment Date</th>
                                                    <!-- Additional columns for Bank payment -->
                                                    <th>Bank Account No</th>
                                                    <th>Cheque No</th>
                                                    <th>Cheque Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalPaid = 0;
                                                @endphp
                                                @foreach ($project_receipts as $index => $receipt)
                                                    @php
                                                        $pay_amount = $receipt->pay_amount;
                                                        $totalPaid += $pay_amount;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $receipt->invoice_no }}</td>
                                                        <td>{{ bdt() }} {{ number_format($receipt->total_amount, 2) }}</td>
                                                        <td>{{ bdt() }} {{ number_format($receipt->pay_amount, 2) }}</td>
                                                        <td>{{ bdt() }} {{ number_format($receipt->due_amount, 2) }}</td>                                                    
                                                        <td>{{ ucfirst($receipt->payment_method) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($receipt->payment_date)->format('d F Y') }}</td>
                                
                                                        <!-- Bank Account No, Cheque No, and Cheque Date only show for "Bank" payment method -->
                                                        @if($receipt->payment_method === 'bank')
                                                            <td>{{ $receipt->bank_account_no ?? '' }}</td>
                                                            <td>{{ $receipt->cheque_no ?? '' }}</td>
                                    
                                                            <td>{{ \Carbon\Carbon::parse($receipt->cheque_date)->format('d F Y') }}</td>
                                                        @else
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-6">
                                </div> 
                                <div class="col-6">
                            
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th>Total Paid :</th>
                                                <td>{{ bdt() }} {{ number_format($totalPaid, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount need to Paid :</th>
                                                {{-- <td>{{ bdt() }} {{ number_format($grandTotal - $totalPaid, 2) }}</td> --}}
                                                <td>{{ bdt() }} {{ number_format($purchase->grand_total - $purchase->paid_amount, 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
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
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush