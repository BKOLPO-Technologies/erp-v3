@extends('Accounts.layouts.admin', ['pageTitle' => 'Ledger Group List'])

@section('admin')

<link rel="stylesheet" href="{{ asset('Accounts/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- You can add additional content here -->
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
                                <h4 class="mb-0">{{ $pageTitle }}</h4>
                                <a href="{{ route('accounts.report.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To Report List
                                </a>
                            </div>
                        </div>
                        
                        <!-- Card Body with Ledger Name -->
                        <div class="card-body">
                            <div class="row">
                                @foreach($ledgerGroups as $group)
                                    <div class="col-md-3">
                                        <div class="card shadow-lg shadow-sm">
                                            <div class="card-header 
                                                @if($group->status == '1') bg-success text-white 
                                                @elseif($group->status == '0') bg-danger text-white 
                                                @else bg-primary text-white @endif">
                                                <h5 class="card-title">{{ $group->group_name }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="mb-0">Number of Ledgers: {{ $group->ledgers->count() }}</p>
                                                <!-- Add more information here if needed -->
                                            </div>
                                            <div class="card-footer d-flex justify-content-between">
                                                <a href="#" onclick="comingSoon()" class="btn btn-danger btn-sm w-50 mr-2">
                                                    <i class="fa fa-file-alt ml-2"></i> Pay Slip
                                                </a>
                                                <a href="{{ route('accounts.report.ledger.group.single.report', $group->id) }}" class="btn btn-info btn-sm w-50">
                                                    <i class="fa fa-file-alt ml-2"></i> View Report
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
// Initialize Select2 if necessary
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endpush
