<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ Check if user is authenticated first
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // ✅ Check if authenticated user is a customer
        $user = Auth::user();
        if ($user->usertype !== 'customer') {
            // Redirect admin users to their dashboard
            if ($user->usertype === 'admin') {
                return redirect()->route('admin.dashboard')->with('info', 'Access denied. Admin users cannot access customer areas.');
            }
            
            // For other user types, redirect to login
            return redirect()->route('login')->with('error', 'Unauthorized access. Customers only.');
        }

        // ✅ User is authenticated AND is a customer
        return $next($request);
    }
}