<?php

namespace App\Http\Controllers;

use App\Models\AptPeriod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function transactionPeriod(Request $request): View
    {
        $query = AptPeriod::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('aptp_month_2', 'like', "%{$search}%")
                    ->orWhere('aptp_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('aptp_is_active_period', $request->status);
        }

        if ($request->filled('closing')) {
            $query->where('aptp_is_closed', $request->closing);
        }

        $periods = $query->orderBy('aptp_year', 'desc')
            ->orderBy('aptp_month_1', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status', 'closing']);

        return view('settings.transaction-period', compact('periods', 'filters'));
    }
}
