<?php

namespace App\Http\Middleware;

use App\Models\Warehouse;
use Closure;
use Illuminate\Http\Request;

class CheckWarehouseAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ! $user->hasRole('Super Admin')) {
            $routeWarehouseId = $request->route('warehouse')
                ?? $request->input('lokasi_gudang_id')
                ?? $request->input('warehouse_id');

            if ($routeWarehouseId) {
                $userBranchIds = $user->branches->pluck('branch_id')->toArray();
                $warehouse = Warehouse::find($routeWarehouseId);
                if ($warehouse && ! in_array((int) $warehouse->branch_id, $userBranchIds)) {
                    return redirect()->route('dashboard')
                        ->with('error', 'Anda tidak memiliki akses ke gudang tersebut.');
                }
            }
        }

        return $next($request);
    }
}
