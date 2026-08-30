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
