<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Staff;
use App\Models\Hrm\LeaveType;
use App\Models\Hrm\LeaveApplication;
use App\Models\Hrm\LeaveDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Leave Application list';
        $leaveApplications  = LeaveApplication::latest()->get();
        return view('Hr.leaves.index',compact('pageTitle','leaveApplications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staffs = Staff::latest()->get();
        $leaveTypes = LeaveType::where('status',1)->latest()->get();
        $pageTitle = 'Leave Application Create';
        return view('Hr.leaves.create',compact('pageTitle','staffs','leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'staff_id' => 'required',
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'documents' => 'nullable|array',  
            'documents.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx',  
            'reason' => 'required|string',
        ]);

        // Handle the leave application data
        $leaveApplication = LeaveApplication::create([
            'staff_id' => $request->staff_id,  // Change based on the actual user
            'leave_type_id' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
        ]);

        // Handle the documents upload if any and insert into leave_documents table
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                // Store document in the storage
                $documentPath = $document->store('upload/documents', 'public');

                // Insert into the leave_documents table
                LeaveDocument::create([
                    'leave_application_id' => $leaveApplication->id,  
                    'attacement' => $documentPath, 
                ]);
            }
        }

        // Redirect with a success message
        return redirect()->route('hr.leaves.index')->with('success', 'Leave application created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        $pageTitle = 'Leave Application Show';

        return view('Hr.leaves.show',compact('leaveApplication','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $leaveApplication = LeaveApplication::with('documents')->find($id);
        $pageTitle = 'Leave Application Edit';
      
        $staffs = Staff::latest()->get();
        $leaveTypes = LeaveType::where('status',1)->latest()->get();

        return view('Hr.leaves.edit',compact('leaveApplication','staffs','leaveTypes','pageTitle'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'staff_id' => 'required',
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'documents' => 'nullable|array',  
            'documents.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx',  
            'reason' => 'required|string',
        ]);

        $leaveApplication = LeaveApplication::findOrFail($id);

        $leaveApplication->staff_id = $request->staff_id;
        $leaveApplication->leave_type_id = $request->leave_type;
        $leaveApplication->start_date = $request->start_date;
        $leaveApplication->end_date = $request->end_date;
        $leaveApplication->start_time = $request->start_time;
        $leaveApplication->end_time = $request->end_time;
        $leaveApplication->reason = $request->reason;
        $leaveApplication->save();

        // Check if there are any existing documents
        if ($request->hasFile('documents')) {
            // First, delete old documents if needed (this is optional if you want to remove them before adding new ones)
            LeaveDocument::where('leave_application_id', $leaveApplication->id)->delete();

            // Loop through the uploaded files and store them
            foreach ($request->file('documents') as $document) {
                // Store the document in the storage
                $documentPath = $document->store('upload/documents', 'public');

                // Insert into the leave_documents table
                LeaveDocument::create([
                    'leave_application_id' => $leaveApplication->id,  
                    'attacement' => $documentPath, // Ensure this is the correct column name in your DB
                ]);
            }
        }

        return redirect()->route('hr.leaves.index')->with('success', 'Leave application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the leave application by ID
        $leaveApplication = LeaveApplication::findOrFail($id);
    
        // Delete associated documents
        if ($leaveApplication->documents && $leaveApplication->documents->isNotEmpty()) {
            foreach ($leaveApplication->documents as $document) {
                // Delete the document from the storage
                if (Storage::exists($document->attacement)) {
                    Storage::delete($document->attacement);
                }
    
                // Delete the document record from the database
                $document->delete();
            }
        }
    
        // Delete the leave application
        $leaveApplication->delete();
    
        // Redirect back with a success message
        return redirect()->route('hr.leaves.index')->with('success', 'Leave application and associated documents deleted successfully.');
    }
    
}
