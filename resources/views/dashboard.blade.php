@extends('layouts.app')
@section('admin')
<style>
    /* Ensure the content is full height and centered at the top */
    .content {
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Align items at the top */
        height: 100vh; /* 100% of the viewport height */
        background-color: #f4f6f9; /* Default background color */
        overflow: hidden;
    }

    .welcome-message {
        text-align: center;
        font-size: 2rem;
        color: #fff;
        opacity: 0;
        padding: 20px;
        border-radius: 10px;
        position: absolute;
        top: 10%; /* Position the message near the top */
        animation: fadeIn 3s forwards, changeBackgroundColor 3s infinite alternate;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animation to change background color */
    @keyframes changeBackgroundColor {
        0% {
            background-color: #007bff; /* Initial background color */
        }
        50% {
            background-color: #28a745; /* Midway background color */
        }
        100% {
            background-color: #ff5722; /* Final background color */
        }
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'Dashboard' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'Dashboard' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        {{-- <div class="welcome-message">
            <h2>Welcome to the Admin Dashboard</h2>
        </div> --}}
        <div class="card-body">
            <div class="row">
                {{-- <div class="col-md-12">
                    <div class="welcome-message">
                        <h2>Welcome to the Inventory Dashboard</h2>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

</div>

@endsection

@push('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endpush

