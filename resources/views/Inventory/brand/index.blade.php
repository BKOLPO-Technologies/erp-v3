@extends('Inventory.layouts.admin')
@section('admin')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Brand List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Brand</li>
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
                        <h3 class="card-title">All Brand</h3>
                        <a href="{{ route('inventory.brand.create') }}" class="btn btn-success float-right">
                            <i class="fas fa-plus fa-sm"></i> Add Brand
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $brand)
                                <tr>
                                    <td class="col-1">{{ $loop->iteration }}</td>
                                    <td class="col-2">
                                        @if($brand->logo)
                                            <img src="{{ asset('upload/Inventory/brands/'.$brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid" style="max-width: 50px;">
                                        @else
                                            <span class="text-muted">No Logo</span>
                                        @endif
                                    </td>
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        @if($brand->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>    
                                    <td>
                                        <a href="{{ route('inventory.brand.show', $brand->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('inventory.brand.edit', $brand->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('inventory.brand.destroy', $brand->id) }}" id="delete" class="btn btn-danger btn-sm">
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
