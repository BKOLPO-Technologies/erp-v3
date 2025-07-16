<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryVendor;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = InventoryVendor::all();
        $pageTitle = 'Vendor List';
        return view('Inventory.vendor.index', compact('vendors', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   $pageTitle = 'Vendor Create';
        return view('Inventory.vendor.create' , compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:11',
            'email' => 'nullable|email|unique:inventory_vendors,email',
            'address' => 'nullable|string',
            'zip' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postbox' => 'nullable|string|max:20',
            'taxid' => 'nullable|string|max:50',
            'bin' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        $vendor = InventoryVendor::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'title' => $request->title,
            'company' => $request->company,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'zip' => $request->zip,
            'city' => $request->city,
            'region' => $request->region,
            'country' => $request->country,
            'postbox' => $request->postbox,
            'taxid' => $request->taxid,
            'bin' => $request->bin,
            'bank_account_name' => $request->bank_account_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_routing_number' => $request->bank_routing_number,
            'password' => $request->password ? Hash::make($request->password) : null,
            // 'status'   => $request->status ?? 1,
        ]);

        return redirect()->route('inventory.vendor.index')->with('success', 'Vendor created successfully.');
    }

    public function store2(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            // 'designation' => 'required|string|max:255',
            // 'title' => 'required|string|max:255',
            // 'company'  => 'nullable|string|max:255',
            // 'phone'    => 'nullable|string|max:11',
            // 'email'    => 'nullable|email|unique:suppliers,email',
            // 'address'  => 'nullable|string',
            // 'zip' => 'nullable|string',
            // 'city'     => 'nullable|string|max:100',
            // 'region'   => 'nullable|string|max:100',
            // 'country'  => 'nullable|string|max:100',
            // 'postbox'  => 'nullable|string|max:20',
            // 'taxid'    => 'nullable|string|max:50',
            // 'bin' => 'nullable|string|max:50',
            // 'password' => 'nullable|string|min:6',
        ]);

        $vendor = InventoryVendor::create([
            'name'     => $request->name,
            'designation' => $request->designation,
            'title' => $request->title,
            'company'  => $request->company,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'address'  => $request->address,
            'zip' => $request->zip,
            'city'     => $request->city,
            'region'   => $request->region,
            'country'  => $request->country,
            'postbox'  => $request->postbox,
            'taxid'    => $request->taxid,
            'bin' => $request->bin,
            'bank_account_name' => $request->bank_account_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_routing_number' => $request->bank_routing_number,
            'password' => $request->password ? Hash::make($request->password) : null,
            // 'status'   => $request->status ?? 1,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Vendor added successfully.',
            'vendor' => $vendor, 
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vendor = InventoryVendor::findOrFail($id);
        $pageTitle = 'Vendor Details';
        return view('Inventory.vendor.show', compact('vendor' , 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vendor = InventoryVendor::findOrFail($id);
        $pageTitle = 'Vendor Edit';
        return view('Inventory.vendor.edit', compact('vendor' , 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:inventory_vendors,email,' . $id, 
            'address' => 'nullable|string|max:500',
            'zip' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postbox' => 'nullable|string|max:255',
            'taxid' => 'nullable|string|max:20',
            'bin' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
            'active' => 'nullable|boolean'
        ]);

        $vendor = InventoryVendor::findOrFail($id);

        $vendor->update([
            'name' => $request->input('name'),
            'designation' => $request->input('designation'),
            'title' => $request->input('title'),
            'company' => $request->input('company'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'zip' => $request->input('zip'),
            'city' => $request->input('city'),
            'region' => $request->input('region'),
            'country' => $request->input('country'),
            'postbox' => $request->input('postbox'),
            'taxid' => $request->input('taxid'),
            'bin' => $request->input('bin'),
            'bank_account_name' => $request->input('bank_account_name'),
            'bank_account_number' => $request->input('bank_account_number'),
            'bank_routing_number' => $request->input('bank_routing_number'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $vendor->password, // Only update password if provided
            // 'active' => $request->input('active') ?? $supplier->active, // Set default active value if not provided
        ]);

        return redirect()->route('inventory.vendor.index')->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vendor = InventoryVendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('inventory.vendor.index')->with('success', 'Vendor deleted successfully.');
    }
}
