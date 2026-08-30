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
    <div class="position-relative overflow-hidden rounded-3 mb-4 hero-welcome text-white">
        <div class="p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-column flex-md-row">
                <div class="text-center text-md-start">
                    <div class="d-inline-flex align-items-center gap-2 mb-2 badge-chip">
                        <span class="badge bg-light text-dark">Welcome back</span>
                        <i data-feather="sparkles" class="align-middle"></i>
                    </div>
                    <h2 class="fw-bold mb-2">Halo, {{ auth()->user()->name ?? 'Developer' }} 👋</h2>
                    <p class="mb-0 opacity-75 typewriter">
                        Selamat datang di panel admin. Semoga harimu produktif dan penuh ide cemerlang!
                    </p>
                </div>
                <div class="text-center">
                    <div class="floating-card shadow-lg rounded-4 bg-white text-dark px-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle"
                                style="width:56px;height:56px;">
                                <i data-feather="activity" class="icon-md"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Uptime aplikasi</div>
                                <div class="h5 mb-0"><span class="countup" data-target="99.98">0</span>%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Animated blobs --}}
        <span class="blob blob-1"></span>
        <span class="blob blob-2"></span>
        <span class="blob blob-3"></span>
        <span class="shine"></span>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card lift-on-hover">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                        style="width:46px;height:46px;">
                        <i data-feather="users" class="icon-sm"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Total Users</div>
                        <div class="h4 mb-0"><span class="countup" data-target="{{ $metrics['users'] ?? 1280 }}">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card lift-on-hover">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                        style="width:46px;height:46px;">
                        <i data-feather="shield" class="icon-sm"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Roles</div>
                        <div class="h4 mb-0"><span class="countup" data-target="{{ $metrics['roles'] ?? 6 }}">0</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card lift-on-hover">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark"
                        style="width:46px;height:46px;">
                        <i data-feather="lock" class="icon-sm"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Permissions</div>
                        <div class="h4 mb-0"><span class="countup"
                                data-target="{{ $metrics['permissions'] ?? 42 }}">0</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card lift-on-hover">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-info text-white"
                        style="width:46px;height:46px;">
                        <i data-feather="check-circle" class="icon-sm"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Tasks Done</div>
                        <div class="h4 mb-0"><span class="countup"
                                data-target="{{ $metrics['tasks_done'] ?? 87 }}">0</span>%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Activity --}}
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="card-title mb-0">Aksi Cepat</h6>
                        <div class="small text-muted">Hemat waktumu 🚀</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <a href="{{ route('super.roles.index') }}"
                                class="btn btn-outline-primary w-100 py-3 hover-glow">
                                <i data-feather="shield" class="me-2 align-text-bottom"></i> Kelola Roles
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="" class="btn btn-outline-success w-100 py-3 hover-glow">
                                <i data-feather="users" class="me-2 align-text-bottom"></i> Kelola Users
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="" class="btn btn-outline-warning w-100 py-3 hover-glow">
                                <i data-feather="lock" class="me-2 align-text-bottom"></i> Permissions
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="" class="btn btn-outline-secondary w-100 py-3 hover-glow">
                                <i data-feather="user" class="me-2 align-text-bottom"></i> Profil Saya
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="" class="btn btn-outline-dark w-100 py-3 hover-glow">
                                <i data-feather="activity" class="me-2 align-text-bottom"></i> System Logs
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="" class="btn btn-outline-info w-100 py-3 hover-glow">
                                <i data-feather="settings" class="me-2 align-text-bottom"></i> Pengaturan
                            </a>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">Aktivitas Terbaru</h6>
                        <a href="" class="small">Lihat semua</a>
                    </div>

                    <ul class="list-group list-group-flush">
                        @forelse(($activities ?? []) as $act)
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                                        style="width:36px;height:36px;">
                                        <i data-feather="{{ $act->icon ?? 'bell' }}" class="icon-sm"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $act->title }}</div>
                                        <div class="small text-muted">{{ $act->desc }}</div>
                                    </div>
                                </div>
                                <div class="text-end small text-muted">{{ $act->created_at?->diffForHumans() }}</div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Belum ada aktivitas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Announcement / Tips --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Pengumuman</h6>
                    <div class="alert alert-info d-flex align-items-start gap-2 mb-2">
                        <i data-feather="info"></i>
                        <div>
                            Maintenance terjadwal hari Minggu pukul 02:00–03:00 WIB.
                        </div>
                    </div>
                    <div class="alert alert-success d-flex align-items-start gap-2 mb-0">
                        <i data-feather="zap"></i>
                        <div>
                            Fitur baru: quick import permissions dari file JSON.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title mb-3">Progress Mingguan</h6>
                    <div class="mb-2 d-flex align-items-center justify-content-between">
                        <span>Implementasi Role</span><span class="small text-muted">80%</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-animated progress-bar-striped bg-success" style="width:80%">
                        </div>
                    </div>
                    <div class="mb-2 d-flex align-items-center justify-content-between">
                        <span>Penyesuaian UI</span><span class="small text-muted">55%</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-animated progress-bar-striped bg-info" style="width:55%">
                        </div>
                    </div>
                    <div class="mb-2 d-flex align-items-center justify-content-between">
                        <span>Pengujian</span><span class="small text-muted">35%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-animated progress-bar-striped bg-warning" style="width:35%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Hero gradient + animation */
        .hero-welcome {
            background: linear-gradient(135deg, #0b07ef, #2121f7);
            /* biru → ungu */
            background-size: 200% 200%;
            animation: gradientMove 12s ease-in-out infinite;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .blob {
            position: absolute;
            filter: blur(40px);
            opacity: .35;
            border-radius: 50%;
            animation: floaty 12s ease-in-out infinite;
        }

        .blob-1 {
            width: 220px;
            height: 220px;
            background: #2d39d7;
            top: -40px;
            left: -40px;
        }

        .blob-2 {
            width: 280px;
            height: 280px;
            background: #3e06e7;
            right: -60px;
            top: -60px;
            animation-delay: -2s;
        }

        .blob-3 {
            width: 200px;
            height: 200px;
            background: #12197a;
            bottom: -60px;
            left: 20%;
            animation-delay: -4s;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) translateX(0) scale(1);
            }

            50% {
                transform: translateY(-12px) translateX(8px) scale(1.03);
            }
        }

        .shine {
            position: absolute;
            inset: 0;
            background: radial-gradient(60% 40% at 10% 10%, rgba(255, 255, 255, .15), transparent 60%);
            mix-blend-mode: screen;
            pointer-events: none;
        }

        .floating-card {
            transform: translateY(0);
            transition: transform .4s ease, box-shadow .4s ease;
        }

        .floating-card:hover {
            transform: translateY(-4px);
        }

        .lift-on-hover {
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .lift-on-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 .75rem 2rem rgba(0, 0, 0, .08) !important;
        }

        .hover-glow {
            position: relative;
            overflow: hidden;
        }

        .hover-glow::after {
            content: "";
            position: absolute;
            inset: auto 0 0 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, .9), transparent);
            transform: translateX(-100%);
            transition: transform .6s ease;
        }

        .hover-glow:hover::after {
            transform: translateX(0);
        }

        .badge-chip {
            user-select: none;
        }

        /* Typewriter (subtle) */
        .typewriter {
            position: relative;
            display: inline-block;
            padding-right: 6px;
        }

        .typewriter::after {
            content: "";
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: rgba(255, 255, 255, .8);
            animation: caret 1s steps(1) infinite;
        }

        @keyframes caret {
            50% {
                opacity: 0;
            }
        }

        /* Reduce motion */
        @media (prefers-reduced-motion: reduce) {

            .hero-welcome,
            .blob,
            .shine,
            .hover-glow::after,
            .floating-card,
            .lift-on-hover {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
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
