<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Domain\Monitoring\Queries\GetServerOptions;

class MonitoringPageController extends Controller
{
    /**
     * Tampilkan halaman monitoring (dropdown pilih server + tampilan Tree/Table)
     */
    public function __invoke(GetServerOptions $opt)
    {
        // Ambil daftar server dari domain query
        $servers = $opt->handle(); // hasil: [{id,label}, ...]

        // kirim ke Blade super/monitoring/index.blade.php
        return view('super.monitoring.index', compact('servers'));
    }
}
