@php
  $isProductsActive = Route::is('inventory.product.index', 'inventory.product.create', 'inventory.product.edit', 'inventory.product.view');
  $isSettingActive = Route::is('inventory.tag.index', 'inventory.tag.create', 'inventory.tag.edit', 'inventory.tag.show','inventory.brand.index', 'inventory.brand.create', 'inventory.brand.edit', 'inventory.brand.show','inventory.category.index', 'inventory.category.create', 'inventory.category.show', 'inventory.category.edit','inventory.unit.index', 'inventory.unit.create', 'inventory.unit.show', 'inventory.unit.edit', 'inventory.specification.index', 'inventory.specification.create', 'inventory.specification.show', 'inventory.specification.edit');
  $isCustomersActive = Route::is('inventory.customer.index', 'inventory.customer.create', 'inventory.customer.edit', 'inventory.customer.view');
  $isVendorActive = Route::is('inventory.vendor.index', 'inventory.vendor.create', 'inventory.vendor.edit', 'inventory.vendor.view');
  $isStockInWardActive = Route::is('inventory.stockinward.index', 'inventory.stockinward.create', 'inventory.stockinward.edit', 'inventory.stockinward.show');
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('inventory.dashboard') }}" class="brand-link text-decoration-none align-items-center text-center">
        <span class="brand-text fw-light fs-5 text-white">
            <span class="text-uppercase">ERP</span> <strong class="text-uppercase">Inventory</strong>
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
                <a href="{{ route('inventory.dashboard') }}" class="d-block">{{ Auth::user()->name ?? '' }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
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

                <!-- start product -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link {{ $isProductsActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
                        <p>
                            Products
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.product.index') }}" class="nav-link {{ Route::is('inventory.product.index', 'inventory.product.create', 'inventory.product.view', 'inventory.product.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Products</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- end product -->

                <!-- start setting -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link {{ $isSettingActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
                        <p>
                            Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.category.index') }}" class="nav-link {{ Route::is('inventory.category.index', 'inventory.category.create', 'inventory.category.show', 'inventory.category.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inventory.unit.index') }}" class="nav-link {{ Route::is('inventory.unit.index', 'inventory.unit.create', 'inventory.unit.show', 'inventory.unit.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Unit</p>
                            </a>
                        </li>
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
                        <li class="nav-item">
                            <a href="{{ route('inventory.specification.index') }}" class="nav-link {{ Route::is('inventory.specification.index', 'inventory.specification.create', 'inventory.specification.show', 'inventory.specification.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Specifications</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- end setting -->

                <!-- start customers -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link {{ $isCustomersActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
                        <p>
                            Customers
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.customer.index') }}" class="nav-link {{ Route::is('inventory.customer.index', 'inventory.customer.create', 'inventory.customer.view', 'inventory.customer.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Customers</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- end customers -->

                <!-- start vendors -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link {{ $isVendorActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
                        <p>
                            Vendors
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.vendor.index') }}" class="nav-link {{ Route::is('inventory.vendor.index', 'inventory.vendor.create', 'inventory.vendor.view', 'inventory.vendor.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Vendors</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- end vendors -->

                <!-- start stock inward -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link {{ $isStockInWardActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
                        <p>
                            Stock
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.stockinward.index') }}" class="nav-link {{ Route::is('inventory.stockinward.index', 'inventory.stockinward.create', 'inventory.stockinward.show', 'inventory.stockinward.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Stock Inward</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- end vendors -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
