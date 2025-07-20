@extends('Inventory.layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1>Product View</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.product.index') }}" style="text-decoration: none; color: black;">Product</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">

                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Product View</h4>
                                <a href="{{ route('inventory.product.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $product->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Product Code</th>
                                            <td>{{ $product->product_code ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category Name</th>
                                            <td>{{ $product->category->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tag Name</th>
                                            <td>
                                                @if($product->tags->count())
                                                    @foreach($product->tags as $tag)
                                                        <span class="badge badge-info">{{ $tag->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No tags assigned</span>
                                                @endif
                                            </td>
                                        </tr>
                                    <tr>
                                            <th>Product Specification</th>
                                            <td>
                                                @if($product->specifications->count())
                                                    <table class="table table-bordered table-sm mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30%;">Specification</th>
                                                                <th style="width: 50%;">Content</th>
                                                                <th style="width: 20%;">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($product->specifications as $spec)
                                                                <tr>
                                                                    <td>{{ $spec->specification->name ?? '' }}</td>
                                                                    <td>{{ $spec->content }}</td>
                                                                    <td>
                                                                        @if($spec->status == 1)
                                                                            <span class="badge badge-success">Active</span>
                                                                        @else
                                                                            <span class="badge badge-secondary">Inactive</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <span class="text-muted">No specifications added</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Brand Name</th>
                                            <td>{{ $product->brand->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Unit Name</th>
                                            <td>{{ $product->unit->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Price</th>
                                            <td>{{ $product->price ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Quantity</th>
                                            <td>{{ $product->quantity ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Latest Quantity (from stocks)</th>
                                            <td>{{ balanceStock($product->id) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alert Quantity</th>
                                            <td>{{ $product->alert_quantity ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Stock Status</th>
                                            <td>
                                                @if($product->stock_status === 'in_stock')
                                                    <span class="badge bg-success">In Stock</span>
                                                @elseif($product->stock_status === 'low_stock')
                                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                                @elseif($product->stock_status === 'out_of_stock')
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @else
                                                    <span class="badge bg-secondary">Unknown</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $product->description ?? '' }}</td>
                                        </tr>
                                        
                                    <tr>
                                            <th>Main Image</th>
                                            <td>
                                                <div class="col-md-1">
                                                    <a href="{{ asset('upload/Inventory/products/' . $product->image) }}" target="_blank">
                                                        <img
                                                            src="{{ !empty($product->image) ? asset('upload/Inventory/products/' . $product->image) : asset('Accounts/logo.jpg') }}"
                                                            alt="Main Image"
                                                            style="width: 100%; height: 60px; border: 1px solid #ddd; border-radius: 5px;">
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Additional Images</th>
                                            <td>
                                                <div class="d-flex flex-wrap">
                                                    @foreach($product->images as $image)
                                                        <div class="mr-2 mb-2" style="width: 80px;">
                                                            <a href="{{ asset('upload/Inventory/products/' . $image->image) }}" target="_blank">
                                                                <img
                                                                    src="{{ asset('upload/Inventory/products/' . $image->image) }}"
                                                                    alt="Additional Image"
                                                                    style="width: 100%; height: 60px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px;">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($product->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>     
                        </div>     
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</div>
@endsection