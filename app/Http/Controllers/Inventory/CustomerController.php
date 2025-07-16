<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryCustomer; 

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = InventoryCustomer::all();
        $pageTitle = 'Customer List';
        return view('Inventory.customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   $pageTitle = 'Customer Create';
        return view('Inventory.customer.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'company'  => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:11',
            'email'    => 'nullable|email|unique:inventory_customers,email',
            'address'  => 'nullable|string',
            'zip' => 'nullable|string',
            'city'     => 'nullable|string|max:100',
            'region'   => 'nullable|string|max:100',
            'country'  => 'nullable|string|max:100',
            'postbox'  => 'nullable|string|max:20',
            'taxid'    => 'nullable|string|max:50',
            'bin' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
            'status'   => 'boolean',
        ]);

        $client = InventoryCustomer::create([
            'name'     => $request->name,
            'designation' => $request->designation,
            'title'     => $request->title,
            'company'  => $request->company,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'address'  => $request->address,
            'zip'      => $request->zip,
            'city'     => $request->city,
            'region'   => $request->region,
            'country'  => $request->country,
            'postbox'  => $request->postbox,
            'taxid'    => $request->taxid,
            'bank_account_name'    => $request->bank_account_name,
            'bank_account_number'    => $request->bank_account_number,
            'bank_routing_number'    => $request->bank_routing_number,
            'bin' => $request->bin,
            'password' => $request->password ? Hash::make($request->password) : null,
            'status'   => $request->status ?? true,
        ]);

        return redirect()->route('inventory.customer.index')->with('success', 'Customer created successfully.');
    }

    public function store2(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name'     => 'nullable|string|max:255',
            // 'designation' => 'nullable|string|max:255',
            // 'title' => 'required|string|max:255',
            // 'company'  => 'nullable|string|max:255',
            // 'phone'    => 'nullable|string|max:11',
            // 'email'    => 'nullable|email|unique:clients,email',
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

        $client = InventoryCustomer::create([
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
            'bank_account_name'    => $request->bank_account_name,
            'bank_account_number'    => $request->bank_account_number,
            'bank_routing_number'    => $request->bank_routing_number,
            'bin' => $request->bin,
            'password' => $request->password ? Hash::make($request->password) : null,
            'status'   => $request->status ?? 1,
        ]);

        //return redirect()->route('accounts.supplier.index')->with('success', 'Supplier added successfully.');
        return response()->json([
            'success'  => true,
            'message'  => 'Customer added successfully.',
            'client' => $client, // Send back the created client data
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = InventoryCustomer::findOrFail($id);
        $pageTitle = 'Customer Details';
        return view('Inventory.customer.show', compact('customer','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = InventoryCustomer::findOrFail($id);
        $pageTitle = 'Customer Edit';
        return view('Inventory.customer.edit', compact('customer','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $id, // Ensure unique email excluding current supplier
            'address' => 'nullable|string|max:500',
            'zip' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postbox' => 'nullable|string|max:255',
            'taxid' => 'nullable|string|max:20',
            'bin' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6', // Only validate password if provided
            'status' => 'nullable|boolean'
        ]);

        $customer = InventoryCustomer::findOrFail($id);

        $customer->update([
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
            'bank_account_name'    => $request->input('bank_account_name'),
            'bank_account_number'    => $request->input('bank_account_number'),
            'bank_routing_number'    => $request->input('bank_routing_number'),
            'bin' => $request->input('bin'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : null, // Only update password if provided
            'status' => $request->input('status') ?? true, // Set default active value if not provided
        ]);

        // Redirect back to the supplier index with a success message
        return redirect()->route('inventory.customer.index')->with('success', 'customer updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = InventoryCustomer::findOrFail($id);
        $customer->delete();

        return redirect()->route('inventory.customer.index')->with('success', 'Customer deleted successfully.');
    }
}
