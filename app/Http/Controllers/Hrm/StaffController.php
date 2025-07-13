<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Hrm\Staff;
use App\Models\Hrm\User;
use App\Models\Hrm\Education;
use App\Models\Hrm\Certification;
use App\Models\Hrm\Award;
use App\Models\Hrm\EmploymentHistory;
use App\Models\Hrm\StaffDocument;
use Illuminate\Support\Facades\Storage;
use Exception;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Staff list';
        $staffs = Staff::latest()->get();
        return view('Hrm.staff.index',compact('pageTitle','staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Staff Create';
        return view('Hrm.staff.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional, must be an image
                'hr_code' => 'required|string|unique:staff,hr_code', // Required and unique in the 'staff' table
                'full_name' => 'required|string|max:255', // Required
                'sex' => 'nullable|in:Male,Female,Other', // Must match enum values
                'birthday' => 'nullable|date', // Valid date
                'birthplace' => 'nullable|string|max:255',
                'hometown' => 'nullable|string|max:255',
                'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed', // Must match enum values
                'nation' => 'nullable|string|max:255',
                'religion' => 'nullable|string|max:255',
                'id_document_type' => 'nullable|string|max:255',
                'id_creation_date' => 'nullable|date', // Valid date
                'place_of_issue' => 'nullable|string|max:255',
                'resident_of' => 'nullable|string|max:255',
                'current_address' => 'nullable|string',
                'literacy' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'job_position' => 'nullable|string|max:255',
                'workplace' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
                'account_name' => 'nullable|string|max:255',
                'bank_of_issue' => 'nullable|string|max:255',
                'personal_tax_code' => 'nullable|string|max:255',
                'hourly_rate' => 'nullable|numeric|min:0', // Must be numeric and not negative
                'phone' => 'nullable|string|max:255',
                'facebook' => 'nullable|string', // Must be a valid URL
                'linkedin' => 'nullable|string', // Must be a valid URL
                'skype' => 'nullable|string|max:255',
                // 'education' => 'nullable|string|max:255',
                'previous_job' => 'nullable|string|max:255',
                'publication_job' => 'nullable|string|max:255',
                // 'award' => 'nullable|string|max:255',
                'email' => 'required|email|unique:staff,email', // Required, unique, and valid email format
                'default_language' => 'nullable|string|max:255',
                'direction' => 'required|in:LTR,RTL', // Required, must match enum values
                'email_signature' => 'nullable|string',
                'other_information' => 'nullable|string',
                'is_administrator' => 'boolean', // Must be true or false
                'send_welcome_email' => 'boolean', // Must be true or false
                'password' => 'required|string|min:8', // Minimum 8 characters and confirmation
                'documents' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            ]);

            // Create a new Staff instance and save data
            $staff = new Staff($validatedData);

            // Hash the password before saving
            $staff->password = bcrypt($validatedData['password']);
            $staff->show_password = $request->password;

            if ($request->file('profile_image')) {
                $file = $request->file('profile_image');
                @unlink(public_path('upload/staff/'.$staff->profile_image));
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('upload/staff'),$filename);
                $staff['profile_image'] = $filename;
            }


            if ($request->hasFile('documents')) {
                $file = $request->file('documents');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('upload/certificates', $fileName, 'public');
        
                // Save document info to the database
                $staff->documents = $filePath;

            }

            $staff->is_administrator = $request->has('is_administrator') ? 1 : 0;
            $staff->send_welcome_email = $request->has('send_welcome_email') ? 1 : 0;

            // Create associated User
            $user = new User();
            $user->name = $staff->full_name;
            $user->email = $staff->email;
            $user->password = $staff->password; // Already hashed
            $user->show_password = $staff->show_password;
            $profileImagePath = $staff->profile_image;
            $fullProfileImageUrl = config('app.url') . '/upload/staff/' . ltrim($profileImagePath, '/');
            $user->profile_image = $profileImagePath;
            $user->profile_image_url = $fullProfileImageUrl;
            $user->save();
   

            // Save the staff record
            $staff->user_id = $user->id;
            $staff->save();

    
            $request->validate([
                'certificates.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            if ($request->hasFile('certificates')) {
                foreach ($request->file('certificates') as $certificate) {
                    $path = $certificate->store('upload/certificates', 'public'); // Store in public/certificates
                    // Save to database if required
                    StaffDocument::create([
                        'staff_id' => $staff->id,
                        'certificates' => $path,
                    ]);
                }
            }

            // EDUCATION MODULE
            $institutionNames = $request->input('institution_name');
            $subjectNames = $request->input('subject_name');
            $startingYears = $request->input('starting_year');
            $passingYears = $request->input('passing_year');
            $descriptions = $request->input('description');
            
            // EDUCATION MODULE
            if (!empty($institutionNames)) {
                foreach ($institutionNames as $index => $institutionName) {
                    Education::create([
                        'staff_id' => $staff->id,
                        'institution_name' => $institutionName,
                        'subject_name' => $subjectNames[$index] ?? null,
                        'starting_year' => $startingYears[$index] ?? null,
                        'passing_year' => $passingYears[$index] ?? null,
                        'description' => $descriptions[$index] ?? null,
                    ]);
                }
            }


            // CERTIFICATION MODULE
            $certificationNames = $request->input('certification');
            $certificationTitles = $request->input('certification_name');
            $certificationYears = $request->input('year');
            $certificationDescriptions = $request->input('certification_description');

            if (!empty($certificationNames)) {
                foreach ($certificationNames as $index => $certificationName) {
                    Certification::create([
                        'staff_id' => $staff->id,  
                        'certification' => $certificationName,
                        'certification_name' => $certificationTitles[$index] ?? null,
                        'year' => $certificationYears[$index] ?? null,
                        'description' => $certificationDescriptions[$index] ?? null,
                    ]);
                }
            }
            // AWARD MODULE
            $awardNames = $request->input('award_name');
            $awardYears = $request->input('award_year');
            $awardDescriptions = $request->input('award_description');

            if (!empty($awardNames)) {
                foreach ($awardNames as $index => $awardName) {
                    Award::create([
                        'staff_id' => $staff->id,
                        'award_name' => $awardName,
                        'year' => $awardYears[$index] ?? null,
                        'description' => $awardDescriptions[$index] ?? null,
                    ]);
                }
            }

            // EMPLOYMENT HISTORY MODULE
            $companyNames = $request->input('company_name');
            $positions = $request->input('position');
            $employmentStartYears = $request->input('starting_year');
            $employmentEndYears = $request->input('ending_year');
            $employmentDescriptions = $request->input('employment_description');

            if (!empty($companyNames)) {
                foreach ($companyNames as $index => $companyName) {
                    EmploymentHistory::create([
                        'staff_id' => $staff->id,
                        'company_name' => $companyName,
                        'position' => $positions[$index] ?? null,
                        'starting_year' => $employmentStartYears[$index] ?? null,
                        'ending_year' => $employmentEndYears[$index] ?? null,
                        'description' => $employmentDescriptions[$index] ?? null,
                    ]);
                }
            }

            // Redirect with a success message
            return redirect()->route('hrm.staff.list')->with('success', 'Staff created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            Log::error('Validation error: ' . $e->getMessage()); 
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Error saving staff: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Staff::with(['educations', 'certifications', 'awards', 'employmentHistories'])->findOrFail($id);
        $pageTitle = 'Staff View';

        return view('Hrm.staff.show',compact('staff','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = Staff::findOrFail($id);
        // dd($staff);
        $pageTitle = 'Staff Edit';

        $education = $staff->educations()->first(); 
        $certification = $staff->certifications()->first();
        $award = $staff->awards()->first();
        $employment = $staff->employmentHistories()->first();

        return view('Hrm.staff.edit',compact('staff','pageTitle','education','certification','award','employment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        // dd($request->all());
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'hr_code' => 'required|string|unique:staff,hr_code,' . $id,
                'full_name' => 'required|string|max:255',
                'sex' => 'nullable|in:Male,Female,Other',
                'birthday' => 'nullable|date',
                'birthplace' => 'nullable|string|max:255',
                'hometown' => 'nullable|string|max:255',
                'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
                'nation' => 'nullable|string|max:255',
                'religion' => 'nullable|string|max:255',
                'id_document_type' => 'nullable|string|max:255',
                'id_creation_date' => 'nullable|date',
                'place_of_issue' => 'nullable|string|max:255',
                'resident_of' => 'nullable|string|max:255',
                'current_address' => 'nullable|string',
                'literacy' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'job_position' => 'nullable|string|max:255',
                'workplace' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
                'account_name' => 'nullable|string|max:255',
                'bank_of_issue' => 'nullable|string|max:255',
                'personal_tax_code' => 'nullable|string|max:255',
                'hourly_rate' => 'nullable|numeric|min:0',
                'phone' => 'nullable|string|max:255',
                'facebook' => 'nullable|string',
                'linkedin' => 'nullable|string',
                'skype' => 'nullable|string|max:255',
                // 'education' => 'nullable|string|max:255',
                'previous_job' => 'nullable|string|max:255',
                'publication_job' => 'nullable|string|max:255',
                // 'award' => 'nullable|string|max:255',
                'email' => 'required|email|unique:staff,email,' . $id,
                'default_language' => 'nullable|string|max:255',
                'direction' => 'required|in:LTR,RTL',
                'email_signature' => 'nullable|string',
                'other_information' => 'nullable|string',
                'is_administrator' => 'boolean',
                'send_welcome_email' => 'boolean',
            ]);

            // Find the staff record
            $staff = Staff::findOrFail($id);

            // Update the staff record
            $staff->fill($validatedData);

            // Update password if provided
            // if (!empty($validatedData['password'])) {
            //     $staff->password = bcrypt($validatedData['password']);
            //     $staff->show_password = $validatedData['password'];
            // }

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                @unlink(public_path('upload/staff/' . $staff->profile_image)); // Delete old image
                $file = $request->file('profile_image');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/staff'), $filename);
                $staff->profile_image = $filename;
            }

            $staff->is_administrator = $request->has('is_administrator') ? 1 : 0;
            $staff->send_welcome_email = $request->has('send_welcome_email') ? 1 : 0;

            $staff->save();

            // EDUCATION MODULE
            $educationIds = $request->input('education_ids'); // Array of IDs for existing records
            // dd($educationIds);
            $institutionNames = $request->input('institution_name');
            $subjectNames = $request->input('subject_name');
            $startingYears = $request->input('starting_year');
            $passingYears = $request->input('passing_year');
            $descriptions = $request->input('description');

            if (!empty($institutionNames)) {
                foreach ($institutionNames as $index => $institutionName) {
                    if (!empty($institutionName)) {
                        $attributes = [
                            'staff_id' => $staff->id,
                            'institution_name' => $institutionName,
                            'subject_name' => $subjectNames[$index] ?? null,
                            'starting_year' => $startingYears[$index] ?? null,
                            'passing_year' => $passingYears[$index] ?? null,
                            'description' => $descriptions[$index] ?? null,
                        ];

                        if (!empty($educationIds[$index])) {
                            $record = Education::find($educationIds[$index]);
                            if ($record) {
                                $record->update($attributes);
                            }
                        } else {
                            Education::create($attributes);
                        }
                    }
                }
            }

            // CERTIFICATION MODULE
            $certificationIds = $request->input('certification_ids');
            $certificationNames = $request->input('certification');
            $certificationTitles = $request->input('certification_name');
            $certificationYears = $request->input('year');
            $certificationDescriptions = $request->input('certification_description');

            if (!empty($certificationNames)) {
                foreach ($certificationNames as $index => $certificationName) {
                    if (!empty($certificationName)) {
                        $attributes = [
                            'staff_id' => $staff->id,
                            'certification' => $certificationName,
                            'certification_name' => $certificationTitles[$index] ?? null,
                            'year' => $certificationYears[$index] ?? null,
                            'description' => $certificationDescriptions[$index] ?? null,
                        ];

                        if (!empty($certificationIds[$index])) {
                            $record = Certification::find($certificationIds[$index]);
                            if ($record) {
                                $record->update($attributes);
                            }
                        } else {
                            Certification::create($attributes);
                        }
                    }
                }
            }

            // AWARD MODULE
            $awardIds = $request->input('award_ids');
            $awardNames = $request->input('award_name');
            $awardYears = $request->input('award_year');
            $awardDescriptions = $request->input('award_description');

            if (!empty($awardNames)) {
                foreach ($awardNames as $index => $awardName) {
                    if (!empty($awardName)) {
                        $attributes = [
                            'staff_id' => $staff->id,
                            'award_name' => $awardName,
                            'year' => $awardYears[$index] ?? null,
                            'description' => $awardDescriptions[$index] ?? null,
                        ];

                        if (!empty($awardIds[$index])) {
                            $record = Award::find($awardIds[$index]);
                            if ($record) {
                                $record->update($attributes);
                            }
                        } else {
                            Award::create($attributes);
                        }
                    }
                }
            }

            // EMPLOYMENT HISTORY MODULE
            $employmentHistoryIds = $request->input('employment_ids');
            $companyNames = $request->input('company_name');
            $positions = $request->input('position');
            $employmentStartYears = $request->input('starting_year');
            $employmentEndYears = $request->input('ending_year');
            $employmentDescriptions = $request->input('employment_description');

            if (!empty($companyNames)) {
                foreach ($companyNames as $index => $companyName) {
                    if (!empty($companyName)) {
                        $attributes = [
                            'staff_id' => $staff->id,
                            'company_name' => $companyName,
                            'position' => $positions[$index] ?? null,
                            'starting_year' => $employmentStartYears[$index] ?? null,
                            'ending_year' => $employmentEndYears[$index] ?? null,
                            'description' => $employmentDescriptions[$index] ?? null,
                        ];

                        if (!empty($employmentHistoryIds[$index])) {
                            $record = EmploymentHistory::find($employmentHistoryIds[$index]);
                            if ($record) {
                                $record->update($attributes);
                            }
                        } else {
                            EmploymentHistory::create($attributes);
                        }
                    }
                }
            }

            // Check if a User exists with the same email as the Staff
            $user = User::findOrFail($staff->user_id);
            $user->name = $staff->full_name;
            $user->email = $staff->email;
            $user->password = $staff->password;
            $profileImagePath = $staff->profile_image;
            $fullProfileImageUrl = config('app.url') . '/upload/staff/' . ltrim($profileImagePath, '/');
            $user->profile_image = $profileImagePath;
            $user->profile_image_url = $fullProfileImageUrl;
            $user->save();

            // Redirect with a success message
            return redirect()->route('hrm.staff.list')->with('success', 'Staff updated successfully.');
            // return redirect()->back()->with('success', 'Staff updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating staff: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){

        $staff = Staff::find($id);

        if (!$staff) {
            return redirect()->route('hrm.staff.list')->with('error', 'Staff not found.');
        }

        try {
            // Delete the staff profile image
            if ($staff->profile_image && file_exists(public_path($staff->profile_image))) {
                unlink(public_path($staff->profile_image));
            }
        } catch (Exception $e) {
            // Log the exception if needed
        }

        try {
            // Delete staff-level documents if the `documents` field exists as a single file path
            if ($staff->documents && Storage::exists($staff->documents)) {
                Storage::delete($staff->documents);
            }
        } catch (Exception $e) {
            // Log the exception if needed
        }

        try {
            // Delete associated certificates and related document records
            if ($staff->documents()->exists()) {
                foreach ($staff->documents as $document) {
                    if (Storage::exists($document->certificates)) {
                        Storage::delete($document->certificates);
                    }

                    // Delete the document record from the database
                    $document->delete();
                }
            }
        } catch (Exception $e) {
            // Log the exception if needed
        }

        // Finally, delete the staff record
        $staff->delete();

        return redirect()->route('hrm.staff.list')->with('success', 'Staff and associated files deleted successfully.');
    }
}
