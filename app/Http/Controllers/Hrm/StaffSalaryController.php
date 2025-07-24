<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Salary;
use App\Models\Hrm\User;
use App\Models\Hrm\Staff;
use Illuminate\Support\Facades\Auth;

class StaffSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Set the page title
        $pageTitle = "Salary Report";
        
        // Get the month and year from the request
        $month = $request->month;
        $year = $request->year;
        
        // Initialize the query to get salaries
        $salarys = Salary::with('staff')->latest();
    
        // Apply filters if provided
        if ($month) {
            $salarys = $salarys->where('month', $month);
        }
    
        if ($year) {
            $salarys = $salarys->where('year', $year);
        }
    
        // Get the salaries after applying filters
        $salarys = $salarys->get();
    
        // Calculate the 'will_get' value for each salary
        foreach ($salarys as $salary) {
            $salary->will_get = $salary->salary - $salary->payment_amount;
        }
    
        // Return the filtered results to the view
        return view('Hr.salary.index', compact('pageTitle', 'salarys'));
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = "Add Salary";
        $staffs = Staff::latest()->get();
        return view('Hr.salary.create', compact('pageTitle', 'staffs'));
    }

    

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // dd($request->all());

        $month = $request->input('month');
        $year = $request->input('year');
        $staff_ids = $request->input('staff_id');
        $salaries = $request->input('salary');

        foreach ($staff_ids as $key => $staff_id) {
            $salary = $salaries[$key];

            $staff = User::findOrFail($staff_id);
            $staff->salary = $salary;
            $staff->save();

            // Insert or update the salary for the current set of data
            $createdBy = Auth::user()->name;
            $createdAt = now();
            $updatedAt = now();

            Salary::updateOrCreate(
                ['staff_id' => $staff_id, 'month' => $month, 'year' => $year],
                ['salary' => $salary, 'created_by' => $createdBy, 'created_at' => $createdAt],
                ['updated_at' => $updatedAt]
            );
        }

        return redirect()->back()->with('success', 'Salary added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pageTitle = "Salary Report View";

        $salary = Salary::with('staff')->findOrFail($id);
        $salary->will_get = $salary->salary - $salary->payment_amount;

        return view('Hr.salary.show', compact('salary','pageTitle'));
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


    // Update salary payment status
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|string',
        ]);
    
        $salary = Salary::findOrFail($id);
    
        // Assuming you update the salary record with the payment amount and mode
        $salary->payment_amount = $request->payment_amount;
        $salary->payment_mode = $request->payment_mode;

        // Calculate the due amount
        $due = $salary->will_get - $salary->payment_amount;

        // Update the status based on the payment amount
        if ($salary->payment_amount == $salary->will_get) {
            // Full payment made, update status to 'Paid'
            $salary->status = 'Paid';
        } elseif ($salary->payment_amount > 0 && $salary->payment_amount < $salary->will_get) {
            // Partial payment made, update status to 'Partially Paid'
            $salary->status = 'Partially Paid';
        } else {
            // No payment made, update status to 'Not Paid'
            $salary->status = 'Unpaid';
        }

        $salary->updated_by = auth()->user()->name;
        $salary->save();

        return redirect()->route('hr.salary.index')->with('success', 'Salary payment updated successfully!');
     }
     
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salary = Salary::findOrFail($id);
        $salary->deleted_by = Auth::user()->name;
        $salary->delete();

        return redirect()->back()->with('success', 'Salary record deleted successfully.');
    }
}
