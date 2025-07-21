<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Order;
use App\Models\Inventory\OrderItem;
use App\Models\Inventory\InventoryProduct;
use App\Models\Inventory\InventoryCustomer;
use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\ProductBrand;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['items.product', 'customer'])->latest()->get();
        $pageTitle = 'Order List';
        return view('Inventory.order.index', compact('orders', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = InventoryCustomer::where('status', 1)->get();
        $products = InventoryProduct::where('status', 1)
            ->with(['category', 'brand'])
            ->get();
        $categories = ProductCategory::where('status', 1)->get();
        $brands = ProductBrand::where('status', 1)->get();

        // Get cart from session
        $cart = session()->get('cart', []);
        // dd($cart);

        $pageTitle = 'Create Order';
        return view('Inventory.order.create', compact(
            'customers', 
            'products', 
            'categories', 
            'brands', 
            'pageTitle',
            'cart'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'customer_id' => 'required|exists:inventory_customers,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:inventory_products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'vat_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500'
        ]);

        // dd($validated);

        DB::beginTransaction();

        try {
            // Validate product quantities before processing
            foreach ($request->products as $product) {
                $inventoryProduct = InventoryProduct::find($product['id']);
                if ($inventoryProduct->quantity < $product['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$inventoryProduct->name}");
                }
            }

            // Generate order number with a more unique approach
            $latestOrder = Order::latest()->first();
            $orderNumber = date('Ymd') . '-' . str_pad(($latestOrder ? $latestOrder->id + 1 : 1), 8, '0', STR_PAD_LEFT);


            // Create the order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'order_date' => now(),
                'subtotal' => $validated['subtotal'],
                'vat_rate' => $request->vat_rate ?? 0,
                'vat_amount' => $validated['vat_amount'] ?? 0,
                'tax_rate' => $request->tax_rate ?? 0,
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'paid_amount' => 0,
                'due_amount' => $validated['total_amount'],
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // dd($order);

            // Add order items and update inventory
            foreach ($validated['products'] as $product) {
                $orderItem = $order->items()->create([
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['price'],
                    'total_price' => $product['price'] * $product['quantity'],
                ]);

                // Update product stock with additional checks
                $affectedRows = InventoryProduct::where('id', $product['id'])
                    ->where('quantity', '>=', $product['quantity'])
                    ->decrement('quantity', $product['quantity']);

                if ($affectedRows === 0) {
                    throw new \Exception("Failed to update inventory for product ID: {$product['id']}");
                }
            }

            // Clear the cart session if exists
            if (session()->has('cart')) {
                session()->forget('cart');
            }

            DB::commit();

            // You might want to fire an event here for notifications, etc.
            // event(new OrderCreated($order));

            return redirect()->route('inventory.order.index')
                ->with('success', 'Order #' . $orderNumber . ' created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            Log::error($e);
            
            return back()->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['items.product', 'customer'])->findOrFail($id);
        $pageTitle = 'Order Show';
        return view('Inventory.order.show', compact('order', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::with(['items.product', 'customer', 'user'])->findOrFail($id);
        $pageTitle = 'Edit Order #' . $order->order_number;

        $customers = InventoryCustomer::where('status', 1)->get();
        $products = InventoryProduct::where('status', 1)
            ->with(['category', 'brand'])
            ->get();
        $categories = ProductCategory::where('status', 1)->get();
        $brands = ProductBrand::where('status', 1)->get();

        $cart = [];
        foreach ($order->items as $item) {
            $product = $item->product;
            $cart[$item->product_id] = [
                'id' => $item->product_id,
                'name' => $product->name,
                'price' => $item->unit_price,
                'quantity' => $item->quantity,
                'max_quantity' => $product->quantity + $item->quantity,
                'product' => $product
            ];
        }

        $orderStatuses = [
            'draft' => 'Draft',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        $paymentStatuses = [
            'unpaid' => 'Unpaid',
            'partial' => 'Partial',
            'paid' => 'Paid',
            'refunded' => 'Refunded'
        ];
        

        return view('Inventory.order.edit', compact(
            'order',
            'pageTitle',
            'customers',
            'products',
            'categories',
            'brands',
            'cart',
            'orderStatuses',
            'paymentStatuses'
        ));
    }
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'customer_id' => 'required|exists:inventory_customers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'order_status' => 'required|string',
            'payment_status' => 'required|string',
            'payment_method' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'vat_rate' => 'required|numeric|min:0',
            'vat_amount' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'items' => 'required|array',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            // Start transaction
            \DB::beginTransaction();

            // Find the order
            $order = Order::findOrFail($id);

            // Update order details
            $order->update([
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'],
                'order_status' => $validated['order_status'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'],
                'subtotal' => $validated['subtotal'],
                'vat_rate' => $validated['vat_rate'],
                'vat_amount' => $validated['vat_amount'],
                'tax_rate' => $validated['tax_rate'],
                'tax_amount' => $validated['tax_amount'],
                'discount' => $validated['discount'],
                'total_amount' => $validated['total_amount'],
                'paid_amount' => $validated['paid_amount'],
                'due_amount' => $validated['due_amount'],
                'notes' => $validated['notes'],
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'],
            ]);

            // Sync order items
            $currentItems = $order->items->pluck('product_id')->toArray();
            $updatedItems = array_keys($validated['items']);

            // Items to remove (in current but not in updated)
            $itemsToRemove = array_diff($currentItems, $updatedItems);
            if (!empty($itemsToRemove)) {
                OrderItem::where('order_id', $order->id)
                    ->whereIn('product_id', $itemsToRemove)
                    ->delete();
            }

            // Update or create items
            foreach ($validated['items'] as $productId => $item) {
                $order->items()->updateOrCreate(
                    ['product_id' => $productId],
                    [
                        'unit_price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['price'] * $item['quantity']
                    ]
                );
            }

            // Commit transaction
            \DB::commit();

            return redirect()->route('inventory.order.index')
                ->with('success', 'Order updated successfully');

        } catch (\Exception $e) {
            // Rollback transaction on error
            \DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    public function removeItem(Request $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);
            
            // Delete the specific item
            $deleted = $order->items()
                ->where('product_id', $request->product_id)
                ->delete();
                
            if ($deleted) {
                // Recalculate order totals
                $this->recalculateOrderTotals($order);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed successfully',
                    'order' => $order->fresh()
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function recalculateOrderTotals(Order $order)
    {
        // Calculate subtotal from items
        $subtotal = $order->items->sum(function($item) {
            return $item->unit_price * $item->quantity;
        });
        
        // Calculate taxes and totals
        $vatAmount = $subtotal * ($order->vat_rate / 100);
        $taxAmount = $subtotal * ($order->tax_rate / 100);
        $totalBeforeDiscount = $subtotal + $vatAmount + $taxAmount;
        $totalAmount = $totalBeforeDiscount - $order->discount;
        $dueAmount = max(0, $totalAmount - $order->paid_amount);
        
        // Update order
        $order->update([
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'due_amount' => $dueAmount
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the order with its items
            $order = Order::with('items')->findOrFail($id);
            
            // Begin database transaction
            \DB::beginTransaction();
            
            // Delete all order items first
            $order->items()->delete();
            
            // Then delete the order
            $order->delete();
            
            // Commit transaction
            \DB::commit();
            
            return redirect()->route('inventory.order.index')
                ->with('success', 'Order deleted successfully');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('inventory.order.index')
                ->with('error', 'Order not found');
                
        } catch (\Exception $e) {
            // Rollback transaction if error occurs
            \DB::rollBack();
            
            return redirect()->route('inventory.order.index')
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    public function addToCart(Request $request)
    {
        $product = InventoryProduct::findOrFail($request->product_id);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                "max_quantity" => $product->quantity
            ];
        }
        
        session()->put('cart', $cart);
        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'message' => 'Product added to cart'
        ]);
    }

    public function updateCart(Request $request)
    {
        if ($request->product_id && $request->quantity) {
            $cart = session()->get('cart');
            
            if (isset($cart[$request->product_id])) {
                if ($request->quantity <= $cart[$request->product_id]['max_quantity']) {
                    $cart[$request->product_id]['quantity'] = $request->quantity;
                    session()->put('cart', $cart);
                    return response()->json(['success' => true]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Quantity exceeds available stock'
                    ], 400);
                }
            }
        }
        
        return response()->json(['success' => false], 400);
    }

    public function removeFromCart(Request $request)
    {
        if ($request->product_id) {
            $cart = session()->get('cart');
            
            if (isset($cart[$request->product_id])) {
                unset($cart[$request->product_id]);
                session()->put('cart', $cart);
            }
            
            return response()->json([
                'success' => true,
                'cart_count' => count($cart),
                'message' => 'Product removed from cart'
            ]);
        }
        
        return response()->json(['success' => false], 400);
    }

    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }
}
