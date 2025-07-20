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
                'vat_amount' => $validated['vat_amount'] ?? 0,
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
