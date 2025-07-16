@php
  

@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('hrm.dashboard') }}" class="brand-link text-decoration-none align-items-center text-center">
        <span class="brand-text fw-light fs-5 text-white">
            <span class="text-uppercase">ERP</span> <strong class="text-uppercase">Hrm</strong>
        </span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('Accounts/assets/img/AdminLTELogo.png') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>

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
                        <a href="{{ route('hrm.dashboard') }}"
                            class="nav-link {{ Route::is('hrm.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                @endcan

                <li
                    class="nav-item {{ Route::is('hrm.staff.list', 'hrm.staff.create', 'hrm.staff.edit', 'hrm.staff.show', 'hrm.ta-da.index', 'hrm.ta-da.create', 'hrm.ta-da.edit', 'hrm.ta-da.show', 'hrm.leaves.index', 'hrm.leaves.create', 'hrm.leaves.edit', 'hrm.leaves.show', 'hrm.attendance.index', 'hrm.attendance.create', 'hrm.attendance.edit', 'hrm.attendance.show', 'hrm.activity.index', 'hrm.activity.create', 'hrm.activity.edit', 'hrm.activity.show', 'hrm.salary.index', 'hrm.salary.create', 'hrm.salary.show') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Route::is('hrm.staff.list', 'hrm.staff.create', 'hrm.staff.edit', 'hrm.staff.show', 'hrm.ta-da.index', 'hrm.ta-da.create', 'hrm.ta-da.edit', 'hrm.ta-da.show', 'hrm.leaves.index', 'hrm.leaves.create', 'hrm.leaves.edit', 'hrm.leaves.show', 'hrm.attendance.index', 'hrm.attendance.create', 'hrm.attendance.edit', 'hrm.attendance.show', 'hrm.activity.index', 'hrm.activity.create', 'hrm.activity.edit', 'hrm.activity.show', 'hrm.salary.index', 'hrm.salary.create', 'hrm.salary.show') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            HR Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('hrm.staff.list') }}"
                                class="nav-link {{ Route::is('hrm.staff.list', 'hrm.staff.create', 'hrm.staff.edit', 'hrm.staff.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Staff List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hrm.ta-da.index') }}"
                                class="nav-link {{ Route::is('hrm.ta-da.index', 'hrm.ta-da.create', 'hrm.ta-da.edit', 'hrm.ta-da.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>TA/DA List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hrm.leaves.index') }}"
                                class="nav-link {{ Route::is('hrm.leaves.index', 'hrm.leaves.create', 'hrm.leaves.edit', 'hrm.leaves.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Applications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hrm.attendance.index') }}"
                                class="nav-link {{ Route::is('hrm.attendance.index', 'hrm.attendance.create', 'hrm.attendance.edit', 'hrm.attendance.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hrm.activity.index') }}"
                                class="nav-link {{ Route::is('hrm.activity.index', 'hrm.activity.create', 'hrm.activity.edit', 'hrm.activity.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Activity List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hrm.salary.index') }}"
                                class="nav-link {{ Route::is('hrm.salary.index', 'hrm.salary.create', 'hrm.salary.show') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Salary</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
