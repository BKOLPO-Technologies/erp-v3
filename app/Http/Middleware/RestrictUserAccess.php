<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RestrictUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            $role = $user->role;
            $email = $user->email;
            $currentPath = $request->path();

            // ✅ 1. Allow Super Admin everything
            if (
                $email === 'superadmin@bkolpo.com' ||
                session()->has('impersonate_original_id') && Auth::id() == session('impersonate_original_id')
            ) {
                return $next($request);
            }

            // ❌ 2. Block all users (except superadmin) from accessing 'admin/*' routes
            if (str_starts_with($currentPath, 'admin')) {
                return redirect()->route($role . '.dashboard')
                    ->with('error', 'Access denied to Super Admin panel.');
            }

            // ✅ 3. Allow only users to access their role's route prefix
            if ($role) {
                $allowedPaths = [
                    $role,
                    $role . '/*',
                    $role . '/dashboard',
                    '/',
                ];

                $isAllowed = false;
                foreach ($allowedPaths as $pattern) {
                    if ($request->is($pattern)) {
                        $isAllowed = true;
                        break;
                    }
                }

                if (!$isAllowed) {
                    return redirect()->route($role . '.dashboard')
                        ->with('error', 'Access denied to this section.');
                }
            }
        }

        return $next($request);
    }


}
