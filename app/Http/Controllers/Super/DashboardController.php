<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\ScanRecord;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SCAN DATE
        |--------------------------------------------------------------------------
        */

        $scanDate = $request->input(
            'scan_date',
            now()->toDateString()
        );


        /*
        |--------------------------------------------------------------------------
        | RECENT SCANS
        |--------------------------------------------------------------------------
        */

        $recentScans = ScanRecord::with([
            'user',
            'outlet',
        ])
            ->latest('scanned_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTAL SCAN BY OUTLET
        |--------------------------------------------------------------------------
        */

        $scanByOutlet = ScanRecord::query()
            ->select(
                'outlet_id',
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('scanned_at', $scanDate)
            ->with('outlet')
            ->groupBy('outlet_id')
            ->get()
            ->sortByDesc('total')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | DATA CHART
        |--------------------------------------------------------------------------
        */

        $outletLabels = $scanByOutlet
            ->map(function ($item) {
                return $item->outlet?->code
                    ?? $item->outlet?->outlet_code
                    ?? $item->outlet?->outlet_name
                    ?? '-';
            })
            ->values();

        $outletTotals = $scanByOutlet
            ->pluck('total')
            ->values();

        $totalToday = ScanRecord::query()
            ->whereDate('scanned_at', today())
            ->count();

        $totalOutlets = Outlet::count();

        $totalUsers = User::count();


        return view('admin.dashboard', compact(
            'recentScans',
            'outletLabels',
            'outletTotals',
            'totalToday',
            'totalOutlets',
            'totalUsers',
            'scanDate',
        ));
    }
}
