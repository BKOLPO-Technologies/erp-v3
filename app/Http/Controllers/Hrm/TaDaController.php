<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Staff;
use App\Models\Hrm\TaDa;
use App\Models\Hrm\TaDaType;
use App\Models\Hrm\TaDetail;
use App\Models\Hrm\TaFile;
use Illuminate\Support\Facades\Log;

class TaDaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'TA/DA list';
        $tads = TaDa::latest()->get();
        return view('Hr.tada.index',compact('pageTitle','tads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'TA\DA Create';
        $staffs = Staff::latest()->get();
        $tadaTypes = TaDaType::where('status',1)->latest()->get();
        return view('Hr.tada.create',compact('pageTitle','staffs','tadaTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'staff_id' => 'required',
            'tada_type' => 'required',
            // 'name' => 'required|string|max:255',
            // 'designation' => 'required|string|max:255',
            // 'project_name' => 'required|string|max:255',
            'date' => 'required|date',
            'purpose.*' => 'required|string|max:255',
            'amount.*' => 'required|numeric|min:0',
            'remarks.*' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            // Insert data into the `TADA` table
            $ta = TaDa::create([
                'user_id' => $request->staff_id,
                'tada_type_id' => $request->tada_type,
                'name' => $request->name,
                'designation' => $request->designation,
                'description' => $request->description,
                'project_name' => $request->project_name,
                'date' => $request->date,
                'subtotal' => $request->subtotal,
                'total' => $request->total,
            ]);
    
            // Insert data into the `details` table
            $details = [];
            foreach ($request->purpose as $index => $purpose) {
                $details[] = [
                    'ta_id' => $ta->id,
                    'purpose' => $purpose,
                    'amount' => $request->amount[$index],
                    'remarks' => $request->remarks[$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            TaDetail::insert($details);
    
            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePath = $file->store('upload/ta', 'public');
                    TaFile::create([
                        'ta_id' => $ta->id,
                        'file_path' => $filePath,
                    ]);
                }
            }
    
            return redirect()->route('hr.ta-da.index')->with('success', 'TA/DA record created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pageTitle = 'TA/DA Details';
        $tada = TaDa::findOrFail($id); // Retrieve the specific record
        return view('Hr.tada.show', compact('pageTitle', 'tada'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $taDa = TaDa::findOrFail($id);
        $pageTitle = 'TA/DA Details';
        $tadaTypes = TaDaType::where('status',1)->latest()->get();
        $staffs = Staff::latest()->get();
        return view('Hr.tada.edit', compact('pageTitle', 'taDa','tadaTypes','staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){
        // dd($request->all());
        $taDa = TaDa::findOrFail($id);
        // $taDa->update($request->only(['name', 'designation', 'project_name', 'date', 'subtotal', 'total']));

        // // Find the existing TaDa record
        // $taDa = TaDa::findOrFail($id);

        // Update the main TaDa record
        $taDa->update([
            'user_id' => $request->staff_id,
            'tada_type_id' => $request->tada_type,
            'name' => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
            'project_name' => $request->project_name,
            'date' => $request->date,
            'subtotal' => $request->subtotal,
            'total' => $request->total,
        ]);

        // Update details
        $taDa->details()->delete();
        foreach ($request->purpose as $key => $purpose) {
            $taDa->details()->create([
                'purpose' => $purpose,
                'amount' => $request->amount[$key],
                'remarks' => $request->remarks[$key] ?? '',
            ]);
        }

        return redirect()->route('hr.ta-da.index')->with('success', 'TA/DA updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){

        $taDa = TaDa::with('details', 'files')->findOrFail($id);

        // Delete associated details
        $taDa->details()->delete();

        // Delete associated files
        foreach ($taDa->files as $file) {
            $filePath = storage_path('app/public/' . $file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath); // Remove the file from storage
            }
            $file->delete(); // Remove the file record from the database
        }

        // Delete the main TA/DA record
        $taDa->delete();

        return redirect()->route('hr.ta-da.index')->with('success', 'TA/DA deleted successfully!');
    }

}
