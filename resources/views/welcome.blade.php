<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Scanner — Smart Scanner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --green-900: #064e3b;
            --green-800: #065f46;
            --green-700: #047857;
            --green-600: #059669;
            --green-500: #10b981;
            --green-400: #34d399;
            --mint: #ecfdf5;
            --cyan: #22d3ee;
            --dark: #06251c;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;
            overflow-x: hidden;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", sans-serif;
            color: var(--dark);
            background:
                radial-gradient(circle at 10% 10%, rgba(16, 185, 129, .12), transparent 28%),
                radial-gradient(circle at 90% 20%, rgba(52, 211, 153, .15), transparent 30%),
                #f5fbf8;
        }

        /* =========================
           PAGE
        ========================= */

        .welcome-page {
            min-height: 100vh;
            padding: 28px;
            position: relative;
            isolation: isolate;
        }

        .background-orb {
            position: fixed;
            z-index: -1;
            border-radius: 50%;
            filter: blur(2px);
            pointer-events: none;
            animation: orbFloat 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 300px;
            height: 300px;
            top: -120px;
            left: -100px;
            background: rgba(16, 185, 129, .10);
        }

        .orb-2 {
            width: 420px;
            height: 420px;
            right: -180px;
            bottom: -160px;
            background: rgba(52, 211, 153, .12);
            animation-delay: -3s;
        }

        /* =========================
           HERO
        ========================= */

        .hero {
            position: relative;
            overflow: hidden;
            min-height: 600px;
            border-radius: 34px;
            padding: 64px 72px;
            display: flex;
            align-items: center;
            background:
                radial-gradient(circle at 78% 45%, rgba(255, 255, 255, .88), transparent 20%),
                linear-gradient(135deg, #f0fdf7 0%, #d1fae5 48%, #a7f3d0 100%);
            border: 1px solid rgba(5, 150, 105, .14);
            box-shadow:
                0 25px 80px rgba(6, 78, 59, .12),
                inset 0 1px 0 rgba(255, 255, 255, .8);
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            right: -140px;
            top: -180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .24);
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 330px;
            height: 330px;
            left: 45%;
            bottom: -260px;
            border-radius: 50%;
            border: 1px solid rgba(5, 150, 105, .10);
            box-shadow:
                0 0 0 55px rgba(5, 150, 105, .04),
                0 0 0 110px rgba(5, 150, 105, .025);
        }

        .hero-content {
            position: relative;
            z-index: 5;
            width: 58%;
        }

        .welcome-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .82);
            color: var(--green-700);
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(6, 78, 59, .07);
            backdrop-filter: blur(8px);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 6px rgba(16, 185, 129, .12);
            animation: pulseDot 2s infinite;
        }

        .hero-title {
            margin: 28px 0 14px;
            font-size: clamp(42px, 5vw, 72px);
            line-height: .98;
            letter-spacing: -3px;
            font-weight: 850;
            color: #052e22;
        }

        .hero-title .accent {
            color: var(--green-700);
        }

        .hero-description {
            max-width: 680px;
            margin-bottom: 30px;
            color: #477263;
            font-size: 18px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 23px;
            border-radius: 15px;
            color: white;
            background: linear-gradient(135deg, #059669, #10b981);
            text-decoration: none;
            font-weight: 750;
            box-shadow: 0 14px 30px rgba(5, 150, 105, .25);
            transition: .25s ease;
        }

        .btn-login:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 18px 36px rgba(5, 150, 105, .32);
        }

        .btn-login svg {
            transition: transform .25s ease;
        }

        .btn-login:hover svg {
            transform: translateX(4px);
        }

        .btn-info {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 19px;
            border-radius: 15px;
            color: var(--green-800);
            background: rgba(255, 255, 255, .68);
            border: 1px solid rgba(5, 150, 105, .12);
            text-decoration: none;
            font-weight: 650;
            transition: .25s ease;
        }

        .btn-info:hover {
            color: var(--green-800);
            background: white;
            transform: translateY(-2px);
        }

        /* =========================
           OSIL IMAGE
        ========================= */

        .hero-visual {
            position: absolute;
            z-index: 4;
            width: 40%;
            right: 4%;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .visual-glow {
            position: absolute;
            width: 390px;
            height: 390px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .65);
            filter: blur(18px);
            animation: glowPulse 4s ease-in-out infinite;
        }

        .osil-image {
            position: relative;
            z-index: 2;
            width: min(330px, 80%);
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 25px 20px rgba(6, 78, 59, .18));
            animation: osilFloat 4.5s ease-in-out infinite;
            transform-origin: center bottom;
        }

        .osil-spark {
            position: absolute;
            z-index: 3;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 0 18px rgba(255, 255, 255, .95);
            animation: sparkle 3s ease-in-out infinite;
        }

        .spark-1 {
            top: 18%;
            right: 22%;
        }

        .spark-2 {
            top: 58%;
            left: 15%;
            width: 8px;
            height: 8px;
            animation-delay: -1s;
        }

        .spark-3 {
            bottom: 17%;
            right: 18%;
            width: 7px;
            height: 7px;
            animation-delay: -2s;
        }

        .osil-badge {
            position: absolute;
            z-index: 5;
            right: -5px;
            top: 8%;
            min-width: 210px;
            padding: 16px 20px;
            border-radius: 18px;
            color: white;
            background: linear-gradient(135deg, #047857, #059669);
            box-shadow: 0 18px 35px rgba(6, 95, 70, .20);
            animation: badgeFloat 4s ease-in-out infinite;
        }

        .osil-badge small {
            display: block;
            opacity: .78;
            margin-bottom: 3px;
        }

        .osil-badge strong {
            font-size: 19px;
        }

        .osil-badge strong span {
            color: #fde68a;
        }

        /* =========================
           QUICK CARDS
        ========================= */

        .quick-grid {
            margin-top: 22px;
        }

        .quick-card {
            height: 100%;
            min-height: 205px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 22px;
            padding: 28px;
            border-radius: 27px;
            text-decoration: none;
            color: #12382d;
            background: rgba(255, 255, 255, .91);
            border: 1px solid rgba(6, 95, 70, .09);
            box-shadow: 0 15px 40px rgba(6, 78, 59, .07);
            transition: .3s ease;
        }

        .quick-card:hover {
            color: #12382d;
            transform: translateY(-7px);
            box-shadow: 0 22px 48px rgba(6, 78, 59, .13);
        }

        .quick-card::after {
            content: "";
            position: absolute;
            width: 170px;
            height: 170px;
            right: -60px;
            bottom: -95px;
            border-radius: 50%;
            background: rgba(16, 185, 129, .07);
            transition: .3s ease;
        }

        .quick-card:hover::after {
            transform: scale(1.35);
        }

        .quick-icon {
            position: relative;
            z-index: 2;
            width: 76px;
            height: 76px;
            flex: 0 0 76px;
            border-radius: 23px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dcfce7;
            color: #047857;
        }

        .quick-icon svg {
            width: 35px;
            height: 35px;
            stroke-width: 1.8;
        }

        .quick-content {
            position: relative;
            z-index: 2;
        }

        .quick-content h4 {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 800;
            color: #087443;
        }

        .quick-content p {
            margin: 0 0 14px;
            color: #708d82;
            line-height: 1.55;
        }

        .quick-action {
            color: #059669;
            font-weight: 800;
            font-size: 14px;
        }

        .quick-arrow {
            position: relative;
            z-index: 3;
            margin-left: auto;
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: white;
            color: #059669;
            box-shadow: 0 8px 22px rgba(6, 78, 59, .10);
            transition: .3s ease;
        }

        .quick-card:hover .quick-arrow {
            transform: translateX(5px);
            background: #059669;
            color: white;
        }

        .quick-card-blue .quick-icon {
            background: #dbeafe;
            color: #2563eb;
        }

        .quick-card-blue .quick-content h4,
        .quick-card-blue .quick-action {
            color: #2563eb;
        }

        .quick-card-blue .quick-arrow {
            color: #2563eb;
        }

        .quick-card-blue:hover .quick-arrow {
            background: #2563eb;
            color: white;
        }

        .quick-card-gold .quick-icon {
            background: #fef3c7;
            color: #ca8a04;
        }

        .quick-card-gold .quick-content h4,
        .quick-card-gold .quick-action {
            color: #b7791f;
        }

        .quick-card-gold .quick-arrow {
            color: #ca8a04;
        }

        .quick-card-gold:hover .quick-arrow {
            background: #ca8a04;
            color: white;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer-note {
            text-align: center;
            padding: 20px 0 5px;
            color: #769187;
            font-size: 13px;
        }

        /* =========================
           ANIMATIONS
        ========================= */

        @keyframes osilFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(1deg);
            }
        }

        @keyframes badgeFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-9px);
            }
        }

        @keyframes glowPulse {

            0%,
            100% {
                transform: scale(.95);
                opacity: .65;
            }

            50% {
                transform: scale(1.08);
                opacity: .9;
            }
        }

        @keyframes sparkle {

            0%,
            100% {
                opacity: .15;
                transform: scale(.7);
            }

            50% {
                opacity: 1;
                transform: scale(1.3);
            }
        }

        @keyframes pulseDot {

            0%,
            100% {
                box-shadow: 0 0 0 5px rgba(16, 185, 129, .10);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, .03);
            }
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            50% {
                transform: translate3d(0, 25px, 0);
            }
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 991.98px) {
            .welcome-page {
                padding: 16px;
            }

            .hero {
                min-height: auto;
                padding: 45px 35px 35px;
            }

            .hero-content {
                width: 100%;
            }

            .hero-visual {
                position: relative;
                width: 100%;
                right: auto;
                top: auto;
                transform: none;
                margin-top: 35px;
                min-height: 330px;
            }

            .hero-title {
                font-size: clamp(42px, 9vw, 62px);
            }

            .osil-image {
                width: 260px;
            }

            .osil-badge {
                right: 10%;
                top: 4%;
            }
        }

        @media (max-width: 575.98px) {
            .hero {
                border-radius: 24px;
                padding: 32px 22px 25px;
            }

            .hero-title {
                letter-spacing: -2px;
                font-size: 42px;
            }

            .hero-description {
                font-size: 16px;
            }

            .hero-visual {
                min-height: 290px;
            }

            .visual-glow {
                width: 290px;
                height: 290px;
            }

            .osil-image {
                width: 230px;
            }

            .osil-badge {
                right: 0;
                min-width: 175px;
                padding: 12px 15px;
            }

            .osil-badge strong {
                font-size: 16px;
            }

            .quick-card {
                min-height: 170px;
                padding: 22px;
            }

            .quick-icon {
                width: 64px;
                height: 64px;
                flex-basis: 64px;
            }

            .quick-content h4 {
                font-size: 19px;
            }

            .quick-content p {
                font-size: 14px;
            }

            .quick-arrow {
                width: 42px;
                height: 42px;
                flex-basis: 42px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    <div class="welcome-page">

        <span class="background-orb orb-1"></span>
        <span class="background-orb orb-2"></span>

        {{-- ========================= HERO ========================= --}}
        <section class="hero">

            <div class="hero-content">

                <div class="welcome-pill">
                    <span class="status-dot"></span>
                    Welcome back
                </div>

                <h1 class="hero-title">
                    Smart Scanner
                    <span class="accent">System.</span>
                </h1>

                <p class="hero-description">
                    Scanner tiket digital untuk validasi QR Code dan barcode
                    secara cepat, aman, dan akurat. Pantau aktivitas scan
                    dan laporan tiket dalam satu platform.
                </p>

                <div class="hero-actions">

                    <a href="{{ route('login', absolute: false) }}" class="btn-login">
                        Login Sekarang

                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">

                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />

                        </svg>
                    </a>

                    <span class="btn-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">

                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>

                        </svg>

                        Secure Scan Validation
                    </span>

                </div>

            </div>

            {{-- ========================= OSIL ========================= --}}
            <div class="hero-visual">

                <div class="visual-glow"></div>

                <span class="osil-spark spark-1"></span>
                <span class="osil-spark spark-2"></span>
                <span class="osil-spark spark-3"></span>

                {{-- File osil.png berada di public/osil.png --}}
                <img src="{{ asset('images/osil.png') }}" alt="OSIL" class="osil-image">

                <div class="osil-badge">
                    <small>Bersama OSIL</small>
                    <strong>Semua Lebih <span>Mudah!</span></strong>
                </div>

            </div>

        </section>


        {{-- ========================= QUICK MENU ========================= --}}
        <section class="quick-grid">

            <div class="row g-3">

                @can('scan-records.create')
                    {{-- CAMERA --}}
                    <div class="col-xl-4 col-md-6">

                        <a href="{{ route('super.scan-records.camera') }}" class="quick-card">

                            <div class="quick-icon">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                    <path
                                        d="M14.5 4h-5L8 7H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-1.5-3z">
                                    </path>
                                    <circle cx="12" cy="13" r="3"></circle>

                                </svg>

                            </div>

                            <div class="quick-content">

                                <h4>Scan Camera</h4>

                                <p>
                                    Scan QR Code menggunakan kamera
                                    dengan cepat dan mudah.
                                </p>

                                <span class="quick-action">
                                    Mulai Scan
                                </span>

                            </div>

                            <div class="quick-arrow">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>

                                </svg>

                            </div>

                        </a>

                    </div>


                    {{-- BARCODE --}}
                    <div class="col-xl-4 col-md-6">

                        <a href="{{ route('super.scan-records.scanner') }}" class="quick-card quick-card-blue">

                            <div class="quick-icon">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                    <path d="M4 7V5a1 1 0 0 1 1-1h2"></path>
                                    <path d="M17 4h2a1 1 0 0 1 1 1v2"></path>
                                    <path d="M20 17v2a1 1 0 0 1-1 1h-2"></path>
                                    <path d="M7 20H5a1 1 0 0 1-1-1v-2"></path>
                                    <path d="M8 8v8"></path>
                                    <path d="M11 8v8"></path>
                                    <path d="M14 8v8"></path>
                                    <path d="M17 8v8"></path>

                                </svg>

                            </div>

                            <div class="quick-content">

                                <h4>Barcode Scanner</h4>

                                <p>
                                    Scan barcode tiket menggunakan
                                    scanner perangkat.
                                </p>

                                <span class="quick-action">
                                    Mulai Scan
                                </span>

                            </div>

                            <div class="quick-arrow">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>

                                </svg>

                            </div>

                        </a>

                    </div>
                @endcan


                {{-- REPORT --}}
                @can('scan-records.view')
                    <div class="col-xl-4 col-md-6">

                        <a href="{{ route('super.scan-records.index') }}" class="quick-card quick-card-gold">

                            <div class="quick-icon">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="8" y1="13" x2="16" y2="13"></line>
                                    <line x1="8" y1="17" x2="14" y2="17"></line>

                                </svg>

                            </div>

                            <div class="quick-content">

                                <h4>Report</h4>

                                <p>
                                    Lihat laporan dan statistik scan
                                    secara lengkap.
                                </p>

                                <span class="quick-action">
                                    Lihat Laporan
                                </span>

                            </div>

                            <div class="quick-arrow">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">

                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>

                                </svg>

                            </div>

                        </a>

                    </div>
                @endcan

            </div>

        </section>


        <div class="footer-note">
            Smart Scanner System &bull; Secure &bull; Fast &bull; Reliable
        </div>

    </div>

</body>

</html>
