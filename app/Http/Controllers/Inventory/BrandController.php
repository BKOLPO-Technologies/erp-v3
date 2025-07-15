<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\ProductBrand;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index() 
    {
        $brands = ProductBrand::orderBy('id', 'desc')->get();
        $pageTitle = 'Brand List';
        return view('Inventory.brand.index',compact('pageTitle', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Brand Create';
        return view('Inventory.brand.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        try {
            $brand = new ProductBrand();
            $brand->name = $validated['name'];
            $brand->slug = Str::slug($validated['name']);
            $brand->description = $validated['description'] ?? null;
            $brand->status = $validated['status'] ?? 1;

            if ($request->file('logo')) {
                $file = $request->file('logo');
                @unlink(public_path('upload/Inventory/brands/'.$brand->logo));
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('upload/Inventory/brands'),$filename);
                $brand['logo'] = $filename;
            }

            $brand->save();

            return redirect()->route('inventory.brand.index')->with('success', 'Brand created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create brand: ' . $e->getMessage()]);
        }
    }

    public function store2(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate slug from name
        $slug = Str::slug($request->name);

        // Check if slug already exists and append a number if necessary
        $existingBrand = ProductBrand::where('slug', $slug)->first();
        if ($existingBrand) {
            $slug = $slug . '-' . (ProductBrand::count() + 1);
        }

        // Store the brand with the unique slug
        $brand = ProductBrand::create([
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->status ?? 1, // Default to active if not provided
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Brand added successfully.',
            'brand' => $brand,
            'all_brands' => ProductBrand::where('status',1)->latest()->get()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = ProductBrand::findOrFail($id);
        $pageTitle = 'Brand Details';
        return view('Inventory.brand.show', compact('pageTitle', 'brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = ProductBrand::findOrFail($id);
        $pageTitle = 'Brand Edit';
        return view('Inventory.brand.edit', compact('pageTitle', 'brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        try {
            $brand = ProductBrand::findOrFail($id);
            $brand->name = $validated['name'];
            $brand->slug = Str::slug($validated['name']);
            $brand->description = $validated['description'] ?? null;
            $brand->status = $validated['status'] ?? 1;

            if ($request->file('logo')) {
                // Delete old logo if it exists
                if ($brand->logo && file_exists(public_path('upload/Inventory/brands/' . $brand->logo))) {
                    @unlink(public_path('upload/Inventory/brands/' . $brand->logo));
                }
                // Store new logo
                $file = $request->file('logo');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/Inventory/brands'), $filename);
                $brand->logo = $filename;
            }

            $brand->save();

            return redirect()->route('inventory.brand.index')->with('success', 'Brand updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update brand: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $brand = ProductBrand::findOrFail($id);
            if ($brand->logo && file_exists(public_path('storage/' . $brand->logo))) {
                @unlink(public_path('storage/' . $brand->logo)); // Delete logo
            }
            $brand->delete();

            return redirect()->route('inventory.brand.index')->with('success', 'Brand deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete brand: ' . $e->getMessage()]);
        }
    }
}
