<?php
namespace App\Traits;

use App\Models\Accounts\Project;

trait ProjectSalesTrait
{
    public function getProjectSalesData($id, $fromDate, $toDate)
    {
        // Fetch the project with related models
        $project = Project::where('id', $id)
            ->with(['client', 'items', 'sales', 'purchases', 'receipts'])
            ->firstOrFail();

        // Filter purchases based on the provided dates
        $purchases = $project->purchasesinvoice()
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->get();
            // dd($purchases);

        // Filter sales within date range
        $sales = $project->sales()
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->get();

        // Calculate totals from sales
        $totalAmount = $project->grand_total;
        // dd($totalAmount);
        $paidAmount  = $sales->sum('paid_amount');
        $dueAmount   = $totalAmount - $paidAmount;

        return compact('project', 'purchases', 'sales', 'totalAmount', 'paidAmount', 'dueAmount');
    }
}
