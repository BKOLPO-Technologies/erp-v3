@extends('Inventory.layouts.admin', ['pageTitle' => 'Vendor View'])

@section('admin')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('inventory.vendor.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <!-- ------ -->
                        <div class="card-body">
                            <div class="row wrapper white-bg page-heading">
                                <div class="col-md-4">
                                    <div class="card card-block p-3">
                                        <h4 class="text-xs-center"> {{ $vendor->name }}</h4>
                                        <div class="ibox-content mt-2">
                                            <img alt="image" id="dpic" class="img-responsive"
                                                src="https://accounts.bkolpo.com/userfiles/customers/example.png">
                                        </div>

                                        <hr>

                                        <div class="user-button">
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <a href="{{ route('inventory.vendor.edit', ['id' => $vendor->id ]) }}" class="btn btn-warning btn-sm">
                                                        <i class="icon-pencil"></i> Edit Profile
                                                    </a>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="#sendMail" data-toggle="modal" data-remote="false" class="btn btn-primary btn-sm " data-type="reminder">
                                                        <i class="icon-envelope"></i> Send Message
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <br>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <h5>Balance Summary</h5>
                                                <hr>
                                                <ul class="list-group list-group-flush">
                                                    <!-- Total Purchases Products -->
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span class="ml-2">Total Purchases Products</span>
                                                        <span class="tag tag-default tag-pill bg-success p-1">
                                                            0
                                                        </span>
                                                    </li>
                                        
                                                    <!-- Amount Paid -->
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span class="ml-2">Total Paid Amount</span>
                                                        <span class="tag tag-default tag-pill bg-info p-1">
                                                            0
                                                        </span>
                                                    </li>
                                            
                                                    <!-- Amount Due -->
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span class="ml-2">Total Due Amount</span>
                                                        <span class="tag tag-default tag-pill bg-warning p-1">
                                                            0
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="row mt-3">
                                            
                                            <div class="col-md-6">
                                                <a href="#" class="btn btn-primary btn-sm">
                                                   View Products Purchases
                                                </a>
                                            </div>
                                        
                                            <div class="col-md-6 text-right">
                                                <a href="#" class="btn btn-success btn-sm">
                                                    View Transactions
                                                </a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="card card-block p-3">
                                        <h4>Vendor Details</h4>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Company Name</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->name }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Group Name</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->company }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Contact Person</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->title }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Designation</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->designation }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong> Address</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->address }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong>Bank Account Name</strong>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $vendor->bank_account_name }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong>Bank Account Number</strong>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $vendor->bank_account_number }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong>Routing Number</strong>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $vendor->bank_routing_number }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>City</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->city }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Country</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->country }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>postcode</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->postbox }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Email</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->email }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong> Phone</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $vendor->phone }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row mt-3">

                                            {{-- <div class="col-md-6">
                                                <a href="#" class="btn btn-primary btn-lg">
                                                    <i class="icon-file-text2"></i> View Purchase Orders
                                                </a>
                                            </div> --}}

                                            {{-- <div class="col-md-6">
                                                <a href="#" class="btn btn-success btn-lg">
                                                    <i class="icon-money3"></i> View Transactions
                                                </a>
                                            </div> --}}

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="sendMail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="emailModalLabel">vendor SMS Send</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="sendmail_form">
                                            <!-- Customer Name Input -->
                                            <div class="form-group">
                                                <label for="customername">vendor Name</label>
                                                <input type="text" class="form-control" id="customername" name="customername" value="{{ $vendor->company }}" readonly> 
                                            </div>

                                            <!-- Subject Input -->
                                            <div class="form-group">
                                                <label for="subject">Subject</label>
                                                <input type="text" class="form-control" id="subject" name="subject">
                                            </div>

                                            <!-- Message Input -->
                                            <div class="form-group">
                                                <label for="contents">Message</label>
                                                <textarea class="form-control" id="contents" name="text" rows="4" title="Contents"></textarea>
                                            </div>

                                            <!-- Hidden Inputs -->
                                            <input type="hidden" id="cid" name="tid" value="1">
                                            <input type="hidden" id="action-url" value="communication/send_general">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="sendNow">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- ------ -->

                    </div>
                </div>
            </div>
        </div>

    </section>


</div>
@endsection