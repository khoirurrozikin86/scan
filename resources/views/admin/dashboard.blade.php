@extends('layouts.admin')
@section('title', 'Dashboard')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Overview</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
@endsection

@section('content')
    {{-- Hero Welcome --}}

    {{-- ========================================================= --}}
    {{-- HERO DASHBOARD --}}
    {{-- ========================================================= --}}

    <div class="dashboard-hero mb-4">

        {{-- Decorative background --}}
        <div class="hero-circle hero-circle-1"></div>
        <div class="hero-circle hero-circle-2"></div>
        <div class="hero-wave"></div>

        <div class="row align-items-center position-relative">

            {{-- LEFT --}}
            <div class="col-lg-7">

                <div class="hero-content">

                    <div class="hero-badge mb-3">
                        <span>Welcome back</span>

                        {{-- <i data-feather="home"></i> --}}
                    </div>


                    <h1 class="hero-title">
                        Halo,
                        <span>
                            {{ auth()->user()->name ?? 'Developer' }}
                        </span>
                        👋
                    </h1>


                    <p class="hero-description">
                        Selamat datang di panel admin.
                        Kelola scan tiket, monitor aktivitas,
                        dan lihat laporan dengan mudah.
                    </p>


                    {{-- MINI STAT --}}
                    <div class="row g-3 mt-4">

                        {{-- UPTIME --}}
                        <div class="col-sm-4">

                            <div class="hero-stat">

                                <div class="hero-stat-icon green">
                                    <i data-feather="map-pin"></i>
                                </div>

                                <div>

                                    <div class="hero-stat-label">
                                        Total Outlet
                                    </div>

                                    <div class="hero-stat-value">
                                        {{ $totalOutlets ?? 0 }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- SCAN --}}
                        <div class="col-sm-4">

                            <div class="hero-stat">

                                <div class="hero-stat-icon emerald">
                                    <i data-feather="activity"></i>
                                </div>

                                <div>

                                    <div class="hero-stat-label">
                                        Scan Hari Ini
                                    </div>

                                    <div class="hero-stat-value">
                                        {{ number_format($totalToday ?? 0) }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- USER --}}
                        <div class="col-sm-4">

                            <div class="hero-stat">

                                <div class="hero-stat-icon teal">
                                    <i data-feather="users"></i>
                                </div>

                                <div>

                                    <div class="hero-stat-label">
                                        User
                                    </div>

                                    <div class="hero-stat-value">
                                        {{ $totalUsers ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RIGHT / OSIL --}}
            <div class="col-lg-5">

                <div class="osil-wrapper">

                    <div class="osil-glow"></div>

                    <img src="{{ asset('images/osil.png') }}" alt="OSIL" class="osil-image">

                    <div class="osil-message">

                        <div class="osil-message-small">
                            Bersama OSIL
                        </div>

                        <div class="osil-message-title">
                            Semua Lebih
                            <strong>Mudah!</strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>




    {{-- ========================================================= --}}
    {{-- QUICK ACCESS --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">


        {{-- ===================================================== --}}
        {{-- CAMERA --}}
        {{-- ===================================================== --}}

        @can('scan-records.create')
            <div class="col-xl-4 col-md-6">

                <a href="{{ route('super.scan-records.camera') }}" class="quick-card quick-card-green">

                    <div class="quick-icon">

                        <i data-feather="camera"></i>

                    </div>


                    <div class="quick-content">

                        <h5>
                            Scan Camera
                        </h5>

                        <p>
                            Scan QR Code menggunakan kamera
                            dengan cepat dan mudah.
                        </p>

                        <span class="quick-action">
                            Mulai Scan
                        </span>

                    </div>


                    <div class="quick-arrow">

                        <i data-feather="arrow-right"></i>

                    </div>

                </a>

            </div>
        @endcan


        {{-- ===================================================== --}}
        {{-- BARCODE --}}
        {{-- ===================================================== --}}

        @can('scan-records.create')
            <div class="col-xl-4 col-md-6">

                <a href="{{ route('super.scan-records.scanner') }}" class="quick-card quick-card-blue">

                    <div class="quick-icon">

                        <i data-feather="maximize"></i>

                    </div>


                    <div class="quick-content">

                        <h5>
                            Barcode Scanner
                        </h5>

                        <p>
                            Scan barcode tiket menggunakan
                            scanner perangkat.
                        </p>

                        <span class="quick-action">
                            Mulai Scan
                        </span>

                    </div>


                    <div class="quick-arrow">

                        <i data-feather="arrow-right"></i>

                    </div>

                </a>

            </div>
        @endcan


        {{-- ===================================================== --}}
        {{-- REPORT --}}
        {{-- ===================================================== --}}

        @can('scan-records.view')
            <div class="col-xl-4 col-md-6">

                <a href="{{ route('super.scan-records.index') }}" class="quick-card quick-card-gold">

                    <div class="quick-icon">

                        <i data-feather="file-text"></i>

                    </div>


                    <div class="quick-content">

                        <h5>
                            Report
                        </h5>

                        <p>
                            Lihat laporan dan statistik
                            scan secara lengkap.
                        </p>

                        <span class="quick-action">
                            Lihat Laporan
                        </span>

                    </div>


                    <div class="quick-arrow">

                        <i data-feather="arrow-right"></i>

                    </div>

                </a>

            </div>
        @endcan

    </div>






    {{-- ========================================================= --}}
    {{-- TOTAL SCAN BY OUTLET --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm scan-chart-card mb-4">

        <div class="card-body p-4">

            {{-- HEADER --}}

            <div class="d-flex justify-content-between align-items-start mb-3">

                <div>

                    <h5 class="fw-bold mb-1">
                        Total Scan by Outlet
                    </h5>

                    <div class="text-muted small">

                        Today's Scan:

                        <span class="badge today-count">
                            {{ number_format($totalToday) }}
                        </span>

                    </div>

                </div>

                <div class="small text-muted">

                    {{ now()->format('d-m-Y') }}

                </div>

            </div>


            {{-- CHART --}}

            <div style="height: 430px;">

                <canvas id="scanByOutletChart"></canvas>

            </div>

        </div>

    </div>





    {{-- ========================================================= --}}
    {{-- RECENT SCANS --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm recent-scan-card">

        <div class="card-body p-4">

            {{-- HEADER --}}
            <div class="d-flex align-items-center justify-content-between mb-4">

                <div>
                    <h5 class="fw-bold mb-1">
                        Recent Scans
                    </h5>

                    <p class="text-muted small mb-0">
                        10 transaksi scan terakhir
                    </p>
                </div>

                <a href="{{ route('super.scan-records.index') }}" class="btn btn-sm btn-outline-success">

                    See all

                </a>

            </div>


            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Outlet</th>

                            <th>Outlet Type</th>

                            <th>User</th>

                            <th>Barcode</th>

                            <th>Ticket Type</th>

                            <th>Method</th>

                            <th>Scan Time</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($recentScans ?? [] as $scan)
                            <tr>

                                {{-- OUTLET --}}
                                <td>

                                    <span class="fw-semibold">

                                        {{ $scan->outlet?->outlet_name ?? '-' }}

                                    </span>

                                </td>


                                {{-- OUTLET TYPE --}}
                                <td>

                                    <span class="badge badge-outlet-type">

                                        {{ $scan->outlet?->outlet_type ?? '-' }}

                                    </span>

                                </td>


                                {{-- USER --}}
                                <td>

                                    {{ $scan->user?->name ?? '-' }}

                                </td>


                                {{-- BARCODE --}}
                                <td>

                                    <span class="fw-semibold">

                                        {{ $scan->qrcode ?? '-' }}

                                    </span>

                                </td>


                                {{-- TICKET TYPE --}}
                                <td>

                                    {{ $scan->ticket_type ?? '-' }}

                                </td>


                                {{-- METHOD --}}
                                <td>

                                    @if ($scan->scan_method === 'scanner')
                                        <span class="badge bg-success-subtle text-success">

                                            Barcode

                                        </span>
                                    @elseif ($scan->scan_method === 'camera')
                                        <span class="badge bg-success-subtle text-success">

                                            Camera

                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted">

                                            {{ ucfirst($scan->scan_method ?? '-') }}

                                        </span>
                                    @endif

                                </td>


                                {{-- TIME --}}
                                <td>

                                    <span class="scan-time">

                                        {{ $scan->scanned_at ? $scan->scanned_at->format('d-m-Y H:i:s') : '-' }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center text-muted py-5">

                                    <i data-feather="inbox" style="width:32px;height:32px;" class="mb-2">
                                    </i>

                                    <div>
                                        Belum ada transaksi scan.
                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection

@push('styles')
    <style>
        /* Hero gradient + animation */
        /* =========================================================
                                                                                   DASHBOARD HERO
                                                                                   ========================================================= */

        .dashboard-hero {
            position: relative;
            overflow: hidden;
            min-height: 310px;
            border-radius: 22px;
            padding: 38px 42px;
            background:
                radial-gradient(circle at 85% 20%,
                    rgba(255, 255, 255, .55),
                    transparent 28%),
                linear-gradient(135deg,
                    #ecfdf5 0%,
                    #d1fae5 48%,
                    #bbf7d0 100%);
            border: 1px solid #d9f2e3;
        }


        /* Decorative circles */

        .hero-circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-circle-1 {
            width: 260px;
            height: 260px;
            right: -90px;
            top: -100px;
            background: rgba(34, 197, 94, .10);
        }

        .hero-circle-2 {
            width: 180px;
            height: 180px;
            right: 220px;
            bottom: -100px;
            background: rgba(16, 185, 129, .08);
        }


        /* =========================================================
                                                                                   HERO CONTENT
                                                                                   ========================================================= */

        .hero-content {
            position: relative;
            z-index: 5;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 13px;
            border-radius: 30px;
            background: rgba(255, 255, 255, .75);
            color: #15803d;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, .8);
            backdrop-filter: blur(10px);
        }

        .hero-badge svg {
            width: 15px;
            height: 15px;
        }

        .hero-title {
            margin: 0;
            font-size: clamp(28px, 3vw, 40px);
            font-weight: 800;
            color: #102a1b;
            letter-spacing: -1px;
        }

        .hero-title span {
            color: #15803d;
        }

        .hero-description {
            max-width: 600px;
            margin-top: 10px;
            margin-bottom: 0;
            color: #527064;
            font-size: 14px;
            line-height: 1.7;
        }


        /* =========================================================
                                                                                   HERO STAT
                                                                                   ========================================================= */

        .hero-stat {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 12px;
            min-height: 72px;
            background: rgba(255, 255, 255, .78);
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 13px;
            box-shadow: 0 8px 25px rgba(21, 128, 61, .06);
            backdrop-filter: blur(10px);
        }

        .hero-stat-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .hero-stat-icon svg {
            width: 20px;
            height: 20px;
        }

        .hero-stat-icon.green {
            background: #dcfce7;
            color: #16a34a;
        }

        .hero-stat-icon.emerald {
            background: #d1fae5;
            color: #059669;
        }

        .hero-stat-icon.teal {
            background: #ccfbf1;
            color: #0f766e;
        }

        .hero-stat-label {
            font-size: 10px;
            color: #718579;
            margin-bottom: 2px;
        }

        .hero-stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #173325;
        }


        /* =========================================================
                                                                                   OSIL
                                                                                   ========================================================= */

        .osil-wrapper {
            position: relative;
            min-height: 270px;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            z-index: 5;
        }

        .osil-glow {
            position: absolute;
            width: 230px;
            height: 230px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .65);
            filter: blur(5px);
            bottom: 5px;
        }

        .osil-image {
            position: relative;
            z-index: 2;
            width: 240px;
            max-width: 90%;
            object-fit: contain;
            animation: osilFloat 4s ease-in-out infinite;
            filter: drop-shadow(0 18px 15px rgba(0, 0, 0, .12));
        }

        @keyframes osilFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-9px) rotate(1deg);
            }

        }


        /* =========================================================
                                                                                   OSIL MESSAGE
                                                                                   ========================================================= */

        .osil-message {
            position: absolute;
            right: 0;
            top: 25px;
            z-index: 4;
            padding: 13px 18px;
            min-width: 145px;
            background: #15803d;
            color: white;
            border-radius: 15px 15px 15px 4px;
            box-shadow: 0 10px 25px rgba(21, 128, 61, .20);
            animation: messageFloat 4s ease-in-out infinite;
        }

        .osil-message::after {
            content: "";
            position: absolute;
            left: -10px;
            bottom: 0;
            border-style: solid;
            border-width: 10px 10px 0 0;
            border-color: #15803d transparent transparent transparent;
        }

        .osil-message-small {
            font-size: 10px;
            opacity: .8;
            margin-bottom: 2px;
        }

        .osil-message-title {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.25;
        }

        .osil-message-title strong {
            color: #fef08a;
        }

        @keyframes messageFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

        }


        /* =========================================================
                                                                                   QUICK ACCESS CARD
                                                                                   ========================================================= */

        .quick-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 18px;
            min-height: 150px;
            padding: 22px;
            overflow: hidden;
            text-decoration: none !important;
            border-radius: 17px;
            border: 1px solid #e8edf0;
            background: white;
            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
        }

        .quick-card::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            right: -55px;
            bottom: -80px;
            border-radius: 50%;
            opacity: .7;
        }

        .quick-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(0, 0, 0, .08);
        }

        .quick-icon {
            width: 64px;
            height: 64px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
        }

        .quick-icon svg {
            width: 31px;
            height: 31px;
            stroke-width: 2;
        }


        /* GREEN */

        .quick-card-green .quick-icon {
            background: #dcfce7;
            color: #15803d;
        }

        .quick-card-green::after {
            background: #ecfdf5;
        }

        .quick-card-green h5,
        .quick-card-green .quick-action {
            color: #15803d;
        }


        /* BLUE */

        .quick-card-blue .quick-icon {
            background: #dbeafe;
            color: #2563eb;
        }

        .quick-card-blue::after {
            background: #eff6ff;
        }

        .quick-card-blue h5,
        .quick-card-blue .quick-action {
            color: #2563eb;
        }


        /* GOLD */

        .quick-card-gold .quick-icon {
            background: #fef3c7;
            color: #ca8a04;
        }

        .quick-card-gold::after {
            background: #fffbeb;
        }

        .quick-card-gold h5,
        .quick-card-gold .quick-action {
            color: #b7791f;
        }


        /* =========================================================
                                                                                   QUICK CONTENT
                                                                                   ========================================================= */

        .quick-content {
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .quick-content h5 {
            margin: 0 0 6px;
            font-size: 16px;
            font-weight: 800;
        }

        .quick-content p {
            margin: 0 0 13px;
            color: #718096;
            font-size: 12px;
            line-height: 1.5;
            max-width: 240px;
        }

        .quick-action {
            font-size: 11px;
            font-weight: 700;
        }


        /* =========================================================
                                                                                   ARROW
                                                                                   ========================================================= */

        .quick-arrow {
            position: relative;
            z-index: 2;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
            transition: transform .2s ease;
        }

        .quick-arrow svg {
            width: 19px;
            height: 19px;
        }

        .quick-card:hover .quick-arrow {
            transform: translateX(4px);
        }


        /* =========================================================
                                                                                   RESPONSIVE
                                                                                   ========================================================= */

        @media (max-width: 991px) {

            .dashboard-hero {
                padding: 30px;
            }

            .osil-wrapper {
                min-height: 230px;
                margin-top: 20px;
            }

            .osil-image {
                width: 190px;
            }

            .osil-message {
                right: 10%;
            }

        }

        @media (max-width: 575px) {

            .dashboard-hero {
                padding: 25px 20px;
                border-radius: 17px;
            }

            .hero-title {
                font-size: 27px;
            }

            .hero-stat {
                min-height: 65px;
            }

            .osil-wrapper {
                min-height: 200px;
            }

            .osil-message {
                right: 0;
                top: 10px;
                transform: scale(.9);
            }

            .quick-card {
                min-height: 130px;
                padding: 17px;
            }

            .quick-icon {
                width: 52px;
                height: 52px;
                border-radius: 16px;
            }

            .quick-icon svg {
                width: 25px;
                height: 25px;
            }

        }
    </style>
@endpush



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>





@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const canvas = document.getElementById(
                'scanByOutletChart'
            );

            if (!canvas) {
                return;
            }


            const labels = @json($outletLabels);

            const totals = @json($outletTotals);


            /*
            |--------------------------------------------------------------------------
            | CREATE GRADIENT
            |--------------------------------------------------------------------------
            */

            const ctx = canvas.getContext('2d');

            const gradient = ctx.createLinearGradient(
                0,
                0,
                0,
                400
            );

            gradient.addColorStop(
                0,
                '#22c55e'
            );

            gradient.addColorStop(
                1,
                '#10b981'
            );


            /*
            |--------------------------------------------------------------------------
            | CHART
            |--------------------------------------------------------------------------
            */

            new Chart(ctx, {

                type: 'bar',

                data: {

                    labels: labels,

                    datasets: [{

                        label: 'Total Scan',

                        data: totals,

                        backgroundColor: gradient,

                        borderRadius: 6,

                        borderSkipped: false,

                        barThickness: 12,

                        maxBarThickness: 16

                    }]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,


                    plugins: {

                        legend: {
                            display: false
                        },

                        tooltip: {

                            backgroundColor: '#1f2937',

                            padding: 10,

                            displayColors: false,

                            callbacks: {

                                label: function(context) {

                                    return (
                                        ' ' +
                                        context.raw +
                                        ' scan'
                                    );

                                }

                            }

                        }

                    },


                    scales: {

                        x: {

                            grid: {
                                display: false
                            },

                            ticks: {

                                color: '#64748b',

                                font: {
                                    size: 10
                                },

                                maxRotation: 90,

                                minRotation: 90

                            }

                        },


                        y: {

                            beginAtZero: true,

                            ticks: {

                                color: '#64748b',

                                precision: 0

                            },

                            grid: {

                                color: '#eef2f3',

                                drawBorder: false

                            }

                        }

                    }

                }

            });

        });
    </script>



    <script>
        // Feather icons
        document.addEventListener('DOMContentLoaded', function() {
            if (window.feather) feather.replace();
        });

        // Simple count-up without lib
        (function() {
            const els = document.querySelectorAll('.countup');
            const easeOut = t => 1 - Math.pow(1 - t, 3);
            const format = (n) => {
                // format integer 1000+ with k
                if (n >= 1000 && Number.isInteger(n)) return (n / 1000).toFixed(n % 1000 === 0 ? 0 : 1) + 'k';
                return n.toLocaleString();
            }
            const onIntersect = (entries, obs) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    const el = entry.target;
                    const target = parseFloat(el.dataset.target || '0');
                    const duration = 1200;
                    const start = performance.now();
                    const isPercent = el.textContent.trim().endsWith('%');
                    const step = (now) => {
                        const p = Math.min(1, (now - start) / duration);
                        const val = target * easeOut(p);
                        el.textContent = isPercent ? (val.toFixed(0)) : (Number.isInteger(target) ? Math
                            .round(val) : val.toFixed(2));
                        if (p < 1) requestAnimationFrame(step);
                        else el.textContent = isPercent ? (target.toFixed(0)) : format(Number.isInteger(
                            target) ? target : Number(target.toFixed(2)));
                    };
                    requestAnimationFrame(step);
                    obs.unobserve(el);
                });
            };
            const io = new IntersectionObserver(onIntersect, {
                threshold: .4
            });
            els.forEach(el => io.observe(el));
        })();
    </script>
@endpush
