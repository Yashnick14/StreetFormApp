<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->usertype === 'admin') {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}
