<?php

use App\Models\Accounts\LedgerSubGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Accounts\BankController;
use App\Http\Controllers\Accounts\RoleController;
use App\Http\Controllers\Accounts\UserController;
use App\Http\Controllers\Accounts\AdminController;
use App\Http\Controllers\Accounts\BranchController;
use App\Http\Controllers\Accounts\LedgerController;
use App\Http\Controllers\Accounts\ReportController;
use App\Http\Controllers\Accounts\CompanyController;
use App\Http\Controllers\Accounts\InvoiceController;
use App\Http\Controllers\Accounts\JournalController;
use App\Http\Controllers\Accounts\ContraController;
use App\Http\Controllers\Accounts\CustomerController;
use App\Http\Controllers\Accounts\EmployeeController;
use App\Http\Controllers\Accounts\SupplierController;
use App\Http\Controllers\Accounts\LedgerGroupController;
use App\Http\Controllers\Accounts\PaymentMethodController;
use App\Http\Controllers\Accounts\UnitController;
use App\Http\Controllers\Accounts\LedgerSubGroupController;
use App\Http\Controllers\Accounts\SalesController;
use App\Http\Controllers\Accounts\StockController;
use App\Http\Controllers\Accounts\ClientController;
use App\Http\Controllers\Accounts\ProductController;
use App\Http\Controllers\Accounts\ProjectController;
use App\Http\Controllers\Accounts\CompanyInformationController;
use App\Http\Controllers\Accounts\CategoryController;
use App\Http\Controllers\Accounts\PurchaseController;
use App\Http\Controllers\Accounts\PurchaseOrderController;
use App\Http\Controllers\Accounts\PurchaseInvoiceController;
use App\Http\Controllers\Accounts\QuotationController;
use App\Http\Controllers\Accounts\WorkOrderController;
use App\Http\Controllers\Accounts\SalePaymentController;
use App\Http\Controllers\Accounts\SaleReceiptController;
use App\Http\Controllers\Accounts\IncomingChalanController;
use App\Http\Controllers\Accounts\OutComingChalanController;
use App\Http\Controllers\Accounts\ProductSaleReceiveController;


