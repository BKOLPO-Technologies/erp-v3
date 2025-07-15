<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Accounts\Category;
use App\Models\Accounts\Product;
use App\Models\Inventory\ProductImage;
use App\Models\Inventory\Tag;
use App\Models\Inventory\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Accounts\Unit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index() 
    {
        $products = Product::orderBy('id', 'desc')->get();
        $pageTitle = 'Product List';
        return view('Inventory.product.index',compact('pageTitle', 'products'));
    }

    public function create() 
    {
        $pageTitle = 'Product Create';
        $categories = Category::where('status',1)->latest()->get();
        $brands = Brand::where('status',1)->latest()->get();
        $tags = Tag::where('status',1)->latest()->get();
        $units = Unit::where('status',1)->latest()->get();
        $productCode = 'PRD' . strtoupper(Str::random(5));

        return view('Inventory.product.create',compact('pageTitle','categories','tags','brands', 'units', 'productCode'));
    }

   public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'product_code' => 'nullable|string|max:50|unique:products,product_code',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|boolean',
            'group_name' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            // Auto-generate stock status based on quantity
            $stockStatus = 'in_stock';
            if ($request->quantity <= 0) {
                $stockStatus = 'out_of_stock';
            } elseif ($request->quantity <= $request->alert_quantity) {
                $stockStatus = 'low_stock';
            }

            // Store the product with the validated data
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'product_code' => $request->product_code,
                'category_id' => $request->category_id,
                // 'brand_id' => $request->brand_id,
                'unit_id' => $request->unit_id,
                'price' => $request->price,
                // 'purchase_price' => $request->purchase_price,
                // 'selling_price' => $request->selling_price,
                'quantity' => $request->quantity,
                'alert_quantity' => $request->alert_quantity,
                'stock_status' => $stockStatus,
                'group_name' => $request->group_name,
                'description' => $request->description,
                'status' => $request->status,
                
            ]);

            // Handle main image upload
            if ($request->hasFile('image')) {
                $this->uploadImage($product, $request->file('image'), 'main');
            }

            // Handle multiple image uploads
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $image) {
                    $this->uploadImage($product, $image, 'gallery');
                }
            }

            DB::commit();

            return redirect()->route('inventory.product.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    protected function uploadImage(Product $product, $file, $type = 'main')
    {
        $uploadPath = 'upload/Inventory/products';
        $filename = 'product_' . $product->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Create directory if not exists
        if (!File::exists(public_path($uploadPath))) {
            File::makeDirectory(public_path($uploadPath), 0755, true);
        }

        // Move file to destination
        $file->move(public_path($uploadPath), $filename);

        if ($type === 'main') {
            // Delete old main image if exists
            if ($product->image) {
                @unlink(public_path("{$uploadPath}/{$product->image}"));
            }
            $product->image = $filename;
            $product->save();
        } else {
            // Create product image record
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $filename,
            ]);
        }
    }

    /**
     * Upload and process image
     */


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $pageTitle = 'Admin Product Edit';
        $categories = Category::where('status',1)->latest()->get();
        $brands = Brand::where('status',1)->latest()->get();
        $tags = Tag::where('status',1)->latest()->get();
        $units = Unit::where('status',1)->latest()->get();
        return view('Accounts.product.edit',compact('pageTitle', 'product','categories', 'units'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            //'quantity' => 'required|integer|min:1',
            'status' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
            'category_id' => 'required',
            'unit_id' => 'required',
        ]);

        // Find the product by ID
        $product = Product::findOrFail($id);

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image && file_exists(public_path('upload/Accounts/products/' . $product->image))) {
                @unlink(public_path('upload/Accounts/products/' . $product->image)); // Delete the old image
            }

            // Store new image
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/Accounts/products'), $filename);

            // Update the product image with the new filename
            $product->image = $filename;
        }

        // $productCode = 'PRD' . strtoupper(Str::random(5));

        // Update the product data
        $product->update([
            'name' => $request->input('name'),
            // 'product_code' => $productCode,
            'price' => $request->input('price', $product->price), // Keep existing price if not provided
            'description' => $request->input('description', $product->description), // Keep existing description
            //'quantity' => $request->input('quantity', $product->quantity), // Keep existing quantity
            'status' => $request->has('status') ? $request->input('status') : $product->status, // Keep existing status
            'category_id' => $request->input('category_id'),
            'unit_id' => $request->input('unit_id'),
            'group_name' => $request->group_name,
            'product_code' => $request->input('product_code'),
        ]);

        // Redirect back to the product index with a success message
        return redirect()->route('accounts.product.index')->with('success', 'Product updated successfully!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $pageTitle = 'Product View';
        return view('Inventory.product.show',compact('pageTitle', 'product'));
    }

    public function AdminProductDestroy($id)
    {
        // Find the supplier by ID
        $product = Product::findOrFail($id);

        // Delete the product image if it exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete the supplier record
        $product->delete();

        // Redirect back to the supplier index with a success message
        return redirect()->route('accounts.product.index')->with('success', 'Product deleted successfully!');
    }

    // AJAX handler for filtering products by category
    public function getProductsByCategory($categoryId)
    {
        if ($categoryId == 'all') {
            // Return all products if "all" is selected
            $products = Product::all();
        } else {
            // Filter products by selected category
            $products = Product::where('category_id', $categoryId)->where('group_name', 'purchases')->with('unit')->get();
        }

        $category = Category::find($categoryId);
        // return response()->json($products);

        return response()->json([
            'products' => $products,
            'category' => $category,
        ]);
    }
}
