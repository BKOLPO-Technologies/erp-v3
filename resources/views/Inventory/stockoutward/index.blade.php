@extends('Inventory.layouts.admin')
@section('admin')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ $pageTitle ?? '' }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $pageTitle ?? '' }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary shadow">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $pageTitle ?? '' }}
                        </h3>
                        <a href="{{ route('inventory.stockoutward.create') }}" class="btn btn-success float-right">
                            <i class="fas fa-plus fa-sm"></i>
                            Add Stock Outward
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Reference Lot</th>
                                    <th>Total Cost</th>
                                    <th>Quantity</th>
                                    <th>Unit Cost</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockOutwards as $stockOutward)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $stockOutward->customer->name ?? '' }}</td>
                                    <td>{{ $stockOutward->product->name ?? '' }}</td>
                                    <td>{{ $stockOutward->reference_lot }}</td>
                                    <td>{{ $stockOutward->total_price }}</td>
                                    <td>{{ $stockOutward->quantity }}</td>
                                    <td>{{ $stockOutward->unit_price }}</td>
                                    <td>{{ $stockOutward->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if($stockOutward->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>    
                                    <td>
                                        <a href="{{ route('inventory.stockoutward.show', $stockOutward->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('inventory.stockoutward.edit', $stockOutward->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('inventory.stockoutward.destroy',$stockOutward->id)}}" id="delete" class="btn btn-danger btn-sm">
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
    </section>
</div>
@endsection
