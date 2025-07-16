@extends('Inventory.layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Product List</li>
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
                        <h3 class="card-title">All Products <span class="badge badge-danger">{{ count($products) }}</span></h3>
                        <a href="{{ route('inventory.product.create') }}" class="btn btn-success float-right">
                            <i class="fas fa-plus fa-sm"></i> Add Product
                        </a>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Group Name</th>
                                    <th>Product Code</th>
                                    <th>Category Name</th>
                                    <th>Price</th>
                                    {{-- <th>Quantity</th> --}}
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key=> $product)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td class="col-1 text-center">  
                                        @if($product->image)
                                            <a href="{{ asset('upload/Inventory/products/' . $product->image) }}" target="_blank">
                                                <img
                                                    src="{{ !empty($product->image) ? asset('upload/Inventory/products/' . $product->image) : asset('Accounts/logo.jpg') }}"
                                                    alt="Main Image"
                                                    style="width: 50px; height: 40px; border: 1px solid #ddd; border-radius: 5px;">
                                            </a>
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ ucfirst($product->group_name ?? '') }}</td>
                                    <td>{{ $product->product_code }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->price }}</td>
                                    {{-- <td>{{ $product->quantity }}</td> --}}
                                    <td>
                                        <a href="{{ route('inventory.product.view', $product->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('inventory.product.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('inventory.product.destroy', $product->id) }}" id="delete" class="btn btn-danger btn-sm">
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
