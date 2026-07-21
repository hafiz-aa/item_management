<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SettingController extends Controller
{
    public function transactionPeriod(): View
    {
        return view('settings.transaction-period');
    }
}
