@extends('Hr.layouts.admin', ['pageTitle' => 'Staff List'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('Accounts/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">{{ $pageTitle ?? ''}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('hr.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"> HR Management / {{ $pageTitle ?? '' }}</li>
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
                            <a href="{{ route('hr.leaves.create') }}" class="btn btn-sm btn-success rounded-0">
                                <i class="fas fa-plus fa-sm"></i> Add Leave Application
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Staff Name</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveApplications as $index => $leaveApplication)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> 
                                        <td>{{ $leaveApplication->staff->full_name ?? 'N/A' }}</td>
                                        <td>{{ $leaveApplication->leaveType->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leaveApplication->start_date)->translatedFormat('d F Y') ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leaveApplication->end_date)->translatedFormat('d F Y') ?? 'N/A' }}</td>
                                        <td>
                                            @if($leaveApplication->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($leaveApplication->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($leaveApplication->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- View Button -->
                                            <a href="{{ route('hr.leaves.show', $leaveApplication->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="{{ route('hr.leaves.edit', $leaveApplication->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <a href="{{ route('hr.leaves.delete', $leaveApplication->id) }}" id="delete" class="btn btn-danger btn-sm">
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
        </div>
      </section>
      </div>
@endsection
