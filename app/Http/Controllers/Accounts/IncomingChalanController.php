<?php

namespace App\Http\Controllers\Accounts;

use Carbon\Carbon;
use App\Models\Accounts\Sale;
use App\Models\Accounts\Ledger;

use App\Models\Accounts\Client;
use App\Models\Accounts\Product;
use App\Models\Accounts\StockIn;
use App\Models\Accounts\Purchase;
use Illuminate\Http\Request;
use App\Models\Accounts\IncomingChalan;
use App\Models\Accounts\InChalanInventory;
use App\Models\Accounts\JournalVoucher;
use App\Models\Accounts\JournalVoucherDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; 
use App\Models\Accounts\IncomingChalanProduct;

class IncomingChalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Incoming Chalan List';
    
        // Fetch all incoming chalans with related sale details
        $incomingchalans = IncomingChalan::with('purchase')->latest()->get();
    
        return view('Accounts.purchase.chalan.index', compact('pageTitle', 'incomingchalans'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        $pageTitle = 'Incoming Chalan';

        $purchases = Purchase::latest()->get();

        return view('Accounts.purchase.chalan.create',compact('pageTitle','purchases')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Log::info('Incoming Chalan Store: Request received', ['request' => $request->all()]);

        DB::beginTransaction(); // Start Transaction

        try {
            // Log::info('Incoming Chalan Store: Request received', ['request' => $request->all()]);
            // Validate request data
            $request->validate([
                'purchase_id' => 'required|exists:purchases,id',
                'invoice_date' => 'required|date',
                'description' => 'nullable|string',
                'product_id' => 'required|array',
                'quantity' => 'required|array',
                'receive_quantity' => 'required|array',
            ]);

            //Log::info('Validation passed.');

            // Create IncomingChalan record
            $incomingChalan = IncomingChalan::create([
                'purchase_id' => $request->purchase_id,
                'invoice_date' => $request->invoice_date,
                'description' => $request->description,
            ]);

            //Log::info('IncomingChalan created', ['incomingChalan' => $incomingChalan]);

            // Generate current timestamp
            $timestamp = now()->format('YmdHis'); // Format: YYYYMMDDHHMMSS

            // Insert product details into IncomingChalanProduct table
            foreach ($request->product_id as $index => $productId) {
                //Log::info("Processing product: {$productId}");
                $incomingChalanProduct = IncomingChalanProduct::create([
                    'incoming_chalan_id' => $incomingChalan->id,
                    'product_id' => $productId,
                    'quantity' => $request->quantity[$index],
                    'receive_quantity' => $request->receive_quantity[$index],
                ]);
                //Log::info('IncomingChalanProduct created', ['incomingChalanProduct' => $incomingChalanProduct]);

                // // Fetch product details
                // $product = Product::find($productId);
                // if (!$product) {
                //     throw new \Exception("Product with ID {$productId} not found.");
                // }

                // // Store product details into InChalanInventory table
                // StockIn::create([
                //     // 'reference_lot' => 'Ref-' . $incomingChalan->id . '-' . $productId,
                //     'reference_lot' => 'Ref-' . $incomingChalan->id . '-' . $productId . '-' . $timestamp, // Unique reference
                //     'product_id' => $productId,
                //     'purchase_id' => $request->purchase_id,
                //     'incoming_chalan_product_id' => $incomingChalanProduct->id,
                //     'quantity' => $request->receive_quantity[$index],
                //     'price' => $product->price * $request->receive_quantity[$index], 
                // ]);

                // // Decrease product stock
                // if ($product->quantity >= $request->receive_quantity[$index]) {
                //     $product->decrement('quantity', $request->receive_quantity[$index]);
                // } else {
                //     throw new \Exception("Insufficient stock for Product ID {$productId}.");
                // }

                // Increase product stock
                //$product->increment('quantity', $request->receive_quantity[$index]);

            }

            // journal add amount
            // Step 1: Fetch Purchase record
            $purchase = Purchase::find($request->purchase_id);

            // Step 2: Get purchase amount
            $purchase_amount = $purchase->total ?? 0; // If purchase doesn't have amount, default to 0

            // Step 3: Retrieve Purchase ledger
            $ledger = Ledger::where('name', 'Purchase Accounts')->first();

            // Step 4: If the ledger exists, proceed with journal creation/update
            if ($ledger) {
                // Step 5: Check if this invoice already exists in JournalVoucher
                $journalVoucher = JournalVoucher::where('transaction_code', $purchase->invoice_no)->first();

                // If journal voucher exists, update it; otherwise, create a new one
                if ($journalVoucher) {
                    // Update existing journal voucher
                    $journalVoucher->update([
                        'transaction_date' => $request->invoice_date,
                        'description' => $request->description,
                    ]);

                    // Update the corresponding journal voucher details
                    JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)
                        ->where('ledger_id', $ledger->id)
                        ->update([
                            'debit' => $purchase_amount, // Update the debit amount
                            'credit' => 0,           // Credit is zero for debit entry
                            'updated_at' => now(),
                        ]);
                } else {
                    // If journal voucher does not exist, create a new one
                    $journalVoucher = JournalVoucher::create([
                        'transaction_code'  => $purchase->invoice_no,
                        'transaction_date'  => $request->invoice_date,
                        'description'       => $request->description,
                        'status'            => 1, // Pending status
                    ]);

                    // Create journal voucher details
                    JournalVoucherDetail::create([
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id'          => $ledger->id,
                        'reference_no'       => $request->reference_no ?? '',
                        'description'        => $request->description ?? '',
                        'debit'              => $purchase_amount,
                        'credit'             => 0,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                }
            }

            DB::commit(); // Commit Transaction
            //Log::info('Transaction committed successfully.');

            // Success message after processing everything correctly
            return redirect()->route('accounts.incoming.chalan.index')->with('success', 'Incoming Chalan created successfully!');
            
        } catch (\Exception $e) {
            // Handle any exception that occurs
            DB::rollBack(); // Rollback transaction if an error occurs
            //Log::error('Error in Incoming Chalan Store: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function view(string $id)
    {
        $pageTitle = 'In Coming Chalan';

        $chalan = IncomingChalan::with('purchase', 'products')->findOrFail($id);
        $sales = Sale::latest()->get();

        return view('Accounts.purchase.chalan.view',compact('pageTitle','chalan', 'sales'));
    }

    /**
     * Show the form for editing the specified resource.
     */ 

    public function edit($id)
    {
        $pageTitle = 'Edit Incoming Chalan';
        // Find the IncomingChalan by ID
        $chalan = IncomingChalan::with('purchase', 'products')->findOrFail($id);
        //$incomingChalan = IncomingChalan::with('sale', 'products')->findOrFail($id);
        $sales = Sale::latest()->get();

        // Pass the IncomingChalan and its products to the view
        return view('Accounts.purchase.chalan.edit', compact('pageTitle', 'chalan', 'sales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'nullable|string|max:1000',
            'receive_quantity' => 'required|array',
            'receive_quantity.*' => 'integer|min:0', // Validate each quantity as an integer
        ]);

        // Find the IncomingChalan record
        $incomingChalan = IncomingChalan::findOrFail($id);

        // Update only the description
        $incomingChalan->update([
            'description' => $request->description,
        ]);

        // Update receive_quantity for each product
        foreach ($request->receive_quantity as $index => $qty) {
            $incomingChalan->products[$index]->update([
                'receive_quantity' => $qty,
            ]);
        }

        return redirect()->route('accounts.incoming.chalan.index')->with('success', 'Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $chalan = IncomingChalan::findOrFail($id);

        // Optional: Delete related IncomingChalanProduct records if they exist
        $chalan->products()->delete();

        // Delete the chalan record
        $chalan->delete();

        return redirect()->back()->with('success', 'Incoming Chalan deleted successfully!');
    }

}
