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
                                <a href="{{ route('inventory.dashboard') }}"
                                    style="text-decoration: none; color: black;">Home</a>
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
                            <a href="{{ route('inventory.order.create') }}" class="btn btn-success float-right">
                                <i class="fas fa-plus fa-sm"></i>
                                Add Order
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="orders-table" class="table table-bordered table-hover table-striped">
                                <thead class="">
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Order Code</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th width="5%">Num of Products</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr class="@if($order->created_at->gt(now()->subMinutes(5))) bg-light-yellow @endif">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $order->order_number }}</strong>
                                                @if ($order->reference_number)
                                                    <br><small class="text-muted">Ref: {{ $order->reference_number }}</small>
                                                @endif
                                                @if($order->created_at->gt(now()->subMinutes(5)))
                                                    <span class="badge badge-success ml-1">New</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $order->customer->name ?? 'Walk-in Customer' }}
                                                @if ($order->customer)
                                                    <br><small class="text-muted">{{ $order->customer->phone }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $order->order_date->format('M d, Y') }}
                                                <br><small
                                                    class="text-muted">{{ $order->order_date->diffForHumans() }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-pill badge-primary">
                                                    {{ $order->items->sum('quantity') }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                {{-- Total Amount --}}
                                                {{ number_format($order->total_amount, 2) }} <br>

                                                {{-- Paid Amount --}}
                                                <small class="text-info">
                                                    Paid: {{ number_format($order->total_amount - $order->due_amount, 2) }}
                                                </small><br>

                                                {{-- Due or Paid Status --}}
                                                <small class="{{ $order->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ $order->due_amount > 0 ? 'Due: ' . number_format($order->due_amount, 2) : 'Paid' }}
                                                </small>
                                            </td>

                                            <td>
                                                <span
                                                    class="badge badge-{{ $order->order_status === \App\Models\Inventory\Order::ORDER_STATUS_COMPLETED
                                                        ? 'success'
                                                        : ($order->order_status === \App\Models\Inventory\Order::ORDER_STATUS_CANCELLED
                                                            ? 'danger'
                                                            : 'warning') }}">
                                                    {{ ucfirst($order->order_status) }}
                                                </span>
                                                <br>
                                                <span
                                                    class="badge badge-{{ $order->payment_status === \App\Models\Inventory\Order::PAYMENT_STATUS_PAID
                                                        ? 'success'
                                                        : ($order->payment_status === \App\Models\Inventory\Order::PAYMENT_STATUS_PARTIAL
                                                            ? 'info'
                                                            : 'danger') }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('inventory.order.show', $order->id) }}"
                                                        class="btn btn-sm btn-info mr-1" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('inventory.order.edit', $order->id) }}" class="btn btn-sm btn-primary mr-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('inventory.order.destroy', $order->id) }}" id="delete" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">Total:</th>
                                        <th class="text-right">{{ number_format($orders->sum('total_amount'), 2) }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            $('#orders-table').DataTable({
                "order": [
                    [3, "desc"]
                ],
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                    "orderable": false,
                    "targets": [7]
                }]
            });
        });
    </script>
@endpush
