<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\InventoryProduct;
use App\Models\Inventory\ProductImage;
use App\Models\Inventory\ProductTag;
use App\Models\Inventory\ProductBrand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory\ProductUnit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index() 
    {
        $products = InventoryProduct::orderBy('id', 'desc')->get();
        $pageTitle = 'Product List';
        return view('Inventory.product.index',compact('pageTitle', 'products'));
    }

    public function create() 
    {
        $pageTitle = 'Product Create';
        $categories = ProductCategory::where('status',1)->latest()->get();
        $brands = ProductBrand::where('status',1)->latest()->get();
        $tags = ProductTag::where('status',1)->latest()->get();
        $units = ProductUnit::where('status',1)->latest()->get();
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
            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'required|exists:product_brands,id',
            'tag_id' => 'required|array',
            'tag_id.*' => 'exists:product_tags,id',
            'unit_id' => 'required|exists:product_units,id',
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
            $product = InventoryProduct::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'product_code' => $request->product_code,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
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

            // Sync tags
            if ($request->has('tag_id')) {
                $product->tags()->sync($request->tag_id);
            }

            // Save specifications
            if ($request->has('specifications')) {
                foreach ($request->specifications as $spec) {
                    $product->specifications()->create([
                        'title' => $spec['title'],
                        'description' => $spec['description'],
                        'status' => $spec['status'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('inventory.product.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    protected function uploadImage(InventoryProduct $product, $file, $type = 'main')
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
        $product = InventoryProduct::with(['tags', 'images', 'specifications'])->findOrFail($id);
        $pageTitle = 'Product Edit';
        $categories = ProductCategory::where('status', 1)->latest()->get();
        $brands = ProductBrand::where('status', 1)->latest()->get();
        $tags = ProductTag::where('status', 1)->latest()->get();
        $units = ProductUnit::where('status', 1)->latest()->get();
        
        return view('Inventory.product.edit', compact(
            'pageTitle', 
            'product',
            'categories',
            'brands',
            'tags',
            'units'
        ));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'required|exists:product_brands,id',
            'tag_id' => 'required|array',
            'tag_id.*' => 'exists:product_tags,id',
            'unit_id' => 'required|exists:product_units,id',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'product_code' => 'nullable|string|max:50|unique:products,product_code,'.$id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|boolean',
            'group_name' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $product = InventoryProduct::findOrFail($id);

            // Auto-generate stock status based on quantity
            $stockStatus = 'in_stock';
            if ($request->quantity <= 0) {
                $stockStatus = 'out_of_stock';
            } elseif ($request->quantity <= $request->alert_quantity) {
                $stockStatus = 'low_stock';
            }

            // Update the product with the validated data
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'product_code' => $request->product_code,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'unit_id' => $request->unit_id,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'alert_quantity' => $request->alert_quantity,
                'stock_status' => $stockStatus,
                'group_name' => $request->group_name,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Handle main image upload
            if ($request->hasFile('image')) {
                $this->uploadImageUpdate($product, $request->file('image'), 'main');
            }

            // Handle multiple image uploads
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $image) {
                    $this->uploadImageUpdate($product, $image, 'gallery');
                }
            }

            // Sync tags
            if ($request->has('tag_id')) {
                $product->tags()->sync($request->tag_id);
            }

            // Update specifications
            if ($request->has('specifications')) {
                // First delete all existing specifications
                $product->specifications()->delete();
                
                // Then create new ones
                foreach ($request->specifications as $spec) {
                    $product->specifications()->create([
                        'title' => $spec['title'],
                        'description' => $spec['description'],
                        'status' => $spec['status'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('inventory.product.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function deleteImage(Request $request)
    {
        try {
            $image = ProductImage::findOrFail($request->image_id);
            
            // Delete file from storage
            $filePath = public_path('upload/Inventory/products/'.$image->image);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            
            // Delete record from database
            $image->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    protected function uploadImageUpdate(InventoryProduct $product, $file, $type = 'main')
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

    public function show($id)
    {
        $product = InventoryProduct::findOrFail($id);
        $pageTitle = 'Product View';
        return view('Inventory.product.show',compact('pageTitle', 'product'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Find the product by ID with all relationships
            $product = InventoryProduct::with(['images', 'specifications'])->findOrFail($id);

            // Delete main product image if exists
            if ($product->image) {
                $imagePath = public_path('upload/Inventory/products/'.$product->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // Delete all product images
            foreach ($product->images as $image) {
                $imagePath = public_path('upload/Inventory/products/'.$image->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
                $image->delete(); // Delete the image record
            }

            // Delete all specifications
            $product->specifications()->delete();

            // Detach all tags
            $product->tags()->detach();

            // Finally delete the product
            $product->delete();

            DB::commit();

            return redirect()->route('inventory.product.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('inventory.product.index')->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    // AJAX handler for filtering products by category
    public function getProductsByCategory($categoryId)
    {
        if ($categoryId == 'all') {
            // Return all products if "all" is selected
            $products = InventoryProduct::all();
        } else {
            // Filter products by selected category
            $products = InventoryProduct::where('category_id', $categoryId)->where('group_name', 'purchases')->with('unit')->get();
        }

        $category = ProductCategory::find($categoryId);
        // return response()->json($products);

        return response()->json([
            'products' => $products,
            'category' => $category,
        ]);
    }
}
