<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;

use App\Models\ScanRecord;

use App\Models\Outlet;

use App\Models\User;

use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
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
    | Hari ini
    */

        $scanByOutlet = ScanRecord::query()
            ->select(
                'outlet_id',
                DB::raw('COUNT(*) as total')
            )
            ->whereDate(
                'scanned_at',
                today()
            )
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


        $totalToday = $outletTotals->sum();


        $totalOutlets = Outlet::count();

        $totalUsers = User::count();


        return view('admin.dashboard', compact(
            'recentScans',
            'outletLabels',
            'outletTotals',
            'totalToday',
            'totalOutlets',
            'totalUsers',

        ));
    }
}
