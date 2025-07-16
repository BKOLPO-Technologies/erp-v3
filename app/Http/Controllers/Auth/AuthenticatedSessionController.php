<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Attempt login
            $request->authenticate();

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Get authenticated user
            $user = auth()->user();

            // Redirect based on user module or role
            switch ($user->email) {
                case 'superadmin@bkolpo.com':
                    $redirectRoute = route('accounts.dashboard'); // main superadmin dashboard
                    break;
                case 'accounts@bkolpo.com':
                    $redirectRoute = route('accounts.dashboard');
                    break;
                case 'inventory@bkolpo.com':
                    $redirectRoute = route('inventory.dashboard');
                    break;
                case 'hr@bkolpo.com':
                    $redirectRoute = url('/hrm/dashboard');
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
                    $redirectRoute = route('dashboard'); // fallback
                    break;
            }

            return redirect()->intended($redirectRoute)
                ->with('success', 'Login Successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->with(['error' => 'These credentials do not match our records.']);
        }
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Admin Logout Successfully');;
    }
}
