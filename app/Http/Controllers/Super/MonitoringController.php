<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Domain\Monitoring\Actions\BuildRouterStatuses;
use Illuminate\Http\Request;
use Throwable;

class MonitoringController extends Controller
{
    public function index(Request $r, BuildRouterStatuses $action, int $serverId)
    {
        try {
            return response()->json($action->handle($serverId));
        } catch (Throwable $e) {
            report($e);
            return response()->json(['error' => config('app.debug') ? $e->getMessage() : 'Internal error'], 500);
        }
    }
}
