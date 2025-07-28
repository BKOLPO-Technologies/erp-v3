@php
  
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none align-items-center text-center">
    <span class="brand-text fw-light fs-5 text-white">
        <span class="text-uppercase">ERP</span> <strong class="text-uppercase">Super Admin</strong>
    </span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <img src="{{ Auth::user()->profile_image ? asset(Auth::user()->profile_image) : asset('Accounts/dist/img/avatar5.png') }}" width="30" height="30"
      class="user-image rounded-circle shadow" alt="User Image"> 

      <div class="info">
        <a href="{{ route('dashboard') }}" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> 
        <li class="nav-item menu-open">
          <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        <!-- ---General--- --> 
        <li class="nav-item {{ Route::is('users.index','users.create','users.edit','users.show','roles.index','roles.create','roles.edit','roles.show') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('users.index','users.create','users.edit','users.show','roles.index','roles.create','roles.edit','roles.show') ? 'active' : '' }}">
            <i class="nav-icon fas fa-building"></i>
            <p>
              General
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ Route::is('users.index','users.create','users.edit','users.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="nav-link {{ Route::is('roles.index','roles.create','roles.edit','roles.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Role List</p>
                </a>
              </li>
          </ul>
        </li>
        <!-- End---General -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>