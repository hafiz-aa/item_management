<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BranchFilterController extends Controller
{
    public function set(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
        ]);

        session(['branch_filter' => $request->branch_id]);

        return redirect()->back()->with('success', 'Branch filter berhasil diterapkan.');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('branch_filter');

        return redirect()->back()->with('success', 'Branch filter berhasil dihapus.');
    }
}
