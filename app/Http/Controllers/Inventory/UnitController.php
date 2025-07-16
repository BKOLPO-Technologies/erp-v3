<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\ProductUnit;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = ProductUnit::orderBy('id', 'desc')->get();
        $pageTitle = 'Unit List';
        return view('Inventory.unit.index', compact('pageTitle', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Unit Create';
        return view('Inventory.unit.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        try {
            $unit = new ProductUnit();
            $unit->name = $validated['name'];
            $unit->status = $validated['status'] ?? 1;

            $unit->save();

            return redirect()->route('inventory.unit.index')->with('success', 'Unit created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create unit: ' . $e->getMessage()]);
        }
    }

    public function store2(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Store the category with the unique slug
        $unit = ProductUnit::create([
            'name' => $request->name,
            'status' => $request->status ?? 1, // Default to active if not provided
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Unit added successfully.',
            'unit' => $unit, // Send back the created supplier data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = ProductUnit::findOrFail($id);
        $pageTitle = 'Unit Details';
        return view('Inventory.unit.show', compact('pageTitle', 'unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $unit = ProductUnit::findOrFail($id);
        $pageTitle = 'Unit Edit';
        return view('Inventory.unit.edit', compact('pageTitle', 'unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        try {
            $unit = ProductUnit::findOrFail($id);
            $unit->name = $validated['name'];
            $unit->status = $validated['status'] ?? 1;

            $unit->save();

            return redirect()->route('inventory.unit.index')->with('success', 'Unit updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update unit: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $unit = ProductUnit::findOrFail($id);
            $unit->delete();

            return redirect()->route('inventory.unit.index')->with('success', 'Unit deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete unit: ' . $e->getMessage()]);
        }
    }
}
