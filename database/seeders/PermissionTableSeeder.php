<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions grouped by role
        $permissionsByRole = [
            'Accounts Manager' => [
                // Setting Permissions
                ['name' => 'dashboard-menu', 'group' => 'settings'],
                ['name' => 'setting-menu', 'group' => 'settings'],
                ['name' => 'setting-information', 'group' => 'settings'],
                ['name' => 'setting-information-edit', 'group' => 'settings'],
                ['name' => 'profile-view', 'group' => 'settings'],
                ['name' => 'password-change', 'group' => 'settings'],
                ['name' => 'setting-import', 'group' => 'settings'],
                ['name' => 'setting-export', 'group' => 'settings'],
                ['name' => 'setting-configuration', 'group' => 'settings'],
                // Setting Products Category Permissions
                ['name' => 'setting-category-list', 'group' => 'settings'],
                ['name' => 'setting-category-create', 'group' => 'settings'],
                ['name' => 'setting-category-edit', 'group' => 'settings'],
                ['name' => 'setting-category-view', 'group' => 'settings'],
                ['name' => 'setting-category-delete', 'group' => 'settings'],
                // Setting Products Units Permissions
                ['name' => 'setting-unit-menu', 'group' => 'settings'],
                ['name' => 'setting-unit-list', 'group' => 'settings'],
                ['name' => 'setting-unit-create', 'group' => 'settings'],
                ['name' => 'setting-unit-edit', 'group' => 'settings'],
                ['name' => 'setting-unit-view', 'group' => 'settings'],
                ['name' => 'setting-unit-delete', 'group' => 'settings'],
                // Account Menu Permissions
                ['name' => 'account-menu', 'group' => 'accounts'],
                ['name' => 'ledger-menu', 'group' => 'accounts'],
                ['name' => 'ledger-group-list', 'group' => 'accounts'],
                ['name' => 'ledger-group-create', 'group' => 'accounts'],
                ['name' => 'ledger-group-edit', 'group' => 'accounts'],
                ['name' => 'ledger-group-view', 'group' => 'accounts'],
                ['name' => 'ledger-group-delete', 'group' => 'accounts'],
                ['name' => 'ledger-sub-group-list', 'group' => 'accounts'],
                ['name' => 'ledger-sub-group-create', 'group' => 'accounts'],
                ['name' => 'ledger-sub-group-edit', 'group' => 'accounts'],
                ['name' => 'ledger-sub-group-view', 'group' => 'accounts'],
                ['name' => 'ledger-sub-group-delete', 'group' => 'accounts'],
                ['name' => 'ledger-list', 'group' => 'accounts'],
                ['name' => 'ledger-create', 'group' => 'accounts'],
                ['name' => 'ledger-edit', 'group' => 'accounts'],
                ['name' => 'ledger-view', 'group' => 'accounts'],
                ['name' => 'ledger-delete', 'group' => 'accounts'],
                // Customer Menu Permissions
                ['name' => 'customer-menu', 'group' => 'customers'],
                ['name' => 'customer-list', 'group' => 'customers'],
                ['name' => 'customer-create', 'group' => 'customers'],
                ['name' => 'customer-edit', 'group' => 'customers'],
                ['name' => 'customer-view', 'group' => 'customers'],
                ['name' => 'customer-delete', 'group' => 'customers'],
                // Vendor Menu Permissions
                ['name' => 'vendor-menu', 'group' => 'vendors'],
                ['name' => 'vendor-list', 'group' => 'vendors'],
                ['name' => 'vendor-create', 'group' => 'vendors'],
                ['name' => 'vendor-edit', 'group' => 'vendors'],
                ['name' => 'vendor-view', 'group' => 'vendors'],
                ['name' => 'vendor-delete', 'group' => 'vendors'],
                // Project Menu Permissions
                ['name' => 'project-menu', 'group' => 'projects'],
                ['name' => 'project-list', 'group' => 'projects'],
                ['name' => 'project-create', 'group' => 'projects'],
                ['name' => 'project-edit', 'group' => 'projects'],
                ['name' => 'project-view', 'group' => 'projects'],
                ['name' => 'project-delete', 'group' => 'projects'],
                // Transaction Menu Permissions
                ['name' => 'transaction-menu', 'group' => 'transactions'],
                ['name' => 'receipt-list', 'group' => 'transactions'],
                ['name' => 'receipt-create', 'group' => 'transactions'],
                ['name' => 'receipt-view', 'group' => 'transactions'],
                ['name' => 'receipt-delete', 'group' => 'transactions'],
                ['name' => 'payment-list', 'group' => 'transactions'],
                ['name' => 'payment-create', 'group' => 'transactions'],
                ['name' => 'payment-view', 'group' => 'transactions'],
                ['name' => 'payment-delete', 'group' => 'transactions'],
                ['name' => 'journal-list', 'group' => 'transactions'],
                ['name' => 'journal-create', 'group' => 'transactions'],
                ['name' => 'journal-edit', 'group' => 'transactions'],
                ['name' => 'journal-view', 'group' => 'transactions'],
                ['name' => 'journal-delete', 'group' => 'transactions'],
                ['name' => 'contra-list', 'group' => 'transactions'],
                ['name' => 'contra-create', 'group' => 'transactions'],
                ['name' => 'contra-edit', 'group' => 'transactions'],
                ['name' => 'contra-view', 'group' => 'transactions'],
                ['name' => 'contra-delete', 'group' => 'transactions'],
                ['name' => 'purchases-list', 'group' => 'transactions'],
                ['name' => 'purchases-create', 'group' => 'transactions'],
                ['name' => 'purchases-edit', 'group' => 'transactions'],
                ['name' => 'purchases-view', 'group' => 'transactions'],
                ['name' => 'purchases-delete', 'group' => 'transactions'],
                ['name' => 'purchases-invoice-list', 'group' => 'transactions'],
                ['name' => 'purchases-invoice-create', 'group' => 'transactions'],
                ['name' => 'purchases-invoice-edit', 'group' => 'transactions'],
                ['name' => 'purchases-invoice-view', 'group' => 'transactions'],
                ['name' => 'purchases-invoice-delete', 'group' => 'transactions'],
                ['name' => 'sales-invoice-list', 'group' => 'transactions'],
                ['name' => 'sales-invoice-create', 'group' => 'transactions'],
                ['name' => 'sales-invoice-edit', 'group' => 'transactions'],
                ['name' => 'sales-invoice-view', 'group' => 'transactions'],
                ['name' => 'sales-invoice-delete', 'group' => 'transactions'],
                // Sales Permissions
                ['name' => 'sales-menu', 'group' => 'sales'],
                ['name' => 'sales-proforma-invoice', 'group' => 'sales'],
                ['name' => 'sales-order', 'group' => 'sales'],
                ['name' => 'sales-delivery-note', 'group' => 'sales'],
                ['name' => 'sales-return', 'group' => 'sales'],
                ['name' => 'sales-warranty', 'group' => 'sales'],
                ['name' => 'sales-customers', 'group' => 'sales'],
                ['name' => 'sales-salesman', 'group' => 'sales'],
                ['name' => 'sales-receivable', 'group' => 'sales'],
                ['name' => 'sales-import', 'group' => 'sales'],
                ['name' => 'sales-team', 'group' => 'sales'],
                // Purchases Permissions
                ['name' => 'purchases-menu', 'group' => 'purchases'],
                ['name' => 'purchases-requisition', 'group' => 'purchases'],
                ['name' => 'purchases-quotation', 'group' => 'purchases'],
                ['name' => 'purchases-receipt-note', 'group' => 'purchases'],
                ['name' => 'purchases-return', 'group' => 'purchases'],
                ['name' => 'purchases-import', 'group' => 'purchases'],
                ['name' => 'purchases-vendors', 'group' => 'purchases'],
                // Report Permissions
                ['name' => 'report-menu', 'group' => 'reports'],
                ['name' => 'report-trial-balance', 'group' => 'reports'],
                ['name' => 'report-balance-sheet', 'group' => 'reports'],
                ['name' => 'report-profit-loss', 'group' => 'reports'],
                ['name' => 'report-receipts-payments', 'group' => 'reports'],
                ['name' => 'report-daybook', 'group' => 'reports'],
                ['name' => 'report-groupwise-statement', 'group' => 'reports'],
                ['name' => 'report-bills-receivable', 'group' => 'reports'],
                ['name' => 'report-bills-payable', 'group' => 'reports'],
                ['name' => 'report-purchases-sales', 'group' => 'reports'],
                ['name' => 'report-sales', 'group' => 'reports'],
                ['name' => 'report-purchases', 'group' => 'reports'],
                // Company Permissions
                ['name' => 'company-menu', 'group' => 'company'],
                ['name' => 'company-list', 'group' => 'company'],
                ['name' => 'company-create', 'group' => 'company'],
                ['name' => 'company-edit', 'group' => 'company'],
                ['name' => 'company-view', 'group' => 'company'],
                ['name' => 'company-delete', 'group' => 'company'],
                // Branch Permissions
                ['name' => 'branch-menu', 'group' => 'branch'],
                ['name' => 'branch-list', 'group' => 'branch'],
                ['name' => 'branch-create', 'group' => 'branch'],
                ['name' => 'branch-edit', 'group' => 'branch'],
                ['name' => 'branch-view', 'group' => 'branch'],
                ['name' => 'branch-delete', 'group' => 'branch'],
                // Product Permissions
                ['name' => 'product-menu', 'group' => 'products'],
                ['name' => 'product-list', 'group' => 'products'],
                ['name' => 'product-create', 'group' => 'products'],
                ['name' => 'product-edit', 'group' => 'products'],
                ['name' => 'product-view', 'group' => 'products'],
                ['name' => 'product-delete', 'group' => 'products'],

            ],
            'Inventory Manager' => [
                ['name' => 'dashboard-menu', 'group' => 'settings'],
                ['name' => 'setting-menu', 'group' => 'settings'],
                ['name' => 'setting-information', 'group' => 'settings'],
                ['name' => 'setting-information-edit', 'group' => 'settings'],
                ['name' => 'profile-view', 'group' => 'settings'],
                ['name' => 'password-change', 'group' => 'settings'],
                // Category Permissions
                ['name' => 'category-menu', 'group' => 'category'],
                ['name' => 'category-list', 'group' => 'category'],
                ['name' => 'category-create', 'group' => 'category'],
                ['name' => 'category-edit', 'group' => 'category'],
                ['name' => 'category-view', 'group' => 'category'],
                ['name' => 'category-delete', 'group' => 'category'],
            ],
            'HR Manager' => [
                ['name' => 'dashboard-menu', 'group' => 'settings'],
                ['name' => 'setting-menu', 'group' => 'settings'],
                ['name' => 'setting-information', 'group' => 'settings'],
                ['name' => 'setting-information-edit', 'group' => 'settings'],
                ['name' => 'profile-view', 'group' => 'settings'],
                ['name' => 'password-change', 'group' => 'settings'],
            ],
        ];

        // Loop through and assign permissions to roles
        foreach ($permissionsByRole as $roleName => $permissions) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);

            $permissionNames = [];

            foreach ($permissions as $permissionData) {
                // Create or update permission
                $permission = \Spatie\Permission\Models\Permission::updateOrCreate(
                    ['name' => $permissionData['name']],
                    ['group' => $permissionData['group']]
                );

                $permissionNames[] = $permission->name;
            }

            // Sync all permissions to this role
            $role->syncPermissions($permissionNames);
        }
    }
}
