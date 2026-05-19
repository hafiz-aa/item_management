<?php

namespace App\Http\Controllers;

use App\Repositories\ActivityLogRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    private ActivityLogRepository $logRepo;

    public function __construct(ActivityLogRepository $logRepo)
    {
        $this->logRepo = $logRepo;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['type', 'user_id', 'date_from', 'date_to', 'search']);
        $logs = $this->logRepo->search($filters);
        return view('activity-logs.index', compact('logs', 'filters'));
    }
}
