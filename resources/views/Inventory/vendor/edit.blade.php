@extends('Inventory.layouts.admin', ['pageTitle' => 'Vendor Edit'])

@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>DataTables</h1> -->
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

    <!-- Main content -->
    <section class="content">
        
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline shadow-lg">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                        <a href="{{ route('inventory.vendor.index')}}" class="btn btn-sm btn-danger rounded-0">
                            <i class="fa-solid fa-arrow-left"></i> Back To List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.vendor.update', $vendor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $vendor->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Group Name</label>
                                        <input type="text" class="form-control" placeholder="Group Name" name="company" value="{{ $vendor->company }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Person</label>
                                        <input type="text" class="form-control" name="title" value="{{ $vendor->title }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <input type="text" class="form-control" name="designation" value="{{ $vendor->designation }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" placeholder="Phone" name="phone" value="{{ $vendor->phone }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{ $vendor->email }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control" placeholder="Address" name="address" value="{{ $vendor->address }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Account Name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="bank_account_name" value="{{ $vendor->bank_account_name }}" placeholder="Enter Bank Account Name">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip no</label>
                                        <input type="text" class="form-control" name="zip" value="{{ $vendor->zip }}">
                                    </div>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Account Number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="bank_account_number" value="{{ $vendor->bank_account_number }}" placeholder="Enter Bank Account Number">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Routing Number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="bank_routing_number" value="{{ $vendor->bank_routing_number }}" placeholder="Enter Roting Number">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="city" value="{{ $vendor->city }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" class="form-control" placeholder="Country" name="country" value="{{ $vendor->country }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>BIN Number</label>
                                        <input type="text" class="form-control" name="bin" value="{{ $vendor->bin }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>TAX ID</label>
                                        <input type="text" class="form-control" placeholder="Tax Id" name="taxid" value="{{ $vendor->taxid }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                    <i class="fas fa-paper-plane"></i> Update Vendor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          </div>
        </div>
      </div>

    </section>


</div>
@endsection