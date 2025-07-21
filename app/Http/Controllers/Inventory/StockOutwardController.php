<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Order;
use App\Models\Inventory\OrderItem;
use App\Models\Inventory\InventoryProduct;
use App\Models\Inventory\InventoryCustomer;
use App\Models\Inventory\Stock;
use App\Models\Inventory\StockOutward;
use Illuminate\Support\Str;


class StockOutwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockOutwards = StockOutward::with(['product', 'customer'])->get();
        $pageTitle = 'Stock Outward List';
        return view('Inventory.stockoutward.index', compact('stockOutwards', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = InventoryCustomer::where('status', 1)->get();
        $products = InventoryProduct::active()->with('stock')->get();
        $pageTitle = 'Create Stock Outward Order';
        
        return view('Inventory.stockoutward.create', compact('customers', 'products', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:inventory_customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:inventory_products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
            'products.*.total_price' => 'required|numeric|min:0',
            'comments' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            // Create stock outward record
            $stockOutward = StockOutward::create([
                'reference_lot' => 'OUT-' . Str::upper(Str::random(8)),
                'customer_id' => $validated['customer_id'],
                'order_id' => $validated['order_id'],
                'comments' => $validated['comments'],
                'status' => $validated['status'],
                'total_price' => array_sum(array_column($validated['products'], 'total_price')),
            ]);

            // Add products
            foreach ($validated['products'] as $product) {
                // Create outward item
                $stockOutward->items()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'total_price' => $product['total_price'],
                ]);

                // Update stock
                $stock = Stock::where('product_id', $product['product_id'])->first();
                
                if (!$stock) {
                    throw new \Exception("Product not found in stock!");
                }

                if ($stock->quantity < $product['quantity']) {
                    throw new \Exception("Insufficient stock for product: " . $stock->product->name);
                }

                $stock->decrement('quantity', $product['quantity']);
            }

            DB::commit();

            return redirect()->route('inventory.stockoutward.index')
                ->with('success', 'Stock outward recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     /**
     * Get orders for a customer (AJAX)
     */
    public function getOrders($customerId)
    {
        $orders = Order::where('customer_id', $customerId)
            ->select('id', 'order_number')
            ->get();
            
        return response()->json($orders);
    }
    
    /**
     * Get product details (AJAX)
     */
    public function getProductDetails($productId)
    {
        $product = InventoryProduct::with('stock')
            ->findOrFail($productId);
            
        return response()->json([
            'price' => $product->price,
            'available_stock' => $product->stock->quantity ?? 0,
            'product_code' => $product->product_code
        ]);
    }
}
