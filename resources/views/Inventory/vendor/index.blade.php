@extends('Inventory.layouts.admin', ['pageTitle' => 'Vendor List'])
@section('admin')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? ''}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? ''}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline shadow-lg">
                    <div class="card-header py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                            <a href="{{ route('inventory.vendor.create') }}" class="btn btn-sm btn-success rounded-0">
                                <i class="fas fa-plus fa-sm"></i> Add New Vendor
                            </a>
                        </div>
                    </div>
                   
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Company Name</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Contact Person</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vendors as $vendor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td> 
                                    <td>{{ $vendor->name ?? '' }}</td>
                                    <td>{{ $vendor->address ?? '' }}</td>
                                    <td>{{ $vendor->email ?? '' }}</td>
                                    <td>{{ $vendor->phone ?? '' }}</td>
                                    <td>{{ $vendor->title ?? '' }}</td>
                                    <td>
                                        <a href="{{ route('inventory.vendor.show', $vendor->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('inventory.vendor.edit', $vendor->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('inventory.vendor.destroy',$vendor->id)}}" id="delete" class="btn btn-danger btn-sm">
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
