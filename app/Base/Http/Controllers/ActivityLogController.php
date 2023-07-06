<?php

namespace App\Base\Http\Controllers;

use App\Activity\Activity;
use App\Base\Models\Notification;
use Illuminate\Http\Request;
use App\Base\Repositories\ActivityLogRepository;

class ActivityLogController extends Controller
{
    public function index(Request $request, ActivityLogRepository $repository)
    {
        $this->authorize('index', Notification::class);
        $activityLog = $repository->getActivityLog($request);

        return response()->json([
            'status'        => 'success',
            'activityLog' => $activityLog
        ]);
    }
}
