@extends('Inventory.layouts.admin')
@section('admin')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Category Show</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.category.index') }}" style="text-decoration: none; color: black;">Category</a></li>
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
                    <h4 class="mb-0">Category Show</h4>
                    <a href="{{ route('inventory.category.index')}}" class="btn btn-sm btn-danger rounded-0">
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
                                <th>Name</th>
                                <td>{{ $category->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Logo</th>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('upload/Inventory/categories/' . $category->image) }}" alt="{{ $category->name }}" width="100">
                                    @else
                                        No Logo Available
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($category->status == 1)
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