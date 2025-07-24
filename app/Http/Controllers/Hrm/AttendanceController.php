<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Staff;
use App\Models\Hrm\Attendance;
use App\Models\Hrm\Shift;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Attendance list';
        $attendances  = Attendance::latest()->get();
        return view('Hr.attendance.index',compact('pageTitle','attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staffs = Staff::latest()->get();
        $shifts = Shift::where('status',1)->latest()->get();
        $pageTitle = 'Attendance Create';
        return view('Hr.attendance.create',compact('pageTitle','staffs','shifts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'late' => 'nullable|string',
            'long' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $dateTimeString = $request->input('date_time'); 
        $dateTime = Carbon::parse($dateTimeString);
    
        // Split the date and time
        $date = $dateTime->format('Y-m-d');  
        $time = $dateTime->format('H:i:s'); 

        // Create the attendance record
        $attendance = Attendance::create([
            'user_id' => $request->staff_id,
            'shift_id' => $request->shift_id,
            'date' => $date,  // Store the date
            'time' => $time,  // Store the time
            'late' => $request->latitude,
            'long' => $request->longitude,
            'location' => $request->location,
        ]);

        return redirect()->route('hr.attendance.index')->with('success', 'Attendance created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $pageTitle = 'Attendance View';

        return view('Hr.attendance.show',compact('attendance','pageTitle'));
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
        try {
            // Find the attendance by ID
            $attendance = Attendance::findOrFail($id);

            // Delete the attendance record
            $attendance->delete();

            // Redirect back with a success message
            return redirect()->route('hr.attendance.index')->with('success', 'Attendance record deleted successfully.');
        } catch (ModelNotFoundException $e) {
            // Redirect back with an error message if the record is not found
            return redirect()->route('hr.attendance.index')->with('error', 'Attendance record not found.');
        } catch (\Exception $e) {
            // Handle any other exceptions
            return redirect()->route('hr.attendance.index')->with('error', 'An error occurred while deleting the attendance record.');
        }
    }

}
