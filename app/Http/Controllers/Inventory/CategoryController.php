<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\ProductCategory;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ProductCategory::orderBy('id', 'desc')->get();
        $pageTitle = 'Category List';
        return view('Inventory.category.index', compact('pageTitle', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Category Create';
        return view('Inventory.category.create', compact('pageTitle'));
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
            'status' => 'nullable|boolean',
        ]);

        try {
            $category = new ProductCategory();
            $category->name = $validated['name'];
            $category->slug = Str::slug($validated['name']);
            $category->status = $validated['status'] ?? 1;

            if ($request->file('logo')) {
                $file = $request->file('logo');
                @unlink(public_path('upload/Inventory/categories/'.$category->image));
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('upload/Inventory/categories'),$filename);
                $category['image'] = $filename;
            }

            $category->save();

            return redirect()->route('inventory.category.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create category: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = ProductCategory::findOrFail($id);
        $pageTitle = 'Category Details';
        return view('Inventory.category.show', compact('pageTitle', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = ProductCategory::findOrFail($id);
        $pageTitle = 'Category Edit';
        return view('Inventory.category.edit', compact('pageTitle', 'category'));
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
            'status' => 'nullable|boolean',
        ]);

        try {
            $category = ProductCategory::findOrFail($id);
            $category->name = $validated['name'];
            $category->slug = Str::slug($validated['name']);
            $category->status = $validated['status'] ?? 1;

            if ($request->file('logo')) {
                // Delete old logo if it exists
                if ($category->image && file_exists(public_path('upload/Inventory/categories/' . $category->image))) {
                    @unlink(public_path('upload/Inventory/categories/' . $category->image));
                }
                // Store new logo
                $file = $request->file('logo');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/Inventory/categories'), $filename);
                $category->image = $filename;
            }

            $category->save();

            return redirect()->route('inventory.category.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update category: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = ProductCategory::findOrFail($id);

            // Match the upload path here
            $imagePath = public_path('upload/Inventory/categories/' . $category->image);
            if ($category->image && file_exists($imagePath)) {
                @unlink($imagePath);
            }

            $category->delete();

            return redirect()->route('inventory.category.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete category: ' . $e->getMessage()]);
        }
    }

}
