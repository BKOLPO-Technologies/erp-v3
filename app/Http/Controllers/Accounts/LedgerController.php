<?php

namespace App\Http\Controllers\Accounts;

use Auth;
use Hash;
use Carbon\Carbon;
use App\Models\Accounts\Ledger;
use Illuminate\View\View;
use App\Models\Accounts\LedgerGroup;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\LedgerExport;
use App\Models\Accounts\LedgerSubGroup;
use App\Models\Accounts\LedgerGroupSubgroupLedger;
use App\Models\Accounts\JournalVoucher;
use App\Models\Accounts\JournalVoucherDetail;
use App\Traits\SumLedgerAmounts;
use App\Models\Accounts\LedgerGroupDetail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LedgerController extends Controller
{
    use SumLedgerAmounts;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Ledger List';

        $ledgers = Ledger::with(['groups', 'journalVoucherDetails'])->get();

        //dd($ledgers);

        $ledgers->each(function ($ledger) {
            $ledger->ledgerSums = $this->getLedgerSums($ledger);
        });

        $totals = $this->getTotalSums($ledgers);
        
        return view('Accounts.ledger.index',compact('pageTitle','ledgers','totals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Ledger Create';
        $groups = LedgerGroup::where('status',1)->latest()->get();
        return view('Accounts.ledger.create',compact('pageTitle','groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        //dd($request->all());
        // Validate the incoming request
        $validatedData =  $request->validate([
            'name' => 'required|string|max:255',
            //'group_id' => 'required|array',
            'group_id' => 'required',
            //'group_id.*' => 'exists:ledger_groups,id'
            'sub_group_id' => 'required|exists:ledger_sub_groups,id',
            'status' => 'required|integer',
        ]);
        
        // Create the Ledger record
        $ledger = Ledger::create([
            'name'          => $request->name,
            'status'        => $request->status,
            'debit'        => $request->debit,
            'credit'        => $request->credit,
            'created_by'    => Auth::user()->id,
        ]);

        // // Attach groups to Ledger
        // foreach ($request->group_id as $groupId) {
        //     LedgerGroupDetail::create([
        //         'ledger_id' => $ledger->id,
        //         'group_id' => $groupId
        //     ]);
        // }

        // 🔹 Pivot Table Entry
        DB::table('ledger_group_subgroup_ledgers')->insert([
            'group_id'     => $request->group_id, // Using directly from request
            'sub_group_id' => $request->sub_group_id,
            'ledger_id'    => $ledger->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('accounts.ledger.index')->with('success', 'Ledger created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ledger = Ledger::findOrFail($id);

        $pageTitle = 'Ledger View';
        return view('Accounts.ledger.show', compact('ledger','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ledger = Ledger::with('groups', 'ledgerGroupSubgroup')->findOrFail($id);
        $groups = LedgerGroup::where('status', 1)->latest()->get();
        $pageTitle = 'Ledger Edit';
        // // Find the Ledger Sub Group
        // $subGroup = LedgerSubGroup::findOrFail($id);
        // $subGroups = LedgerSubGroup::where('ledger_group_id', $id)->get();

        // dd($subGroups);

        // // Retrieve the selected group_id from the pivot table
        // $selectedGroupId = DB::table('ledger_group_subgroup_ledgers')
        //     ->where('ledger_id', $id)
        //     ->value('group_id');

        // Retrieve the selected group_id from the relation
        $selectedGroupId = optional($ledger->ledgerGroupSubgroup)->group_id;
        $selectedSubGroupId = optional($ledger->ledgerGroupSubgroup)->sub_group_id;
        
        // Fetch sub-groups based on the selected group (if exists)
        $subGroups = $selectedGroupId ? LedgerSubGroup::where('ledger_group_id', $selectedGroupId)->get() : [];
        
        return view('Accounts.ledger.edit', compact('ledger','groups','pageTitle', 'subGroups', 'selectedGroupId', 'selectedSubGroupId'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //dd($request->all());

    //     $validatedData =  $request->validate([
    //         'name' => 'required|string|max:255',
    //         'group_id' => 'required|array',
    //         //'group_id.*' => 'exists:ledger_groups,id'
    //         'sub_group_id' => 'required|exists:ledger_sub_groups,id',
    //         'status' => 'required|integer',
    //     ]);

    //     $ledger = Ledger::findOrFail($id);
    //     $ledger->name = $request->input('name');
    //     $ledger->debit = $request->input('debit');
    //     $ledger->credit = $request->input('credit');
    //     $ledger->opening_balance = $request->opening_balance;
    //     $ledger->status = $request->input('status');
    //     $ledger->save();
        
    //     // Sync groups in ledger_group_details
    //     $ledger->groups()->sync($request->group_id);

    //     return redirect()->route('ledger.index')->with('success', 'Ledger updated successfully.');
    // }

    public function update(Request $request, string $id)
    {
        // Validate the request
        $validatedData =  $request->validate([
            'name'         => 'required|string|max:255',
            'group_id'     => 'required', // Assuming it's a single value, not an array
            'sub_group_id' => 'required|exists:ledger_sub_groups,id',
            'status'       => 'required|integer',
        ]);

        $ledger = Ledger::findOrFail($id);
        $ledger->name = $request->input('name');
        // $ledger->debit = $request->input('debit');
        // $ledger->credit = $request->input('credit');
        $ledger->opening_balance = $request->input('opening_balance');
        $ledger->status = $request->input('status');
        $ledger->save();

        // ✅ Update if exists, otherwise insert (No need to delete)

        $ledgerGroupSubgroupLedger = LedgerGroupSubgroupLedger::updateOrCreate(
            ['ledger_id' => $ledger->id], // Condition to check if the record exists
            [
                'group_id' => $request->group_id,
                'sub_group_id' => $request->sub_group_id,
                'updated_at' => now(), // Automatically update the timestamp
            ]
        );

        // $lastMonthLastDate = now()->subMonth()->endOfMonth()->toDateString(); // 🔹 গত মাসের শেষ তারিখ

        // // 🔹 Generate Transaction Code
        // $randomNumber = rand(100000, 999999);
        // $fullDate = now()->format('d/m/y');
        // $transactionCode = 'BCL-O-'.$fullDate.' - '.$randomNumber;

        // // 🔹 Create Journal Voucher 
        // $journalVoucher = JournalVoucher::create([
        //     'transaction_code' => $transactionCode,
        //     // 'company_id'       => $company->id,
        //     // 'branch_id'        => $request->branch_id,
        //     'transaction_date' => $lastMonthLastDate,
        // ]);

        // $ledgerGroup = LedgerGroup::findOrFail($request->group_id);
        // $groupType = $ledgerGroup->group_name; 

        // $debit = 0;
        // $credit = 0;
        
        // if ($groupType === 'Assets') {
        //     $debit = $ledger->opening_balance;
        // } elseif ($groupType === 'Liabilities') {
        //     $credit = $ledger->opening_balance;
        // }


        // // 🔹 Journal Entry 
        // JournalVoucherDetail::create([
        //     'journal_voucher_id' => $journalVoucher->id,
        //     'ledger_id'          => $ledger->id,
        //     'reference_no'       => "REF-" . rand(100000, 999999),
        //     'description'        => 'Opening Balance Entry',
        //     'debit'              => $debit,
        //     'credit'             => $credit,
        //     'created_at'         => $lastMonthLastDate,
        //     'updated_at'         => $lastMonthLastDate,
        // ]);


        return redirect()->route('accounts.ledger.index')->with('success', 'Ledger updated successfully.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Step 1: Find the Ledger by ID
        $ledger = Ledger::findOrFail($id);

        // Step 2: Check if the Ledger has any related JournalVoucherDetail
        if ($ledger->journalVoucherDetails()->exists()) {
            // If JournalVoucherDetails exist, you cannot delete the ledger directly
            return redirect()->route('accounts.ledger.index')
                            ->with('error', 'Cannot delete this Ledger because it has related Journal Voucher entries. Please delete the journal entries first.');
        }

        // Step 3: If no related JournalVoucherDetails, proceed with detaching the groups
        $ledger->groups()->detach();

        // Step 4: Delete the ledger
        $ledger->delete();

        return redirect()->route('accounts.ledger.index')->with('success', 'Ledger deleted successfully.');
    }
    // import download formate
    public function downloadFormat()
    {
        return Excel::download(new LedgerExport, 'Ledger_Import_Template.xlsx');
    }

    // import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
    
        $file = $request->file('file');
        $data = Excel::toArray([], $file);
        $rows = $data[0]; // Get all rows from the first sheet (index 0)
    
        // Skip the first row, which contains headers
        $header = $rows[0]; // The first row (headers)
        $rows = array_slice($rows, 1); // Remove the first row from data

        // Find the actual column name containing "Group Name"
        $groupColumn = null;
        foreach ($header as $col) {
            if (str_contains($col, 'Group Name')) { // Match dynamically
                $groupColumn = $col;
                break;
            }
        }
      
        // Loop through the rows and trim spaces around the keys and values
        foreach ($rows as $row) {
            // Map column headers to keys for easy access
            $row = array_combine($header, $row); // Combine header names with data rows
            // dd($row);
            // Create ledger
            $ledger = Ledger::create([
                'name' => $row['Ledger Name'], // Access the correct column
                'debit' => $row['Opening Balance'],
                'credit' => $row['Ending Balance'],
            ]);
            // Attach ledger to group(s)
            if (!empty($row[$groupColumn])) {
                foreach (explode(',', $row[$groupColumn]) as $groupId) {
                    LedgerGroupDetail::create([
                        'ledger_id' => $ledger->id,
                        'group_id' => trim($groupId),
                    ]);
                }
            }
        }
    
        return back()->with('success', 'Ledger data imported successfully!');
    }


}
