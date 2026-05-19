<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (Auth::guest()) {
            return redirect()->route('login');
        }

        foreach ($permissions as $permission) {
            if (!Auth::user()->can($permission)) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthorized.'], 403);
                }
                return redirect()->route('dashboard')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            }
        }

        return $next($request);
    }
}
