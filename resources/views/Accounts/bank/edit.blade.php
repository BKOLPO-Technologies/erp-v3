@extends('Accounts.layouts.admin', ['pageTitle' => 'Bank Edit'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? ''}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? ''}}</li>
                  </ol>
                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                    <a href="{{ route('accounts.bank.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                              <form method="POST" action="{{ route('accounts.bank.update',$bank->id) }}" enctype="multipart/form-data">
                                  @csrf
                                  <div class="row">
                                      <div class="col-md-6 mb-2">
                                          <label for="name" class="form-label">Name
                                              @error('name')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="name" name="name" value="{{ $bank->name }}" placeholder="Enter Bank Name">
                                          </div>
                                      </div>
                                      <div class="col-md-6 mb-2">
                                          <label for="account_number" class="form-label">Account Number
                                              @error('account_number')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-university"></i></span>
                                              <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $bank->account_number }}" placeholder="Enter Account Number">
                                          </div>
                                      </div>
                                      <div class="col-md-12 mb-2">
                                          <label for="status" class="form-label">Status
                                              @error('status')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="1" {{ $bank->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ $bank->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                          </div>
                                      </div>
                                      <div class="col-12 mb-1">
                                          <label for="description" class="form-label">Description
                                              @error('description')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-pencil-alt"></i></span>
                                              <textarea class="form-control" name="description" rows="3" placeholder="Enter description">{{ $bank->description }}</textarea>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row mt-3">
                                      <div class="col-lg-12">
                                          <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="fas fa-paper-plane"></i> Update Bank</button>
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
@push('js')
<script>
    // select 2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select roles",
            allowClear: true
        });
    });

</script>
@endpush