Route::prefix('accounts')->as('accounts.')->group(function () {
    /* =============== Start Admin Route  ============= */
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');
        Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('logout');

        /* ==================== Branch =================== */
        Route::prefix('branch')->as('branch.')->group(function () {
            Route::get('/', [BranchController::class, 'index'])->name('index')->middleware('can:branch-list');
            Route::get('/create', [BranchController::class, 'create'])->name('create')->middleware('can:branch-create');
            Route::post('/store', [BranchController::class, 'store'])->name('store');
            Route::post('/store2', [BranchController::class, 'store2'])->name('store2');
            Route::get('/edit/{id}', [BranchController::class, 'edit'])->name('edit')->middleware('can:branch-edit');
            Route::post('/update/{id}', [BranchController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [BranchController::class, 'destroy'])->name('delete')->middleware('can:branch-delete');
            Route::get('/view/{id}', [BranchController::class, 'show'])->name('show')->middleware('can:branch-view');
        }); 

        Route::prefix('company')->group(function () {
            Route::get('/', [CompanyController::class, 'index'])->name('company.index')->middleware('can:company-list');
            Route::get('/create', [CompanyController::class, 'create'])->name('company.create')->middleware('can:company-create');
            Route::post('/store', [CompanyController::class, 'store'])->name('company.store');
            Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit')->middleware('can:company-edit');
            Route::post('/update/{id}', [CompanyController::class, 'update'])->name('company.update');
            Route::get('/delete/{id}', [CompanyController::class, 'destroy'])->name('company.delete')->middleware('can:company-delete');
            Route::get('/view/{id}', [CompanyController::class, 'show'])->name('company.show')->middleware('can:company-view');

        });

        /* ==================== Bank  =================== */
        Route::prefix('bank')->as('bank.')->group(function () {
            Route::get('/', [BankController::class, 'index'])->name('index');
            Route::get('/create', [BankController::class, 'create'])->name('create');
            Route::post('/store', [BankController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [BankController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [BankController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [BankController::class, 'destroy'])->name('delete');
            Route::get('/view/{id}', [BankController::class, 'show'])->name('show');
        }); 

        /* ==================== payment method  =================== */
        Route::prefix('payment')->as('payment.')->group(function () {
            Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
            Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
            Route::post('/store', [PaymentMethodController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [PaymentMethodController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [PaymentMethodController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [PaymentMethodController::class, 'destroy'])->name('delete');
            Route::get('/view/{id}', [PaymentMethodController::class, 'show'])->name('show');
        }); 

        /* ==================== ledger category  =================== */
        Route::prefix('ledger')->as('ledger.')->group(function () {
            Route::get('/', [LedgerController::class, 'index'])->name('index')->middleware('can:ledger-list');
            Route::get('/create', [LedgerController::class, 'create'])->name('create')->middleware('can:ledger-create');
            Route::post('/store', [LedgerController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [LedgerController::class, 'edit'])->name('edit')->middleware('can:ledger-edit');
            Route::post('/update/{id}', [LedgerController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [LedgerController::class, 'destroy'])->name('delete')->middleware('can:ledger-delete');
            Route::get('/view/{id}', [LedgerController::class, 'show'])->name('show')->middleware('can:ledger-view');
            Route::get('/ledger/import/format', [LedgerController::class, 'downloadFormat'])->name('import.format');
            Route::post('/import', [LedgerController::class, 'import'])->name('import');

            
            
        });

        /* ==================== ledger group category  =================== */
        Route::prefix('ledger-group')->as('ledger.group.')->group(function () {
            Route::get('/', [LedgerGroupController::class, 'index'])->name('index')->middleware('can:ledger-group-list');
            Route::get('/create', [LedgerGroupController::class, 'create'])->name('create')->middleware('can:ledger-group-create');
            Route::post('/store', [LedgerGroupController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [LedgerGroupController::class, 'edit'])->name('edit')->middleware('can:ledger-group-edit');
            Route::post('/update/{id}', [LedgerGroupController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [LedgerGroupController::class, 'destroy'])->name('delete')->middleware('can:ledger-group-delete');
            Route::get('/view/{id}', [LedgerGroupController::class, 'show'])->name('show')->middleware('can:ledger-group-view');
            Route::get('/import/format', [LedgerGroupController::class, 'downloadFormat'])->name('import.format');
            Route::post('/import', [LedgerGroupController::class, 'import'])->name('import');
        }); 

        /* ==================== ledger group category  =================== */
        Route::prefix('ledger-sub-group')->as('ledger.sub.group.')->group(function () {
            Route::get('/', [LedgerSubGroupController::class, 'index'])->name('index');
            Route::get('/create', [LedgerSubGroupController::class, 'create'])->name('create');
            Route::post('/store', [LedgerSubGroupController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [LedgerSubGroupController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [LedgerSubGroupController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [LedgerSubGroupController::class, 'destroy'])->name('destroy');
            Route::get('/view/{id}', [LedgerSubGroupController::class, 'show'])->name('show');
            Route::get('/import/format', [LedgerSubGroupController::class, 'downloadFormat'])->name('import.format');
            Route::post('/import', [LedgerSubGroupController::class, 'import'])->name('import');
        }); 

        /* ==================== journal voucher  =================== */
        Route::prefix('journal-voucher')->as('journal-voucher.')->group(function () {
            Route::get('/', [JournalController::class, 'index'])->name('index')->middleware('can:journal-list');
            Route::get('/excel', [JournalController::class, 'excel'])->name('excel');
            Route::get('/create', [JournalController::class, 'create'])->name('create')->middleware('can:journal-create');
            Route::get('/contra/create', [JournalController::class, 'contracreate'])->name('contracreate')->middleware('can:journal-create');
            Route::get('/create-manual', [JournalController::class, 'manuallyCreate'])->name('manually.create');
            Route::get('/create-manual-capital', [JournalController::class, 'manuallyCapitalCreate'])->name('manually.capital.create');
            Route::post('/store', [JournalController::class, 'store'])->name('store');
            Route::post('/capital/store', [JournalController::class, 'capitalstore'])->name('capital.store');
            Route::get('/edit/{id}', [JournalController::class, 'edit'])->name('edit')->middleware('can:journal-edit');
            Route::post('/update/{id}', [JournalController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [JournalController::class, 'destroy'])->name('delete')->middleware('can:journal-delete');
            Route::get('/view/{id}', [JournalController::class, 'show'])->name('show')->middleware('can:journal-view');
            Route::get('/get-branches/{companyId}', [JournalController::class, 'getBranchesByCompany']);
            Route::get('/import/format', [JournalController::class, 'downloadFormat'])->name('import.format');
            Route::post('/import', [JournalController::class, 'import'])->name('import');
            Route::post('/update-status', [JournalController::class, 'updateStatus'])->name('update-status');


        }); 

        /* ==================== contra voucher  =================== */
        Route::prefix('contra-voucher')->as('contra-voucher.')->group(function () {
            Route::get('/', [ContraController::class, 'index'])->name('index');
            Route::get('/excel', [ContraController::class, 'excel'])->name('excel');
            Route::get('/create', [ContraController::class, 'create'])->name('create');
            Route::post('/store', [ContraController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ContraController::class, 'edit'])->name('edit')->middleware('can:journal-edit');
            Route::post('/update/{id}', [ContraController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [ContraController::class, 'destroy'])->name('delete')->middleware('can:journal-delete');
            Route::get('/view/{id}', [ContraController::class, 'show'])->name('show')->middleware('can:journal-view');
            Route::get('/get-branches/{companyId}', [JournalController::class, 'getBranchesByCompany']);
            Route::get('/import/format', [JournalController::class, 'downloadFormat'])->name('import.format');
            Route::post('/import', [JournalController::class, 'import'])->name('import');
            Route::post('/update-status', [JournalController::class, 'updateStatus'])->name('update-status');

        }); 

        /* ==================== Report =================== */
        Route::prefix('report/accounts')->as('report.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index')->middleware('can:report-list');
            Route::get('/trial/balance', [ReportController::class, 'trialBalance'])->name('trial.balance')->middleware('can:trial-balnce-report');
            Route::get('/balance/sheet', [ReportController::class, 'balanceSheet'])->name('balance.sheet')->middleware('can:balance-shit-report');
            Route::get('/ledger', [ReportController::class, 'ledgerList'])->name('ledger.report');
            Route::get('/ledger/report/{id}', [ReportController::class, 'ledgerReport'])->name('ledger.single.report');
            Route::get('/ledger/group', [ReportController::class, 'ledgerGroupList'])->name('ledger.group.report');
            Route::get('/ledger/group/report/{id}', [ReportController::class, 'ledgerGroupReport'])->name('ledger.group.single.report');
            Route::get('/ledger/pay-slip/{id}', [ReportController::class, 'getLedgerPaySlip'])->name('ledger.pay.slip');
            Route::get('/ledger/profit/loss', [ReportController::class, 'ledgerProfitLoss'])->name('ledger.profit.loss');
            Route::get('/project/profit/loss', [ReportController::class, 'ProjectProfitLoss'])->name('project.profit.loss');
            Route::get('/daybook', [ReportController::class, 'showDayBook'])->name('daybook');
            Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('/purchases', [ReportController::class, 'purchasesReport'])->name('purchases');
            Route::get('/purchases/sales', [ReportController::class, 'purchasesSalesReport'])->name('purchases.sales');
            Route::get('/bills/payable', [ReportController::class, 'billsPayableReport'])->name('bills.payable');
            Route::get('/bills/receivable', [ReportController::class, 'billsReceivableReport'])->name('bills.receivable');
            Route::get('/group/wise', [ReportController::class, 'groupwiseReport'])->name('groupwise.statement');
            Route::get('/receipts/payments', [ReportController::class, 'receiptPaymentReport'])->name('receipts.payments');

        });



        /* ==================== Invoice =================== */
        Route::prefix('invoices')->group(function () {
            Route::get('/', [InvoiceController::class, 'AdminInvoiceIndex'])->name('invoice');
            
            Route::get('/create', [InvoiceController::class, 'AdminInvoiceCreate'])->name('invoiceCreate');
        });



        /* ==================== supplier =================== */
        Route::prefix('supplier')->group(function () {
            Route::get('/', [SupplierController::class, 'AdminSupplierIndex'])->name('supplier.index');
            Route::get('/create', [SupplierController::class, 'AdminSupplierCreate'])->name('supplier.create');
            Route::post('/storeSupplier2', [SupplierController::class, 'AdminSupplierStore2'])->name('supplier2.store');
            Route::post('/storeSupplier', [SupplierController::class, 'AdminSupplierStore'])->name('supplier.store');
            Route::get('/view/{id}', [SupplierController::class, 'AdminSupplierView'])->name('supplier.view');
            Route::get('/edit/{id}', [SupplierController::class, 'AdminSupplierEdit'])->name('supplier.edit');
            Route::put('/update/{id}', [SupplierController::class, 'AdminSupplierUpdate'])->name('supplier.update');
            //Route::delete('/delete/{id}', [SupplierController::class, 'AdminSupplierDestroy'])->name('supplier.destroy');
            Route::get('/delete/{id}', [SupplierController::class, 'AdminSupplierDestroy'])->name('supplier.destroy');
            Route::get('/products/{supplier}', [SupplierController::class, 'viewProducts'])->name('supplier.products');
            Route::get('/transactions/{supplier}', [SupplierController::class, 'viewTransactions'])->name('supplier.transactions');
        });

        /* ==================== customers =================== */
        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerController::class, 'AdminCustomerIndex'])->name('customer.index');
            Route::get('/create', [CustomerController::class, 'AdminCustomerCreate'])->name('customer.create');
            Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');

            Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
            Route::put('/{id}/update', [CustomerController::class, 'update'])->name('customer.update');
        });

        /* ==================== employee =================== */
        Route::prefix('employee')->group(function () {
            Route::get('/', [EmployeeController::class, 'AdminEmployeeIndex'])->name('employee.index');
            Route::get('/add', [EmployeeController::class, 'AdminEmployeeAdd'])->name('employee.add');
            Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
        });

    

        /* ==================== Company Information =================== */
        Route::prefix('company-information')->as('company-information.')->group(function () {
            Route::get('/', [CompanyInformationController::class, 'index'])->name('index')->middleware('can:setting-information');
            Route::get('edit/{id}', [CompanyInformationController::class, 'edit'])->name('edit')->middleware('can:setting-information-edit');
            Route::post('update/{id}', [CompanyInformationController::class, 'update'])->name('update');
            Route::get('import', [CompanyInformationController::class, 'import'])->name('import');
            Route::get('export', [CompanyInformationController::class, 'export'])->name('export');

            Route::get('ledgerExport', [CompanyInformationController::class, 'ledgerExport'])->name('ledgerExport');
            Route::get('ledgerGroupExport', [CompanyInformationController::class, 'ledgerGroupExport'])->name('ledgerGroupExport');
            Route::get('journalExport', [CompanyInformationController::class, 'journalExport'])->name('journalExport');
        });

        /* ==================== Role and User Management =================== */
        Route::resource('roles', RoleController::class) ->middleware([
            'can:role-list',   
            'can:role-create',  
            'can:role-edit',   
            'can:role-delete',
            'can:role-view',
        ]);
        Route::get('roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.delete');
        Route::resource('users', UserController::class)->middleware([
            'can:user-list',   
            'can:user-create',  
            'can:user-edit',   
            'can:user-delete',   
            'can:user-view',   
        ]);
        Route::get('users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');

        /* ==================== Category =================== */
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'AdminCategoryIndex'])->name('category.index');
            Route::get('/create', [CategoryController::class, 'AdminCategoryCreate'])->name('category.create');
            Route::post('/storeCategory', [CategoryController::class, 'AdminCategoryStore'])->name('category.store');
            Route::post('/storeCategory2', [CategoryController::class, 'AdminCategoryStore2'])->name('category.store2');
            Route::get('/edit/{id}', [CategoryController::class, 'AdminCategoryEdit'])->name('category.edit');
            Route::put('/update/{id}', [CategoryController::class, 'AdminCategoryUpdate'])->name('category.update');
            Route::get('/delete/{id}', [CategoryController::class, 'AdminCategoryDestroy'])->name('category.destroy');
        });

        /* ==================== Unit =================== */
        Route::prefix('unit')->group(function () {
            Route::get('/', [UnitController::class, 'AdminUnitIndex'])->name('unit.index');
            Route::get('/create', [UnitController::class, 'AdminUnitCreate'])->name('unit.create');
            Route::post('/storeUnit', [UnitController::class, 'AdminUnitStore'])->name('unit.store');
            Route::post('/storeUnit2', [UnitController::class, 'AdminUnitStore2'])->name('unit.store2');
            Route::get('/edit/{id}', [UnitController::class, 'AdminUnitEdit'])->name('unit.edit');
            Route::put('/update/{id}', [UnitController::class, 'AdminUnitUpdate'])->name('unit.update');
            Route::get('/delete/{id}', [UnitController::class, 'AdminUnitDestroy'])->name('unit.destroy');
        });

        /* ==================== Product =================== */
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'AdminProductIndex'])->name('product.index');
            Route::get('/create', [ProductController::class, 'AdminProductCreate'])->name('product.create');
            Route::post('/storeProduct', [ProductController::class, 'AdminProductStore'])->name('product.store');
            Route::get('/view/{id}', [ProductController::class, 'AdminProductView'])->name('product.view');
            Route::get('/edit/{id}', [ProductController::class, 'AdminProductEdit'])->name('product.edit');
            Route::put('/update/{id}', [ProductController::class, 'AdminProductUpdate'])->name('product.update');
            Route::get('/delete/{id}', [ProductController::class, 'AdminProductDestroy'])->name('product.destroy');
            Route::get('/products-by-category/{categoryId}', [ProductController::class, 'getProductsByCategory'])->name('products.by.category');
        });

        /* ==================== client =================== */
        Route::prefix('client')->group(function () {
            Route::get('/', [ClientController::class, 'AdminClientIndex'])->name('client.index');
            Route::get('/create', [ClientController::class, 'AdminClientCreate'])->name('client.create');
            Route::post('/storeClient', [ClientController::class, 'AdminClientStore'])->name('client.store');
            Route::post('/storeClient2', [ClientController::class, 'AdminClientStore2'])->name('client2.store');
            Route::get('/view/{id}', [ClientController::class, 'AdminClientView'])->name('client.view');
            Route::get('/edit/{id}', [ClientController::class, 'AdminClientEdit'])->name('client.edit');
            Route::put('/update/{id}', [ClientController::class, 'AdminClientUpdate'])->name('client.update');
            Route::get('/delete/{id}', [ClientController::class, 'AdminClientDestroy'])->name('client.destroy');

            Route::get('/products/{client}', [ClientController::class, 'viewProducts'])->name('client.products');
            Route::get('/transactions/{client}', [ClientController::class, 'viewTransactions'])->name('client.transactions');
        });

        /* ==================== purchase =================== */
        Route::prefix('purchase')->group(function () {
            Route::get('/', [PurchaseController::class, 'index'])->name('purchase.index');
            Route::get('/create', [PurchaseController::class, 'AdminPurchaseCreate'])->name('purchase.create');
            Route::post('/store', [PurchaseController::class, 'AdminPurchaseStore'])->name('purchase.store');
            //Route::get('/view/{id}', [PurchaseController::class, 'AdminPurchaseView'])->name('purchase.show');
            Route::get('/edit/{id}', [PurchaseController::class, 'AdminPurchaseEdit'])->name('purchase.edit');
            Route::put('/update/{id}', [PurchaseController::class, 'AdminPurchaseUpdate'])->name('purchase.update');
            Route::get('/delete/{id}', [PurchaseController::class, 'destroy'])->name('purchase.destroy');
            Route::get('/get-invoice-details/{id}', [PurchaseController::class, 'getInvoiceDetails']);
            Route::get('/print', [PurchaseController::class, 'Print'])->name('purchase.print');
            Route::get('/view', [PurchaseController::class, 'AdminPurchaseView2'])->name('purchase.view');
            // purchase order
            Route::get('/order/list', [PurchaseOrderController::class, 'index'])->name('purchase.order.index');
            Route::get('/order/create', [PurchaseOrderController::class, 'create'])->name('purchase.order.create');
            Route::post('/order/store', [PurchaseOrderController::class, 'store'])->name('purchase.order.store');
            Route::get('/order/edit/{id}', [PurchaseOrderController::class, 'edit'])->name('purchase.order.edit');
            Route::put('/order/update/{id}', [PurchaseOrderController::class, 'update'])->name('purchase.order.update');
            Route::get('/order/delete/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchase.order.destroy');
            Route::get('/order/view', [PurchaseOrderController::class, 'show'])->name('purchase.order.view');

            // purchase invoice
            Route::get('/invoice/list', [PurchaseInvoiceController::class, 'index'])->name('purchase.invoice.index');
            Route::get('/invoice/create', [PurchaseInvoiceController::class, 'create'])->name('purchase.invoice.create');
            Route::post('/invoice/store', [PurchaseInvoiceController::class, 'store'])->name('purchase.invoice.store');
            Route::get('/invoice/edit/{id}', [PurchaseInvoiceController::class, 'edit'])->name('purchase.invoice.edit');
            Route::put('/invoice/update/{id}', [PurchaseInvoiceController::class, 'update'])->name('purchase.invoice.update');
            Route::get('/invoice/delete/{id}', [PurchaseInvoiceController::class, 'destroy'])->name('purchase.invoice.destroy');
            Route::get('/invoice/view', [PurchaseInvoiceController::class, 'AdminPurchaseInvoiceView2'])->name('purchase.invoice.view');
            // AJAX data routes
            Route::get('/get-purchases-by-project/{projectId}', [PurchaseInvoiceController::class, 'getPurchasesByProject'])
                ->name('purchase.get.purchases.by.project');
            Route::get('/get-products-by-purchase/{purchaseId}', [PurchaseInvoiceController::class, 'getProductsByPurchase'])
                ->name('purchase.get.products.by.purchase');

            
        });
        
        /* ==================== Sales Payment Controller =================== */
        Route::prefix('payment/sales')->group(function () {
            Route::get('/', [SalePaymentController::class, 'index'])->name('sale.payment.index');
            Route::get('/create', [SalePaymentController::class, 'create'])->name('sale.payment.create');
            Route::post('/store', [SalePaymentController::class, 'store'])->name('sale.payment.store');
            //Route::get('/view/{id}', [SalePaymentController::class, 'view'])->name('sale.payment.show');
            Route::get('/view', [SalePaymentController::class, 'view'])->name('sale.payment.show');
            Route::get('/edit/{id}', [SalePaymentController::class, 'edit'])->name('sale.payment.edit');
            Route::put('/update/{id}', [SalePaymentController::class, 'update'])->name('sale.payment.update');
            Route::get('/delete/{id}', [SalePaymentController::class, 'destroy'])->name('sale.payment.destroy');
            Route::get('/get-ledgers-by-group', [SalePaymentController::class, 'getLedgersByGroup'])->name('sale.payment.get.ledgers.by.group');
            Route::get('/payment/get-chalans-by-supplier', [SalePaymentController::class, 'getChalansBySupplier'])->name('sale.payment.get.chalans.by.supplier');
            Route::get('/get-purchase-details', [SalePaymentController::class, 'getPurchaseDetails'])->name('sale.payment.get.purchase.details');

        });

        /* ==================== sales =================== */
        Route::prefix('sales')->group(function () {
            Route::get('/', [SalesController::class, 'index'])->name('sale.index');
            Route::get('/create', [SalesController::class, 'create'])->name('sale.create');
            Route::post('/store', [SalesController::class, 'store'])->name('sale.store');
            Route::get('/view/{id}', [SalesController::class, 'view'])->name('sale.show');
            Route::get('/edit/{id}', [SalesController::class, 'edit'])->name('sale.edit');
            Route::put('/update/{id}', [SalesController::class, 'update'])->name('sale.update');
            Route::get('/delete/{id}', [SalesController::class, 'destroy'])->name('sale.destroy');
            Route::get('/get-invoice-details/{id}', [SalesController::class, 'getInvoiceDetails']);
        });
        
        /* ==================== Sales Receipt Controller =================== */
        Route::prefix('payment/receipt')->group(function () {
            Route::get('/', [SaleReceiptController::class, 'index'])->name('receipt.payment.index');
            Route::get('/create', [SaleReceiptController::class, 'create'])->name('receipt.payment.create');
            Route::post('/store', [SaleReceiptController::class, 'store'])->name('receipt.payment.store');
            Route::get('/view/{id}', [SaleReceiptController::class, 'view'])->name('receipt.payment.show');
            Route::get('/edit/{id}', [SaleReceiptController::class, 'edit'])->name('receipt.payment.edit');
            Route::put('/update/{id}', [SaleReceiptController::class, 'update'])->name('receipt.payment.update');
            Route::get('/delete/{id}', [SaleReceiptController::class, 'destroy'])->name('receipt.payment.destroy');
            Route::get('/get-ledgers-by-group', [SaleReceiptController::class, 'getLedgersByGroup'])->name('receipt.payment.get.ledgers.by.group');
            Route::get('/payment/get-chalans-by-client', [SaleReceiptController::class, 'getChalansByClient'])->name('receipt.payment.get.chalans.by.client');
        });

        /* ==================== Incoming Chalan =================== */
        Route::prefix('chalan/incoming')->group(function () {
            Route::get('/', [IncomingChalanController::class, 'index'])->name('incoming.chalan.index');
            Route::get('/create', [IncomingChalanController::class, 'create'])->name('incoming.chalan.create');
            Route::post('/store', [IncomingChalanController::class, 'store'])->name('incoming.chalan.store');
            Route::get('/view/{id}', [IncomingChalanController::class, 'view'])->name('incoming.chalan.show');
            Route::get('/edit/{id}', [IncomingChalanController::class, 'edit'])->name('incoming.chalan.edit');
            Route::put('/update/{id}', [IncomingChalanController::class, 'update'])->name('incoming.chalan.update');
            Route::get('/delete/{id}', [IncomingChalanController::class, 'destroy'])->name('incoming.chalan.destroy');
        });

        /* ==================== Outcoming Chalan =================== */
        Route::prefix('chalan/outcoming')->group(function () {
            Route::get('/', [OutComingChalanController::class, 'index'])->name('outcoming.chalan.index');
            Route::get('/create', [OutComingChalanController::class, 'create'])->name('outcoming.chalan.create');
            Route::post('/store', [OutComingChalanController::class, 'store'])->name('outcoming.chalan.store');
            Route::get('/view/{id}', [OutComingChalanController::class, 'view'])->name('outcoming.chalan.show');
            Route::get('/edit/{id}', [OutComingChalanController::class, 'edit'])->name('outcoming.chalan.edit');
            Route::put('/update/{id}', [OutComingChalanController::class, 'update'])->name('outcoming.chalan.update');
            Route::get('/delete/{id}', [OutComingChalanController::class, 'destroy'])->name('outcoming.chalan.destroy');
        });

        // Project Routes
        Route::resource('projects', ProjectController::class);
        Route::get('/projects/delete/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::get('/projects/sales/{id}', [ProjectController::class, 'projectsSales'])->name('projects.sales');
        Route::get('/project/details', [ProjectController::class, 'getProjectDetails'])->name('project.get.details');
        Route::post('/product/store2', [ProjectController::class, 'AdminProductStore2'])->name('Product2.store');
        Route::get('/product/modal', [ProjectController::class, 'AdminProductModal'])->name('product.modal');

        /* ==================== Project Payment Receipt Controller =================== */
        Route::prefix('project/payment/receipt')->group(function () {
            Route::get('/', [ProductSaleReceiveController::class, 'index'])->name('project.receipt.payment.index');
            Route::get('/create', [ProductSaleReceiveController::class, 'create'])->name('project.receipt.payment.create');
            Route::post('/store', [ProductSaleReceiveController::class, 'store'])->name('project.receipt.payment.store');
            // Route::get('/view/{invoice_no}', [ProductSaleReceiveController::class, 'view'])->name('project.receipt.payment.show');
            Route::get('/view', [ProductSaleReceiveController::class, 'view'])->name('project.receipt.payment.show');
            Route::get('/edit/{id}', [ProductSaleReceiveController::class, 'edit'])->name('project.receipt.payment.edit');
            Route::put('/update/{id}', [ProductSaleReceiveController::class, 'update'])->name('project.receipt.payment.update');
            Route::get('/delete/{id}', [ProductSaleReceiveController::class, 'destroy'])->name('project.receipt.payment.destroy');
            Route::get('/get-ledgers-by-group', [ProductSaleReceiveController::class, 'getLedgersByGroup'])->name('project.receipt.payment.get.ledgers.by.group');
            Route::get('/payment/get-chalans-by-client', [ProductSaleReceiveController::class, 'getChalansByClient'])->name('project.receipt.payment.get.chalans.by.client');
        });
        
        // Quotation Routes
        Route::resource('quotations', QuotationController::class);
        Route::get('/quotations/delete/{id}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
        // Work Order Routes
        Route::resource('workorders', WorkOrderController::class);
        Route::get('/delete/{id}', [WorkOrderController::class, 'destroy'])->name('workorders.destroy');
        Route::get('/workorders/invoice/{id}', [WorkOrderController::class, 'invoice'])->name('workorders.invoice');


        /* ==================== Stock =================== */
        Route::prefix('stock')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('stock.index');
            Route::get('/view/{id}', [StockController::class, 'view'])->name('stock.show');
            Route::get('/out', [StockController::class, 'Out'])->name('stock.out');
            Route::get('/out/{id}', [StockController::class, 'OutView'])->name('stock.out.view');
            Route::get('/in', [StockController::class, 'In'])->name('stock.in');
            Route::get('/in/{id}', [StockController::class, 'InView'])->name('stock.in.view');
        });

    });





    Route::view('/invoice', 'Accounts.invoice.invoice');
    Route::view('/invoice2', 'Accounts.invoice.invoice2');

    Route::get('/get-sub-groups/{group_id}', function ($group_id) {
        Log::info("Fetching sub-groups for group ID: " . $group_id);
        $subGroups = LedgerSubGroup::where('ledger_group_id', $group_id)->pluck('subgroup_name', 'id');
        Log::info("Sub-groups fetched: ", $subGroups->toArray());
        return response()->json($subGroups);
    });

    Route::get('/purchase-details/{purchase_id}', [ProjectController::class, 'showDetails']);

}); 