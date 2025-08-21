@extends('Accounts.layouts.admin', [$pageTitle => 'Customer View'])

@section('admin')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
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
                            <!-- <h3 class="card-title">Add New supplier Details</h3> -->
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                <a href="{{ route('accounts.client.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row wrapper white-bg page-heading">
                                <div class="col-md-4">
                                    <div class="card card-block p-3">
                                        <h4 class="text-xs-center">{{ $client->name }}</h4>
                                        <div class="ibox-content mt-2">
                                            <img alt="image" id="dpic" class="img-responsive"
                                                src="https://accounts.bkolpo.com/userfiles/customers/example.png">
                                        </div>

                                        <hr>

                                        <div class="user-button">
                                            <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <a href="{{ route('accounts.client.edit', ['id' => $client->id ]) }}" class="btn btn-warning btn-sm">
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
                                                            {{ bdt() }} {{ number_format($totalPurchaseAmount, 2) }}
                                                        </span>
                                                    </li>

                                                    <!-- Amount Paid -->
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span class="ml-2">Total Paid Amount</span>
                                                        <span class="tag tag-default tag-pill bg-info p-1">
                                                            {{ bdt() }} {{ number_format($totalPaidAmount, 2) }}
                                                        </span>
                                                    </li>
                                            
                                                    <!-- Amount Due -->
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span class="ml-2">Total Due Amount</span>
                                                        <span class="tag tag-default tag-pill bg-warning p-1">
                                                            {{ bdt() }} {{ number_format($totalDueAmount, 2) }}
                                                        </span>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>

                                        <hr>
                                        
                                        <div class="row mt-3">
                                            
                                            <div class="col-md-6">
                                                <a href="{{ route('accounts.client.products', $client->id) }}" class="btn btn-primary btn-sm">
                                                   View Products Sales
                                                </a>
                                            </div>
                                        
                                            <div class="col-md-6 text-right">
                                                <a href="{{ route('accounts.client.transactions', $client->id) }}" class="btn btn-success btn-sm">
                                                    View Transactions
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-8">
                                    <div class="card card-block p-3">
                                        <h4>Client Details</h4>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Company Name</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->name }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Group Name</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->company }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong> Address</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->address }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong>Bank Account Name</strong>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $client->bank_account_name }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong>Bank Account Number</strong>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $client->bank_account_number }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong>Routing Number</strong>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $client->bank_routing_number }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>City</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->city }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Region</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->region }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Country</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->country }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>postcode</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->postbox }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong>Email</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->email }}
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-2">
                                                <strong> Phone</strong>
                                            </div>
                                            <div class="col-md-10">
                                                {{ $client->phone }}
                                            </div>

                                        </div>
                                        <hr>
                                        {{-- <div class="row mt-3">
                                            <div class="col-md-6">

                                                <a href="#" class="btn btn-primary btn-lg">
                                                    <i class="icon-file-text2"></i> View Purchase Orders
                                                </a>

                                            </div>

                                            <div class="col-md-6">
                                                <a href="#" class="btn btn-success btn-lg">
                                                    <i class="icon-money3"></i> View Transactions
                                                </a>
                                            </div>

                                        </div> --}}


                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="sendMail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="emailModalLabel">Email</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="sendmail_form">
                                            <!-- Email Input -->
                                            <div class="form-group">
                                                <label for="mailtoc">Email</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    </div>
                                                    <input type="email" class="form-control" id="mailtoc" name="mailtoc" placeholder="Email" value="stews@gmail.com">
                                                </div>
                                            </div>

                                            <!-- Client Name Input -->
                                            <div class="form-group">
                                                <label for="customername">Client Name</label>
                                                <input type="text" class="form-control" id="customername" name="customername" value="GPH Ispat Ltd.">
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