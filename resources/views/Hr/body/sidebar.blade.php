@php
  

@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('hr.dashboard') }}" class="brand-link text-decoration-none align-items-center text-center">
        <span class="brand-text fw-light fs-5 text-white">
            <span class="text-uppercase">ERP</span> <strong class="text-uppercase">Hr</strong>
        </span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <img src="{{ Auth::user()->profile_image ? asset(Auth::user()->profile_image) : asset('Accounts/dist/img/avatar5.png') }}" width="30" height="30"
            class="user-image rounded-circle shadow" alt="User Image"> 
            <div class="info">
                <a href="{{ route('accounts.dashboard') }}" class="d-block">{{ Auth::user()->name ?? '' }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                    @can('dashboard-menu')  
                    <li class="nav-item menu-open">
                        <a href="{{ route('hr.dashboard') }}"
                            class="nav-link {{ Route::is('hr.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    @endcan
                @can('hr-menu')  
                <li
                    class="nav-item menu-open {{ Route::is('hr.staff.list', 'hr.staff.create', 'hr.staff.edit', 'hr.staff.show', 'hr.ta-da.index', 'hr.ta-da.create', 'hr.ta-da.edit', 'hr.ta-da.show', 'hr.leaves.index', 'hr.leaves.create', 'hr.leaves.edit', 'hr.leaves.show', 'hr.attendance.index', 'hr.attendance.create', 'hr.attendance.edit', 'hr.attendance.show', 'hr.activity.index', 'hr.activity.create', 'hr.activity.edit', 'hr.activity.show', 'hr.salary.index', 'hr.salary.create', 'hr.salary.show') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Route::is('hr.staff.list', 'hr.staff.create', 'hr.staff.edit', 'hr.staff.show', 'hr.ta-da.index', 'hr.ta-da.create', 'hr.ta-da.edit', 'hr.ta-da.show', 'hr.leaves.index', 'hr.leaves.create', 'hr.leaves.edit', 'hr.leaves.show', 'hr.attendance.index', 'hr.attendance.create', 'hr.attendance.edit', 'hr.attendance.show', 'hr.activity.index', 'hr.activity.create', 'hr.activity.edit', 'hr.activity.show', 'hr.salary.index', 'hr.salary.create', 'hr.salary.show') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            HR Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('hr-staff-list')  
                        <li class="nav-item">
                            <a href="{{ route('hr.staff.list') }}"
                                class="nav-link {{ Route::is('hr.staff.list', 'hr.staff.create', 'hr.staff.edit', 'hr.staff.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Staff List</p>
                            </a>
                        </li>
                        @endcan
                        @can('hr-tada-list')  
                        <li class="nav-item">
                            <a href="{{ route('hr.ta-da.index') }}"
                                class="nav-link {{ Route::is('hr.ta-da.index', 'hr.ta-da.create', 'hr.ta-da.edit', 'hr.ta-da.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>TA/DA List</p>
                            </a>
                        </li>
                        @endcan
                        @can('hr-leave-application-list')  
                        <li class="nav-item">
                            <a href="{{ route('hr.leaves.index') }}"
                                class="nav-link {{ Route::is('hr.leaves.index', 'hr.leaves.create', 'hr.leaves.edit', 'hr.leaves.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Applications</p>
                            </a>
                        </li>
                        @endcan
                        @can('hr-attendance-list')  
                        <li class="nav-item">
                            <a href="{{ route('hr.attendance.index') }}"
                                class="nav-link {{ Route::is('hr.attendance.index', 'hr.attendance.create', 'hr.attendance.edit', 'hr.attendance.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance List</p>
                            </a>
                        </li>
                        @endcan
                        @can('hr-activity-list')  
                        <li class="nav-item">
                            <a href="{{ route('hr.activity.index') }}"
                                class="nav-link {{ Route::is('hr.activity.index', 'hr.activity.create', 'hr.activity.edit', 'hr.activity.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Activity List</p>
                            </a>
                        </li>
                        @endcan
                        @can('hr-salary-list')  
                        <li class="nav-item">
                            <a href="{{ route('hr.salary.index') }}"
                                class="nav-link {{ Route::is('hr.salary.index', 'hr.salary.create', 'hr.salary.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Salary</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
