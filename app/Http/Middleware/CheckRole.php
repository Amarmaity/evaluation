<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;



class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {
        // Log entire session data for debugging
        Log::info('Session Data:', Session::all());

        // Retrieve user type from session
        $user = Session::get('user_type');
        Log::info('Retrieved User Type: ' . ($user ?? 'null')); // Check if user type is correctly retrieved

        // If user type is missing or invalid, log them out
        if (!$user || !in_array($user, ['admin', 'hr', 'manager', 'users', 'client', 'Super User'])) {
            Log::warning("User logged out due to invalid user type: " . ($user ?? 'null'));
            Auth::logout();
            Session::invalidate();
            return redirect()->route('all-user-login')->with('error', 'Unauthorized access. You have been logged out.');
        }

        // Define allowed routes for each role
        $roleRoutes = [
            'admin' => ['admin-dashboard', 'admin-review', 'input-evaluation', 'admin-review-section', 'admin-review-list', 'user-admin-details'],
            'hr' => ['hr-dashboard', 'hr-review', 'input-evaluation', 'hr-review-list', 'user-hr-details', 'user-report-view-evaluation'],
            'manager' => ['manager-dashboard', 'manager-review', 'input-evaluation', 'manager-review-list', 'user-manager-details'],
            'users' => ['users-dashboard', 'input-evaluation', 'log-out-users'],
            'client' => ['client-dashboard', 'client-review', 'client-dashboard'],
            'Super User' => ['super-user-dashboard', 'super-user-review', 'add-user', 'userlist', 'super.search',
            'appraisal-view', 'financial.view', 'logout-users', 'user-search', 'super-admin-search', 'super-user-search-bar', 'active-user', 'financial-view', 'super-admin-view',
            'financial-view-tables', 'employee.details', 'evaluation.details', 'hr.review.details','manager.review.details','admin.review.details','setting-view','create-client', 'client-list']
        ];

        // Get the current route name
        $currentRoute = $request->route()->getName();
        Log::info('Current Route: ' . ($currentRoute ?? 'null')); // Log current route

        // If user role doesn't match allowed routes, log them out
        if (!isset($roleRoutes[$user]) || !in_array($currentRoute, $roleRoutes[$user])) {
            Log::warning("User logged out due to unauthorized route: " . $currentRoute . " for user type: " . $user);
            Auth::logout();
            Session::invalidate();
            return redirect()->route('all-user-login')->with('error', 'Unauthorized access. You have been logged out.');
        }
        //  dd($request);
        return $next($request);
    }
}
