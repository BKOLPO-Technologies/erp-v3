@extends('Accounts.layouts.admin', ['pageTitle' => 'Balance Sheet Report'])
<!-- CSS to Hide Form on Print -->
<style>
    @media print {
        #filter-form {
            display: none !important;
        }
    }
</style>
@section('admin')
<link rel="stylesheet" href="{{ asset('Accounts/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
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
                                <h4 class="mb-0">{{ $pageTitle }}</h4>
                                <a href="{{ route('accounts.report.balance.sheet')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <!-- Print Button -->
                        <div class="text-right mt-3 mr-4">
                            <button class="btn btn-primary" onclick="printBalanceSheet()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>

                        <div id="printable-area">
                            <div class="card-body">
                                <!-- Balance Sheet Table -->
                                <div class="card-header text-center mb-3">
                                    <h2 class="mb-1">
                                        <img 
                                            src="{{ !empty(get_company()->logo) ? url('upload/Accounts/company/' . get_company()->logo) : asset('Accounts/logo.jpg') }}" 
                                            alt="Company Logo" 
                                            style="height: 40px; vertical-align: middle; margin-right: 10px;"
                                        >
                                        {{ get_company()->name ?? '' }}
                                    </h2> 
                                    <p class="mb-0"><strong>Balance Sheet Report</strong></p>
                                    <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                                </div>
                                <div class="card-body">
                                    <!-- Date Filter Form -->
                                    <div id="filter-form">
                                        <form action="{{ route('accounts.report.balance.sheet') }}" method="GET" class="mb-3">
                                            <div class="row justify-content-center">
                                                <div class="col-md-3 mt-3">
                                                    <label for="from_date">From Date:</label>
                                                    <input type="text" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
                                                </div>
                                                <div class="col-md-3 mt-3">
                                                    <label for="to_date">To Date:</label>
                                                    <input type="text" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
                                                </div>
                                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                                </div>
                                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                                    <a href="{{ route('accounts.report.balance.sheet') }}"  class="btn btn-danger w-100">Clear</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                            <div class="row">
                                                <!-- Liabilities Column -->
                                                <div class="col-md-6">
                                                    @foreach ($ledgerGroups->where('group_name', 'Liabilities') as $group)
                                                        <h2>{{ $group->group_name ?? 'N/A' }}</h2>
                                                        
                                                        <div class="table-responsive">
                                                            <table border="1" class="table-striped table-bordered" cellpadding="5" cellspacing="0" style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 55%;">Name</th>
                                                                        <th style="width: 45%;">Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php $groupTotal = 0; @endphp
                                                                    
                                                                    @foreach ($group->subGroups as $subGroup)
                                                                        @php
                                                                            $subGroupTotal = 0;
                                                                            foreach ($subGroup->ledgers as $ledger) {
                                                                                $subGroupTotal += abs($ledger->total_debit - $ledger->total_credit);
                                                                            }
                                                                            $groupTotal += $subGroupTotal;
                                                                        @endphp
                                                                        
                                                                        <tr data-toggle="collapse" data-target="#subgroup-{{ $subGroup->id }}" aria-expanded="false" style="cursor: pointer; background-color: #f2f2f2;">
                                                                            <td><strong>{{ $subGroup->subgroup_name }}</strong></td>
                                                                            <td><strong>{{ bdt() }} {{ number_format($subGroupTotal, 2) }}</strong></td>
                                                                        </tr>
                                                    
                                                                        <tbody id="subgroup-{{ $subGroup->id }}" class="collapse">
                                                                            @foreach ($subGroup->ledgers as $ledger)
                                                                                @php
                                                                                    $balance = abs($ledger->total_debit - $ledger->total_credit);
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $ledger->name }}</td>
                                                                                    <td>{{ bdt() }} {{ number_format($balance, 2) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    @if($showDifferenceOn == 'Liabilities' && $absDifference > 0)
                                                                    <tr>
                                                                        <th>Balance (Difference)</th>
                                                                        <th class="text-danger">{{ bdt() }} {{ number_format($absDifference, 2) }}</th>
                                                                    </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <th>Total {{ $group->group_name }}</th>
                                                                        <th>{{ bdt() }} {{ number_format($showDifferenceOn == 'Liabilities' ? $groupTotal + $absDifference : $groupTotal, 2) }}</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!-- Assets Column -->
                                                <div class="col-md-6">
                                                    @foreach ($ledgerGroups->where('group_name', 'Asset') as $group)
                                                        <h2>{{ $group->group_name ?? 'N/A' }}</h2>
                                                        
                                                        <div class="table-responsive">
                                                            <table border="1" class="table-striped table-bordered" cellpadding="5" cellspacing="0" style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 60%;">Name</th>
                                                                        <th style="width: 40%;">Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php $groupTotal = 0; @endphp
                                                                    
                                                                    @foreach ($group->subGroups as $subGroup)
                                                                        @php
                                                                            $subGroupTotal = 0;
                                                                            foreach ($subGroup->ledgers as $ledger) {
                                                                                $subGroupTotal += abs($ledger->total_debit - $ledger->total_credit);
                                                                            }
                                                                            $groupTotal += $subGroupTotal;
                                                                        @endphp
                                                                        
                                                                        <tr data-toggle="collapse" data-target="#subgroup-{{ $subGroup->id }}" aria-expanded="false" style="cursor: pointer; background-color: #f2f2f2;">
                                                                            <td><strong>{{ $subGroup->subgroup_name }}</strong></td>
                                                                            <td><strong>{{ bdt() }} {{ number_format($subGroupTotal, 2) }}</strong></td>
                                                                        </tr>
                                                    
                                                                        <tbody id="subgroup-{{ $subGroup->id }}" class="collapse">
                                                                            @foreach ($subGroup->ledgers as $ledger)
                                                                                @php
                                                                                    $balance = abs($ledger->total_debit - $ledger->total_credit);
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $ledger->name }}</td>
                                                                                    <td>{{ bdt() }} {{ number_format($balance, 2) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    @if($showDifferenceOn == 'Asset' && $absDifference > 0)
                                                                    <tr>
                                                                        <th>Balance (Difference)</th>
                                                                        <th>{{ bdt() }} {{ number_format($absDifference, 2) }}</th>
                                                                    </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <th>Total {{ $group->group_name }}</th>
                                                                        <th>{{ bdt() }} {{ number_format($showDifferenceOn == 'Asset' ? $groupTotal + $absDifference : $groupTotal, 2) }}</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Grand Total -->
                                            <div class="row mt-3">
                                                <div class="col-md-12 text-center">
                                                    @php
                                                        $grandTotal = max($totalAssets, $totalLiabilities);
                                                    @endphp
                                                    <h4>Grand Total: {{ bdt() }} {{ number_format($grandTotal, 2) }}</h4>
                                                    <p><strong>Amount in Words:</strong> {{ convertNumberToWords($grandTotal) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
<!-- JavaScript for Printing -->
<script>
    function printBalanceSheet() {
        var printContent = document.getElementById("printable-area").innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>
@endpush
