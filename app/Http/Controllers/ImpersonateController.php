<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ImpersonateController extends Controller
{
    public function loginAs($id)
    {
        if (!Auth::user()->hasRole('Super Admin')) {
            abort(403);
        }

        // Store the original super admin's ID
        session(['impersonate_original_id' => Auth::id()]);

        // Log in as the selected user
        Auth::loginUsingId($id);

        // Get impersonated user
        $user = auth()->user();

        // Dynamic redirect based on user email
        switch ($user->email) {
            case 'superadmin@bkolpo.com':
                $redirectRoute = route('dashboard');
                break;
            case 'accounts@bkolpo.com':
                $redirectRoute = route('accounts.dashboard');
                break;
            case 'inventory@bkolpo.com':
                $redirectRoute = route('inventory.dashboard');
                break;
            case 'hr@bkolpo.com':
                $redirectRoute = url('/hr/dashboard');
                break;
            case 'ecommerce@bkolpo.com':
                $redirectRoute = route('ecommerce.dashboard');
                break;
            case 'payroll@bkolpo.com':
                $redirectRoute = route('payroll.dashboard');
                break;
            case 'process@bkolpo.com':
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
