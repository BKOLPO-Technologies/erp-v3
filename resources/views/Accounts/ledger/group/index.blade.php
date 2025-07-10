@extends('Accounts.layouts.admin', ['pageTitle' => 'Ledger Group List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('Accounts/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
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
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    @can('ledger-group-create')
                                    <a href="{{ route('accounts.ledger.group.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Ledger Group
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Group Name</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ledgers as $index => $ledger) 
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> 
                                                <td>{{ $ledger->group_name }}</td>
                                                <td>
                                                    @if($ledger->status == 1)
                                                        <a href="#" class="badge badge-success">
                                                            <span class="badge bg-success">Active</span>
                                                        </a>
                                                    @else
                                                        <a href="#" class="badge badge-danger">
                                                            <span class="badge bg-danger">Inactive</span>
                                                        </a>
                                                    @endif
                                                </td>           
                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    @can('ledger-group-view')
                                                    <a href="{{ route('accounts.ledger.group.show',$ledger->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endcan
                                                    <!-- Edit Button -->
                                                    @can('ledger-group-edit')
                                                    <a href="{{ route('accounts.ledger.group.edit',$ledger->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endcan
                                                    <!-- Delete Button -->
                                                    {{-- @can('ledger-group-delete')
                                                    <a href="{{ route('ledger.group.delete',$ledger->id)}}" id="delete" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    @endcan --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
