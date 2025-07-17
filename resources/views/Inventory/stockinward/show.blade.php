@extends('Inventory.layouts.admin')
@section('admin')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Stock Inward Show</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.stockinward.index') }}" style="text-decoration: none; color: black;">Stock Inward</a></li>
                        <li class="breadcrumb-item active">Show</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline shadow-lg">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Stock Inward Show</h4>
                    <a href="{{ route('inventory.stockinward.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>Vendor</th>
                                <td>{{ $stockInward->vendor->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Product</th>
                                <td>{{ $stockInward->product->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Reference Lot</th>
                                <td>{{ $stockInward->reference_lot }}</td>
                            </tr>
                            <tr>
                                <th>Total Cost</th>
                                <td>{{ $stockInward->total_price }}</td>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td>{{ $stockInward->quantity }}</td>
                            </tr>
                            <tr>
                                <th>Unit Cost</th>
                                <td>{{ $stockInward->unit_price }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ $stockInward->created_at->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Comments</th>
                                <td>{{ $stockInward->comments }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($stockInward->status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </section>
</div>
@endsection