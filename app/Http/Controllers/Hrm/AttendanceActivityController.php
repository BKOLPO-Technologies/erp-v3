<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Staff;
use App\Models\Hrm\AttendanceActivity;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class AttendanceActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Activity list';
        $activitys  = AttendanceActivity::latest()->get();
        return view('Hrm.activity.index',compact('pageTitle','activitys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staffs = Staff::latest()->get();
        $pageTitle = 'Activity Create';
        return view('Hrm.activity.create',compact('pageTitle','staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        // Create the activity record
        $activity = AttendanceActivity::create([
            'user_id' => $request->staff_id,
            'date' => $date,  // Store the date
            'time' => $time,  // Store the time
            'late' => $request->latitude,
            'long' => $request->longitude,
            'location' => $request->location,
        ]);

        return redirect()->route('hrm.activity.index')->with('success', 'Activity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activity = AttendanceActivity::findOrFail($id);
        $pageTitle = 'Activity View';

        return view('Hrm.activity.show',compact('activity','pageTitle'));
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
            $activity = AttendanceActivity::findOrFail($id);

            // Delete the attendance record
            $activity->delete();

            // Redirect back with a success message
            return redirect()->route('hrm.activity.index')->with('success', 'Activity record deleted successfully.');
        } catch (ModelNotFoundException $e) {
            // Redirect back with an error message if the record is not found
            return redirect()->route('hrm.activity.index')->with('error', 'Activity record not found.');
        } catch (\Exception $e) {
            // Handle any other exceptions
            return redirect()->route('hrm.activity.index')->with('error', 'An error occurred while deleting the attendance record.');
        }
    }
}
