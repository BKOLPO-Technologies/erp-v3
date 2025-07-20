<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Order;
use App\Models\Inventory\OrderItem;
use App\Models\Inventory\InventoryProduct;
use App\Models\Inventory\InventoryVendor;
use App\Models\Inventory\Stock;
use App\Models\Inventory\StockOutward;
use Illuminate\Support\Str;


class StockOutwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockOutwards = StockOutward::with(['product', 'vendor'])->get();
        $pageTitle = 'Stock Outward List';
        return view('Inventory.stockoutward.index', compact('stockOutwards', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = InventoryVendor::active()->get();
        $products = InventoryProduct::active()->get();
        $pageTitle = 'Stock Inward';
        return view('Inventory.stockoutward.create', compact('vendors','products', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
