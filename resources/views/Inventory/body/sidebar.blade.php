@php
  $isProductsActive = Route::is('inventory.product.index', 'inventory.product.create', 'inventory.product.edit', 'inventory.product.view');
  $isSettingActive = Route::is('inventory.tag.index', 'inventory.tag.create', 'inventory.tag.edit', 'inventory.tag.show','inventory.brand.index', 'inventory.brand.create', 'inventory.brand.edit', 'inventory.brand.show');
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('inventory.dashboard') }}" class="brand-link">
        <span class="brand-text fw-light">ERP <strong>Management System</strong></span>
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
                <a href="{{ route('inventory.dashboard') }}" class="d-block">{{ Auth::user()->name ?? '' }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @can('dashboard-menu')
                   <!-- start dashboard -->
                    <li class="nav-item menu-open">
                        <a href="{{ route('inventory.dashboard') }}"
                            class="nav-link {{ Route::is('inventory.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <!-- end dashboard -->
                @endcan

                <!-- start product -->
                <li class="nav-item {{ $isProductsActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isProductsActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
                        <p>
                            Products
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.product.index') }}" class="nav-link {{ Route::is('inventory.product.index', 'inventory.product.create', 'inventory.product.show', 'inventory.product.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Products</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- end product -->

                <!-- start setting -->
                <li class="nav-item {{ $isSettingActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isSettingActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
                        <p>
                            Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.tag.index') }}" class="nav-link {{ Route::is('inventory.tag.index', 'inventory.tag.create', 'inventory.tag.show', 'inventory.tag.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Tags</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inventory.brand.index') }}" class="nav-link {{ Route::is('inventory.brand.index', 'inventory.brand.create', 'inventory.brand.show', 'inventory.brand.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Brands</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- end setting -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
