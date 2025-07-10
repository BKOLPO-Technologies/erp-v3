<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Accounts\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function AdminCategoryIndex() 
    {
        $categories = Category::all();
        $pageTitle = 'Admin Category';
        return view('Accounts.inventory.category.index',compact('pageTitle', 'categories'));
    }

    public function AdminCategoryCreate() 
    {
        $pageTitle = 'Admin Category Create';
        return view('Accounts.inventory.category.create',compact('pageTitle'));
    }


    public function AdminCategoryStore(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate slug from name
        $slug = Str::slug($request->name);

        // Check if slug already exists and append a number if necessary
        $existingCategory = Category::where('slug', $slug)->first();
        if ($existingCategory) {
            $slug = $slug . '-' . (Category::count() + 1);
        }

        // Store the category with the unique slug
        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $request->image ?? null, // Add logic for image if needed
            'status' => $request->status ?? 1, // Default to active if not provided
            'vat' => $request->vat,
            'tax' => $request->tax
        ]);

        return redirect()->route('accounts.category.index')->with('success', 'Category created successfully!');
    }

    public function AdminCategoryStore2(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate slug from name
        $slug = Str::slug($request->name);

        // Check if slug already exists and append a number if necessary
        $existingCategory = Category::where('slug', $slug)->first();
        if ($existingCategory) {
            $slug = $slug . '-' . (Category::count() + 1);
        }

        // Store the category with the unique slug
        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $request->image ?? null, // Add logic for image if needed
            'status' => $request->status ?? 1, // Default to active if not provided
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Category added successfully.',
            'category' => $category, // Send back the created supplier data
        ]);
    }
    
    public function AdminCategoryEdit($id)
    {
        $category = Category::findOrFail($id);
        $pageTitle = 'Admin Category Edit';
        return view('Accounts.inventory.category.edit',compact('pageTitle', 'category'));
    }

    public function AdminCategoryUpdate(Request $request, $id)
    {

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        //dd($request->company);

        // Find the supplier by ID
        $category = Category::findOrFail($id);

        // Update the supplier data
        $category->update([
            'name' => $request->input('name'),
            'vat' => $request->input('vat'),
            'tax' => $request->input('tax'),
        ]);

        // Redirect back to the supplier index with a success message
        return redirect()->route('accounts.category.index')->with('success', 'Category updated successfully!');
    }

    public function AdminCategoryDestroy($id)
    {
        // Find the supplier by ID
        $category = Category::findOrFail($id);

        // Delete the supplier record
        $category->delete();

        // Redirect back to the supplier index with a success message
        return redirect()->route('accounts.category.index')->with('success', 'Category deleted successfully!');
    }

}
