<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckWarehouseAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->hasRole('Super Admin')) {
            $routeWarehouseId = $request->route('warehouse')
                ?? $request->input('lokasi_gudang_id')
                ?? $request->input('warehouse_id');

            if ($routeWarehouseId) {
                $userWarehouseIds = $user->warehouses->pluck('id')->toArray();
                if (!in_array((int) $routeWarehouseId, $userWarehouseIds)) {
                    return redirect()->route('dashboard')
                        ->with('error', 'Anda tidak memiliki akses ke gudang tersebut.');
                }
            }
        }

        return $next($request);
    }
}
