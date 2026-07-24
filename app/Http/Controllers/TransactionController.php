<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TransactionController extends Controller
{
    public function receive(): View
    {
        return view('transactions.receive');
    }

    public function issue(): View
    {
        return view('transactions.issue');
    }

    public function returnItem(): View
    {
        return view('transactions.return');
    }

    public function broken(): View
    {
        return view('transactions.broken');
    }

    public function writeOff(): View
    {
        return view('transactions.write-off');
    }

    public function disposal(): View
    {
        return view('transactions.disposal');
    }

    public function changeDescription(): View
    {
        return view('transactions.change-description');
    }
}
