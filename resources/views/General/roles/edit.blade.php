@extends('layouts.app', ['pageTitle' => 'Role Edit'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('Accounts/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
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
                                    <h4 class="mb-0">Edit Role: {{ $role->name }}</h4>
                                    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('roles.update', $role->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="name" class="form-label">Name
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name', $role->name) }}" placeholder="Enter Role Name">
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            @php
                                                $roleGroups = [
                                                    'accounts-manager' => [
                                                        'title' => 'Accounts Manager',
                                                        'bg_color' => 'bg-success',
                                                        'groups' => ['settings','accounts','customers','vendors','projects','transactions','sales','purchases','reports','company','branch','products']
                                                    ],
                                                    'inventory-manager' => [
                                                        'title' => 'Inventory Manager',
                                                        'bg_color' => 'bg-info',
                                                        'groups' => ['settings', 'category'],
                                                    ],
                                                    'hr-manager' => [
                                                        'title' => 'HR Manager',
                                                        'bg_color' => 'bg-primary',
                                                        'groups' => ['settings', 'hr'],
                                                    ],
                                                ];

                                                $rolePermissions = $role->permissions->pluck('id')->toArray();
                                            @endphp

                                            @foreach ($roleGroups as $roleKey => $roleData)
                                                @if (strtolower($role->name) === strtolower($roleData['title']))
                                                    <h3
                                                        class="{{ $roleData['bg_color'] }} w-100 p-2 text-center text-light font-weight-bolder rounded">
                                                        {{ $roleData['title'] }}
                                                    </h3>
                                                    <hr>

                                                    <div class="form-group">
                                                        <strong>Permission:</strong>

                                                        <!-- Select All -->
                                                        <div class="form-group clearfix mt-3">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="checkbox" id="selectAll{{ $roleKey }}"
                                                                    class="role-select-all">
                                                                <label for="selectAll{{ $roleKey }}">
                                                                    Select All for {{ $roleData['title'] }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        @foreach ($roleData['groups'] as $group)
                                                            @php
                                                                $groupPermissions = $permission->where('group', $group);
                                                                $groupTitle = ucfirst($group);
                                                            @endphp

                                                            @if ($groupPermissions->count() > 0)
                                                                <div class="card card-info card-outline mt-4 shadow">
                                                                    <div
                                                                        class="card-header d-flex justify-content-between align-items-center">
                                                                        <h5 class="card-title mb-0">{{ $groupTitle }}
                                                                            Menu</h5>
                                                                        <div class="ml-auto icheck-primary d-inline">
                                                                            <input type="checkbox" class="group-select-all"
                                                                                id="selectAll{{ $roleKey }}{{ $group }}"
                                                                                data-role="{{ $roleKey }}"
                                                                                data-group="{{ $group }}">
                                                                            <label
                                                                                for="selectAll{{ $roleKey }}{{ $group }}">Select
                                                                                All</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="card-body">
                                                                        <div class="form-group clearfix d-flex flex-wrap">
                                                                            @foreach ($groupPermissions as $value)
                                                                                <div
                                                                                    class="icheck-success d-inline mb-2 mr-3"  style="min-width: 220px;">
                                                                                    <input type="checkbox"
                                                                                        value="{{ $value->id }}"
                                                                                        name="permission[]"
                                                                                        class="permission-checkbox {{ $roleKey }}-checkbox {{ $roleKey }}-{{ $group }}-checkbox"
                                                                                        id="checkbox{{ $roleKey }}{{ $value->id }}"
                                                                                        @if (in_array($value->id, $rolePermissions)) checked @endif>
                                                                                    <label
                                                                                        for="checkbox{{ $roleKey }}{{ $value->id }}">
                                                                                        {{ ucwords(str_replace('-', ' ', $value->name)) }}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light"
                                                style="float: right;">
                                                <i class="fas fa-save"></i> Update Role
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

@push('js')
    <script>
        $(document).ready(function() {
            // Role-level select all
            $('.role-select-all').change(function() {
                const roleKey = $(this).attr('id').replace('selectAll', '');
                $(`.${roleKey}-checkbox`).prop('checked', $(this).prop('checked'));
            });

            // Group-level select all within a role
            $('.group-select-all').change(function() {
                const roleKey = $(this).data('role');
                const group = $(this).data('group');
                $(`.${roleKey}-${group}-checkbox`).prop('checked', $(this).prop('checked'));

                // Update role-level select all status
                const allChecked = $(`.${roleKey}-checkbox`).length === $(`.${roleKey}-checkbox:checked`)
                    .length;
                $(`#selectAll${roleKey}`).prop('checked', allChecked);
            });

            // Individual checkbox change
            $('.permission-checkbox').change(function() {
                const classList = $(this).attr('class').split(' ');
                const roleKey = classList.find(cls => cls.endsWith('-checkbox')).replace('-checkbox', '');

                // Update group-level select all status
                const groupCheckboxes = $(`.${roleKey}-checkbox`);
                const allChecked = groupCheckboxes.length === groupCheckboxes.filter(':checked').length;
                $(`#selectAll${roleKey}`).prop('checked', allChecked);
            });

            // Initialize select all checkboxes based on current selections
            $('.group-select-all').each(function() {
                const roleKey = $(this).data('role');
                const group = $(this).data('group');
                const allChecked = $(`.${roleKey}-${group}-checkbox`).length === $(
                    `.${roleKey}-${group}-checkbox:checked`).length;
                $(this).prop('checked', allChecked);
            });

            $('.role-select-all').each(function() {
                const roleKey = $(this).attr('id').replace('selectAll', '');
                const allChecked = $(`.${roleKey}-checkbox`).length === $(`.${roleKey}-checkbox:checked`)
                    .length;
                $(this).prop('checked', allChecked);
            });
        });
    </script>
@endpush
