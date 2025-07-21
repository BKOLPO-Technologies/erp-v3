@extends('Inventory.layouts.admin')
@section('admin')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.order.index') }}" style="text-decoration: none; color: black;">Order Show</a></li>
                        <li class="breadcrumb-item active">Order</li>
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
                    <h4 class="mb-0">Order Show</h4>
                    <a href="{{ route('inventory.order.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>Order Code</th>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Customer Name</th>
                                    <td>
                                        {{ $order->customer->name ?? 'Walk-in Customer' }}
                                        @if ($order->customer)
                                            <br><small class="text-muted">{{ $order->customer->phone }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Product</th>
                                    <td>
                                        @if($order->items->count())
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%;">Name</th>
                                                        <th style="width: 10%;">Subtotal</th>
                                                        <th style="width: 10%;">Quantity</th>
                                                        <th style="width: 10%;">Total Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($order->items as $item)
                                                        <tr>
                                                            <td>{{ $item->product->name ?? '' }}</td>
                                                            <td>{{ $item->unit_price ?? '' }}</td>
                                                            <td>{{ $item->quantity ?? '' }}</td>
                                                            <td>{{ $item->total_price ?? '' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        <div>
                                        @else
                                            <span class="text-muted">No products added</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order Status</th>
                                    <td>
                                    <span class="badge badge-{{ $order->order_status === \App\Models\Inventory\Order::ORDER_STATUS_COMPLETED
                                                ? 'success'
                                                : ($order->order_status === \App\Models\Inventory\Order::ORDER_STATUS_CANCELLED
                                                    ? 'danger'
                                                    : 'warning') }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Status</th>
                                    <td>
                                        <span class="badge badge-{{ $order->payment_status === \App\Models\Inventory\Order::PAYMENT_STATUS_PAID
                                                ? 'success'
                                                : ($order->payment_status === \App\Models\Inventory\Order::PAYMENT_STATUS_PARTIAL
                                                    ? 'info'
                                                    : 'danger') }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Vat Rate</th>
                                    <td>
                                        {{ $order->vat_rate}}%
                                    </td>
                                </tr>
                                <tr>
                                    <th>Vat Amount</th>
                                    <td>
                                        {{ $order->vat_amount}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tax Rate</th>
                                    <td>
                                        {{ $order->tax_rate}}%
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tax Amount</th>
                                    <td>
                                        {{ $order->tax_amount}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td>
                                        {{ $order->discount}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td>
                                        {{ $order->total_amount}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Paid Amount</th>
                                    <td>
                                        {{ $order->paid_amount}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Due Amount</th>
                                    <td>
                                        {{ $order->due_amount}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>
                                        {{ $order->order_date->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $order->order_date->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Notes</th>
                                    <td>
                                        {{ $order->notes }}
                                    </td>
                                </tr>
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