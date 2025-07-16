<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Specification;

class SpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specifications = Specification::all();
        $pageTitle = 'Specification List'; 
        return view('Inventory.specification.index', compact('specifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Specification';
        return view('Inventory.specification.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $specification = new Specification();
        $specification->name = $request->name;
        $specification->description = $request->description;
        $specification->status = $request->status ?? 1;
        $specification->save();

        return redirect()->route('inventory.specification.index')->with('success', 'Specification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $specification = Specification::findOrFail($id);
        $pageTitle = 'Specification Details';
        return view('Inventory.specification.show', compact('specification', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $specification = Specification::findOrFail($id);
        $pageTitle = 'Edit Specification';
        return view('Inventory.specification.edit', compact('specification', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $specification = Specification::findOrFail($id);
        $specification->name = $request->name;
        $specification->description = $request->description;
        $specification->status = $request->status ?? 1;
        $specification->save();

        return redirect()->route('inventory.specification.index')->with('success', 'Specification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $specification = Specification::with(['products'])->findOrFail($id);

        // Check if specification is used by any products
        if ($specification->products()->exists()) {
            return redirect()->route('inventory.specification.index')
                ->with('warning', 'Cannot delete specification - it is currently in use by products.');
        }

        $specification->delete();

        return redirect()->route('inventory.specification.index')->with('success', 'Specification deleted successfully.');
    }
}
