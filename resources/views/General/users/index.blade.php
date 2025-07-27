@extends('layouts.app', ['pageTitle' => 'User List'])
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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
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
                                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New User
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user) 
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> 
                                                <td>{{ $user->name ?? '' }}</td>
                                                <td>{{ $user->email ?? '' }}</td>
                                                <td>
                                                    @if(!empty($user->getRoleNames()))
                                                        @foreach($user->getRoleNames() as $v)
                                                        <label class="badge bg-success">{{ $v }}</label>
                                                        @endforeach
                                                    @endif
                                                </td>   
                                                <td>
                                                    @if($user->status == 1)
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
                                                    <!-- Login As Button -->
                                                    @if(Auth::user()->hasRole('superadmin') && Auth::id() != $user->id)
                                                        <a href="{{ route('impersonate.login', $user->id) }}" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-sign-in-alt"></i> Login As
                                                        </a>
                                                    @endif
                                                    <!-- View Button -->
                                                    <a href="{{ route('users.show',$user->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('users.edit',$user->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('users.delete',$user->id)}}" id="delete" class="btn btn-danger btn-sm">
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
