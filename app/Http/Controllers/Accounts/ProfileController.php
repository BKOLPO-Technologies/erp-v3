<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Hash;

class ProfileController extends Controller
{
     /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // dd($request->all());
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        $pageTitle = 'Profile Edit';

        return view('Accounts.profile.edit', [
            'user' => $user,
            'pageTitle' => $pageTitle,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;

        if ($request->hasFile('profile_image')) {
            // Optionally delete old image
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }

            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'upload/profile_images/' . $imageName;

            $image->move(public_path('upload/profile_images'), $imageName);

            $user->profile_image = $imagePath; // Save relative path
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function changePasswordForm()
    {
        $pageTitle = 'Change Password';
        return view('Accounts.profile.password', compact('pageTitle'));
    }

    public function updatePassword(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match']);
        }

        $user->password = Hash::make($request->new_password);
        $user->show_password = $request->new_password;
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

}
