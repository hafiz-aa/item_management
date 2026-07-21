<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SettingController extends Controller
{
    public function vendor(): View
    {
        return view('settings.vendor');
    }

    public function transactionPeriod(): View
    {
        return view('settings.transaction-period');
    }
}
