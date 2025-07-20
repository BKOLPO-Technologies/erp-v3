<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryProduct;
use App\Models\Inventory\InventoryVendor;
use App\Models\Inventory\Stock;
use App\Models\Inventory\StockInward;
use Illuminate\Support\Str;

class StockInwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockInwards = StockInward::with(['product', 'vendor'])->get();
        $pageTitle = 'Stock Inward List';
        return view('Inventory.stockinward.index', compact('stockInwards', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = InventoryVendor::active()->get();
        $products = InventoryProduct::active()->get();
        $pageTitle = 'Stock Inward';
        return view('Inventory.stockinward.create', compact('vendors','products', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // dd($request->all());
        // Remove reference_lot from validation since we'll generate it
        $validated = $request->validate([
            'product_id' => 'required|exists:inventory_products,id',
            'vendor_id' => 'required|exists:inventory_vendors,id',
            'total_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'comments' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        try {
            // Generate reference/lot number
            $validated['reference_lot'] = $this->generateReferenceLot();
            
            $stockInward = StockInward::create($validated);
            
            // Update product stock quantity
            $product = InventoryProduct::find($validated['product_id']);
            $product->increment('quantity', $validated['quantity']);

            // 3. Insert into Stock Table
            Stock::create([
                'product_id'  => $validated['product_id'],
                'type'        => 'in',
                'quantity'    => $validated['quantity'],
                'reference'   => $validated['reference_lot'],
            ]);
            
            return redirect()->route('inventory.stockinward.index')
                ->with('success', 'Stock inward created successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating stock inward: '.$e->getMessage());
        }
    }

    /**
     * Generate unique reference/lot number
     */
    protected function generateReferenceLot()
    {
        $prefix = 'STK-IN-'; // You can customize this prefix
        $datePart = date('Ymd'); // YYYYMMDD format
        $randomPart = strtoupper(substr(uniqid(), -4)); // Last 4 chars of uniqid
        
        // Check if exists and regenerate if needed
        do {
            $reference = $prefix . $datePart . '-' . $randomPart;
            $exists = StockInward::where('reference_lot', $reference)->exists();
            
            if ($exists) {
                $randomPart = strtoupper(substr(uniqid(), -4));
            }
        } while ($exists);
        
        return $reference;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stockInward = StockInward::with(['product', 'vendor'])->findOrFail($id);
        $pageTitle = 'Stock Inward Details';
        return view('Inventory.stockinward.show', compact('stockInward', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stockInward = StockInward::with(['product', 'vendor'])->findOrFail($id);
        $vendors = InventoryVendor::all();
        $products = InventoryProduct::active()->get();
        // dd($products);
        $pageTitle = 'Stock Inward Edit';
        return view('Inventory.stockinward.edit', compact('stockInward', 'vendors','products', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stockInward = StockInward::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'required|exists:inventory_products,id',
            'vendor_id' => 'required|exists:inventory_vendors,id',
            'total_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'comments' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        try {
            $stockInward->update($validated);
            
            // Update product stock quantity
            $product = InventoryProduct::find($validated['product_id']);
            $product->increment('quantity', $validated['quantity'] - $stockInward->quantity);
            // Update Stock Table
            Stock::where('reference', $stockInward->reference_lot)
                ->update([
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                ]);
                
            // Update reference/lot number if needed
            if ($stockInward->reference_lot !== $this->generateReferenceLot()) {
                $newReference = $this->generateReferenceLot();
                $stockInward->update(['reference_lot' => $newReference]);
                Stock::where('reference', $stockInward->reference_lot)
                    ->update(['reference' => $newReference]);
            }
            
            return redirect()->route('inventory.stockinward.index')
                ->with('success', 'Stock inward updated successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating stock inward: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stockInward = StockInward::findOrFail($id);
        $stockInward->delete();

        return redirect()->route('inventory.stockinward.index')->with('success', 'Stock Inward deleted successfully.');
    }
}
