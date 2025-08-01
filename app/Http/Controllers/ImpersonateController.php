<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ImpersonateController extends Controller
{
    public function loginAs($id)
    {
        if (!Auth::user()->hasRole('superadmin')) { 
            abort(403);
        }

        // Store the original super admin's ID
        session(['impersonate_original_id' => Auth::id()]);

        // Log in as the selected user
        Auth::loginUsingId($id);

        // Get impersonated user
        $user = auth()->user();

        // Redirect based on user role
        switch ($user->role) {
            case 'superadmin':
                $redirectRoute = route('dashboard');
                break;
            case 'accounts':
                $redirectRoute = route('accounts.dashboard');
                break;
            case 'inventory':
                $redirectRoute = route('inventory.dashboard');
                break;
            case 'hr':
                $redirectRoute = url('/hr/dashboard');
                break;
            case 'ecommerce':
                $redirectRoute = route('ecommerce.dashboard');
                break;
            case 'payroll':
                $redirectRoute = route('payroll.dashboard');
                break;
            case 'process':
                $redirectRoute = route('process.dashboard');
                break;
            default:
                $redirectRoute = route('login');
                break;
        }

        return redirect($redirectRoute)->with('success', 'You are now logged in as ' . $user->email);
    }

    public function leave()
    {
        if (session()->has('impersonate_original_id')) {
            $originalId = session()->pull('impersonate_original_id');
            Auth::loginUsingId($originalId); // Log back in as Super Admin
        }

        // Ensure session is clean
        session()->forget('impersonate_original_id');

        return redirect()->route('users.index')->with('success', 'Returned to Super Admin account.');
    }
}
