@php
  // Determine active state for menu items
  $isReportActive = Route::is('accounts.report.index','accounts.report.trial.balance','accounts.report.balance.sheet','accounts.report.ledger.report','accounts.report.ledger.single.report','accounts.report.ledger.group.report','accounts.report.ledger.group.single.report','accounts.report.ledger.profit.loss','accounts.report.project.profit.loss','accounts.report.daybook','accounts.report.sales','accounts.report.purchases','accounts.report.purchases.sales','accounts.report.bills.payable','accounts.report.bills.receivable','accounts.report.groupwise.statement','accounts.report.receipts.payments');
  $isSupplierActive = Route::is('accounts.supplier.index','accounts.supplier.create','accounts.supplier.view','accounts.supplier.edit','accounts.supplier.products','accounts.supplier.transactions');
  $isClientActive = Route::is('accounts.client.index','accounts.client.create','accounts.client.view','accounts.client.edit','accounts.client.products','accounts.client.transactions');
  $isProjectActive = Route::is('accounts.projects.index', 'accounts.projects.create', 'accounts.projects.show', 'accounts.projects.edit', 'accounts.projects.sales');
  $isInvoiceActive = Route::is('accounts.project.receipt.payment.show');
  $isSalesActive = Route::is('accounts.index','accounts.create','accounts.edit','accounts.show','accounts.outcoming.chalan.index','accounts.outcoming.chalan.create','accounts.outcoming.chalan.show','accounts.outcoming.chalan.edit','accounts.receipt.payment.index','accounts.receipt.payment.create','accounts.stock.out','accounts.stock.out.view');
  $isPurchaseActive = Route::is('workorders.index','workorders.create','workorders.edit','workorders.show','incoming.chalan.index','incoming.chalan.create','incoming.chalan.show','incoming.chalan.edit');
  $isAccountMasterActive = Route::is('accounts.chart_of_accounts.*', 'accounts.ledger.*', 'accounts.ledger.group.*', 'accounts.ledger.sub.group.*', 'accounts.client.index','accounts.client.create','accounts.client.view','accounts.client.edit','accounts.client.products','accounts.client.transactions', 'accounts.supplier.index','accounts.supplier.create','accounts.supplier.view','accounts.supplier.edit','accounts.supplier.products','accounts.supplier.transactions');
  // new
  $isTransactionsActive = Route::is('accounts.journal-voucher.*','accounts.purchase.invoice.index','accounts.purchase.invoice.create','accounts.purchase.invoice.show','accounts.purchase.invoice.edit','accounts.purchase.order.index','accounts.purchase.order.create','accounts.purchase.order.edit','accounts.purchase.order.create','accounts.sale.index','accounts.sale.create','accounts.sale.show','accounts.sale.edit','workorders.index','workorders.create','workorders.edit','workorders.show','incoming.chalan.index','accounts.incoming.chalan.create','accounts.incoming.chalan.show','accounts.incoming.chalan.edit','accounts.sale.payment.index','accounts.sale.payment.create','accounts.stock.in','accounts.stock.in.view', 'accounts.sale.payment.show','accounts.project.receipt.payment.index', 'accounts.project.receipt.payment.create', 'accounts.project.receipt.payment.show','accounts.contra-voucher.create','accounts.contra-voucher.index','accounts.contra-voucher.edit');
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('accounts.dashboard') }}" class="brand-link text-decoration-none align-items-center text-center">
    <span class="brand-text fw-light fs-5 text-white">
        <span class="text-uppercase">ERP</span> <strong class="text-uppercase">Accounts</strong>
    </span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <img src="{{ Auth::user()->profile_image ? asset(Auth::user()->profile_image) : asset('Accounts/dist/img/avatar5.png') }}" width="30" height="30"
      class="user-image rounded-circle shadow" alt="User Image"> 
      <div class="info">
        <a href="{{ route('accounts.dashboard') }}" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      @can('dashboard-menu')  
        <li class="nav-item menu-open">
          <a href="{{ route('accounts.dashboard') }}" class="nav-link {{ Route::is('accounts.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        @endcan
        <!-- =================== Start Accounts Main Menu =================== -->
        @can('account-menu')  
        <li class="nav-item {{ $isAccountMasterActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isAccountMasterActive ? 'active' : '' }}">
            <i class="nav-icon fas fa-university"></i>
            <p>
              Account Master
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <!-- === Chart of Accounts Submenu === -->
          @can('ledger-menu')  
          <ul class="nav nav-treeview shadow-lg">
            <li class="nav-item {{ Route::is('accountsledger.*', 'accounts.ledger.group.*', 'accounts.ledger.sub.group.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ Route::is('ledger.*', 'accounts.ledger.group.*', 'accounts.ledger.sub.group.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                  Chart of Accounts
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('ledger-group-list')  
                <li class="nav-item">
                  <a href="{{ route('accounts.ledger.group.index') }}" class="nav-link {{ Route::is('accounts.ledger.group.*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Group List</p>
                  </a>
                </li>
                @endcan
                @can('ledger-sub-group-list')  
                <li class="nav-item">
                  <a href="{{ route('accounts.ledger.sub.group.index') }}" class="nav-link {{ Route::is('accounts.ledger.sub.group.*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Sub Group List</p>
                  </a>
                </li>
                @endcan
                @can('ledger-list')  
                <li class="nav-item">
                  <a href="{{ route('accounts.ledger.index') }}" class="nav-link {{ Route::is('accounts.ledger.index', 'accounts.ledger.create', 'accounts.ledger.edit', 'accounts.ledger.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ledger List</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
          </ul>
          @endcan
          <!-- === End Chart of Accounts Submenu === -->

          <!-- === Customers === -->
          <ul class="nav nav-treeview shadow-lg">
            @can('customer-menu')  
            <li class="nav-item {{ $isClientActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ $isClientActive ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>
                    Customers
                    <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
            
              <ul class="nav nav-treeview">
                @can('customer-list')  
                <li class="nav-item">
                    <a href="{{ route('accounts.client.index') }}" class="nav-link {{ Route::is('accounts.client.index') || Route::is('accounts.client.create') || Route::is('accounts.client.view') || Route::is('accounts.client.edit') || Route::is('accounts.client.products')  || Route::is('accounts.client.transactions') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Customers</p>
                    </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
          </ul>
          <!-- === End of Customers === -->

          <!-- === Vendors === -->
          <ul class="nav nav-treeview shadow-lg">
            @can('vendor-menu')  
            <li class="nav-item {{ $isSupplierActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ $isSupplierActive ? 'active' : '' }}">
                  <i class="nav-icon fas fa-truck"></i>
                  <p>
                    Vendors
                    <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
          
              <ul class="nav nav-treeview">
                @can('vendor-list')  
                <li class="nav-item">
                  <a href="{{ route('accounts.supplier.index') }}" class="nav-link {{ Route::is('accounts.supplier.index') || Route::is('accounts.supplier.view') || Route::is('accounts.supplier.edit') || Route::is('accounts.supplier.create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Manage Vendors</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
          </ul>
          <!-- === End of Vendors === -->

        </li>
        @endcan
        <!-- =================== End Accounts Main Menu =================== -->
        
        <!-- accounts -->
      @can('project-menu')
       <li class="nav-item {{ $isProjectActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isProjectActive ? 'active' : '' }}">
              <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
              <p>
                  Projects
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>
          
          <ul class="nav nav-treeview">
             @can('project-list')  
              <li class="nav-item">
                  <a href="{{ route('accounts.projects.index') }}" class="nav-link {{ Route::is('accounts.projects.index', 'accounts.projects.create', 'accounts.projects.show', 'accounts.projects.edit', 'accounts.projects.sales') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Manage Projects</p>
                  </a>
              </li>
              @endcan
          </ul>
        </li>
        @endcan
        
      

        <!-- Transactions -->
        @can('transaction-menu')
        <li class="nav-item {{ $isTransactionsActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isTransactionsActive ? 'active' : '' }}">
            <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                  Transactions
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>
          
          <ul class="nav nav-treeview">
            @can('receipt-list')
            <li class="nav-item">
                <a href="{{ route('accounts.project.receipt.payment.index') }}" class="nav-link {{ Route::is('accounts.project.receipt.payment.index','accounts.project.receipt.payment.create','accounts.project.receipt.payment.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Receipt</p>
                </a>
            </li>
            @endcan
            @can('payment-list')
            <li class="nav-item">
                <a href="{{ route('accounts.sale.payment.index') }}" class="nav-link {{ Route::is('accounts.sale.payment.index','accounts.sale.payment.create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Payment</p>
                </a>
            </li>
            @endcan
            @can('journal-list')
            <li class="nav-item">
              <a href="{{ route('accounts.journal-voucher.index') }}" class="nav-link {{ Route::is('accounts.journal-voucher.index', 'accounts.journal-voucher.create', 'accounts.journal-voucher.manually.capital.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Journal</p>
              </a>
            </li>
            @endcan
            @can('contra-list')
            <li class="nav-item">
              <a href="{{ route('accounts.contra-voucher.index') }}" class="nav-link {{ Route::is('accounts.contra-voucher.index','accounts.contra-voucher.create','accounts.contra-voucher.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contra</p>
              </a>
            </li>
            @endcan
            <li class="nav-item">
              <a href="#"  onclick="comingSoon()" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Journal</p>
              </a>
            </li>
            @can('purchases-list')
            <li class="nav-item">
              <a href="{{ route('accounts.purchase.order.index') }}" class="nav-link {{ Route::is('accounts.purchase.order.index','accounts.purchase.order.create', 'accounts.purchase.order.show', 'accounts.purchase.order.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Order</p>
              </a>
            </li>
            @endcan
            @can('purchases-invoice-list')
            <li class="nav-item">
              <a href="{{ route('accounts.purchase.invoice.index') }}" class="nav-link {{ Route::is('accounts.purchase.invoice.index','accounts.purchase.invoice.create', 'accounts.purchase.invoice.show', 'accounts.purchase.invoice.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Invoice</p>
              </a>
            </li>
            @endcan
            @can('sales-invoice-list')
            <li class="nav-item">
              <a href="{{ route('accounts.sale.index') }}" class="nav-link {{ Route::is('accounts.sale.index','accounts.sale.create','accounts.sale.show','accounts.sale.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Invoice</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcan

        <!-- Sales -->
        @can('sales-menu')
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>
              Sales
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <!-- Proforma Invoice -->
            @can('sales-proforma-invoice')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Proforma Invoice</p>
              </a>
            </li>
            @endcan

            <!-- Sales Order -->
            @can('sales-order')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Order</p>
              </a>
            </li>
            @endcan

            <!-- Delivery Note -->
            @can('sales-delivery-note')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Delivery Note</p>
              </a>
            </li>
            @endcan

            <!-- Invoice/Bill -->
            {{-- <li class="nav-item">
              <a href="{{ route('accounts.sale.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Invoice/Bill</p>
              </a>
            </li> --}}

            <!-- Sales Return -->
            @can('sales-return')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Return</p>
              </a>
            </li>
            @endcan

            <!-- Warranty In -->
            @can('sales-warranty')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Warranty In</p>
              </a>
            </li>
            @endcan

            <!-- Customers -->
            @can('sales-customers')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Customers</p>
              </a>
            </li>
            @endcan

            @can('sales-salesman')
            <!-- Salesman Performance -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Salesman Performance</p>
              </a>
            </li>
            @endcan

            <!-- Salesman-wise Receivable -->
            @can('sales-receivable')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Salesman Receivable</p>
              </a>
            </li>
            @endcan

            <!-- Sales Import -->
            @can('sales-import')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Import</p>
              </a>
            </li>
            @endcan

            <!-- Team -->
            @can('sales-team')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Team</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcan

        <!-- Purchase -->
        @can('purchases-menu')
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-bag"></i>
            <p>
              Purchase
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <!-- Purchase Requisition -->
            @can('purchases-requisition')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Requisition</p>
              </a>
            </li>
            @endcan

            <!-- Purchase Quotation -->
            @can('purchases-quotation')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Quotation</p>
              </a>
            </li>
            @endcan

            <!-- Receipt Note -->
            @can('purchases-receipt-note')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Receipt Note</p>
              </a>
            </li>
            @endcan

            <!-- Purchase Return -->
            @can('purchases-return')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Return</p>
              </a>
            </li>
            @endcan

            <!-- Purchase Import -->
            @can('purchases-import')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Import</p>
              </a>
            </li>
            @endcan

            <!-- Vendors -->
            @can('purchases-vendors')
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Vendors</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcan



        <!-- Report Menu -->
        @can('report-menu')  
        <li class="nav-item {{ $isReportActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isReportActive ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>
                Accounting Report
                <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <!-- Accounts Submenu -->
            @can('report-trial-balance')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.trial.balance') }}" class="nav-link {{ Route::is('accounts.report.trial.balance') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Trial Balance</p>
              </a>
            </li>
            @endcan
            @can('report-balance-sheet')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.balance.sheet') }}" class="nav-link {{ Route::is('accounts.report.balance.sheet') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Balance Sheet</p>
              </a>
            </li>
            @endcan
            @can('report-profit-loss')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.ledger.profit.loss') }}" class="nav-link {{ Route::is('accounts.report.ledger.profit.loss') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profit and Loss</p>
              </a>
            </li>
            @endcan
            @can('report-receipts-payments')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.receipts.payments') }}" class="nav-link {{ Route::is('accounts.report.receipts.payments') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Receipts & Payments</p>
              </a>
            </li>
            @endcan
            @can('report-daybook')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.daybook') }}" class="nav-link {{ Route::is('accounts.report.daybook') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daybook</p>
              </a>
            </li>
            @endcan
            @can('report-groupwise-statement')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.groupwise.statement') }}" class="nav-link {{ Route::is('accounts.report.groupwise.statement') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Groupwise Statement</p>
              </a>
            </li>
            @endcan
            @can('report-bills-receivable')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.bills.receivable') }}" class="nav-link {{ Route::is('accounts.report.bills.receivable') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bills Receivable</p>
              </a>
            </li>
            @endcan
            @can('report-bills-payable')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.bills.payable') }}" class="nav-link {{ Route::is('accounts.report.bills.payable') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bills Payable</p>
              </a>
            </li>
            @endcan
            @can('report-purchases-sales')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.purchases.sales') }}" class="nav-link {{ Route::is('accounts.report.purchases.sales') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase And Sales</p>
              </a>
            </li>
            @endcan
            @can('report-sales')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.sales') }}" class="nav-link {{ Route::is('accounts.report.sales') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Report</p>
              </a>
            </li>
            @endcan
            @can('report-purchases')  
            <li class="nav-item">
              <a href="{{ route('accounts.report.purchases') }}" class="nav-link {{ Route::is('accounts.report.purchases') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Report</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcan

        <!-- ---Company--- -->
        @can('company-menu')  
        <li class="nav-item {{ Route::is('accounts.company.index','accounts.company.create','accounts.company.edit','accounts.company.show','accounts.users.index','accounts.users.create','accounts.users.edit','accounts.users.show','accounts.roles.index','accounts.roles.create','accounts.roles.edit','accounts.roles.show') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('accounts.company.index','accounts.company.create','accounts.company.edit','accounts.company.show','accounts.users.index','accounts.users.create','accounts.users.edit','accounts.users.show','accounts.roles.index','accounts.roles.create','accounts.roles.edit','accounts.roles.show') ? 'active' : '' }}">
            <i class="nav-icon fas fa-building"></i>
            <p>
              Company
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('company-list') 
            <li class="nav-item">
              <a href="{{ route('accounts.company.index') }}" class="nav-link {{ Route::is('accounts.company.index','accounts.company.create','accounts.company.edit','accounts.company.show') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Company List</p>
              </a>
            </li> 
            @endcan
                 {{-- @can('user-menu')  
              <li class="nav-item">
                <a href="{{ route('accounts.users.index') }}" class="nav-link {{ Route::is('accounts.users.index','accounts.users.create','accounts.users.edit','accounts.users.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User List</p>
                </a>
              </li>
              @endcan
              @can('role-list')
              <li class="nav-item">
                <a href="{{ route('accounts.roles.index') }}" class="nav-link {{ Route::is('accounts.roles.index','accounts.roles.create','accounts.roles.edit','accounts.roles.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Role List</p>
                </a>
              </li>
              @endcan --}}
          </ul>
        </li>
        @endcan
        <!-- End---Company -->

        <!-- ---Branch--- -->
        @can('branch-menu')  
        <li class="nav-item {{ Route::is('accounts.branch.index', 'accounts.branch.create', 'accounts.branch.edit','accounts.branch.show') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('accounts.branch.index', 'accounts.branch.create', 'accounts.branch.edit','accounts.branch.show') ? 'active' : '' }}">
            <i class="nav-icon fas fa-project-diagram"></i>
            <p>
              Branch
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('branch-list')  
            <li class="nav-item">
              <a href="{{ route('accounts.branch.index') }}" class="nav-link {{ Route::is('accounts.branch.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Branch List</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcan
        <!-- End---Branch -->

        <!-- Product -->
        @can('product-menu')  
        <li class="nav-item {{ Route::is('accounts.product*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('accounts.product*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-box"></i>
            <p>
              Product
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            @can('product-menu')  
            <li class="nav-item">
              <a href="{{ route('accounts.product.index') }}" class="nav-link {{ Route::is('accounts.product.index') || Route::is('accounts.product.create') || Route::is('accounts.product.edit') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Manage Product</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcan

        @can('setting-menu')  
        <li class="nav-item {{ Route::is('accounts.company-information.index','accounts.company-information.import','accounts.company-information.export', 'accounts.category*', 'accounts.unit*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('accounts.company-information.index','accounts.company-information.import','accounts.company-information.export', 'accounts.category*', 'accounts.unit*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-left right"></i>
              </p>
          </a>
          <ul class="nav nav-treeview">
            @can('setting-category-list')  
            <li class="nav-item">
              <a href="{{ route('accounts.category.index') }}" class="nav-link {{ Route::is('accounts.category.index') || Route::is('accounts.category.create') || Route::is('accounts.category.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Products Category</p>
              </a>
            </li>
            @endcan
            @can('setting-unit-list')  
            <li class="nav-item">
              <a href="{{ route('accounts.unit.index') }}" class="nav-link {{ Route::is('accounts.unit.index') || Route::is('accounts.unit.create') || Route::is('accounts.unit.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Products Unit</p> 
              </a>
            </li>
            @endcan
            @can('setting-import')  
            <li class="nav-item">
              <a href="{{ route('accounts.company-information.import') }}" class="nav-link {{ Route::is('company-information.import') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Import</p> 
              </a>
            </li>
            @endcan
            @can('setting-export')  
            <li class="nav-item">
              <a href="{{ route('accounts.company-information.export') }}" class="nav-link {{ Route::is('company-information.export') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Export</p> 
              </a>
            </li>
            @endcan
            @can('setting-configuration')  
            <li class="nav-item">
              <a href="{{ route('accounts.company-information.index') }}" class="nav-link {{ Route::is('accounts.company-information.index') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Configuration</p> 
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