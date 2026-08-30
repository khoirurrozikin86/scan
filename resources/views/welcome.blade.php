<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ticketing — Smart Ticketing</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #09090b, #18181b, #09090b);
            color: #f4f4f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-floaty {
            animation: floaty 4s ease-in-out infinite;
        }

        .gradient-btn {
            background: linear-gradient(to right, #34d399, #0ea5e9);
            color: #0a0a0a;
            font-weight: 500;
            border: none;
            border-radius: 1rem;
            padding: 0.75rem 1.5rem;
            transition: opacity 0.3s ease;
            text-decoration: none;
        }

        .gradient-btn:hover {
            opacity: 0.9;
        }

        .gradient-logo {
            background: linear-gradient(to right, #34d399, #0ea5e9);
            color: #0a0a0a;
        }
    </style>
</head>

<body>

    <div class="text-center px-4">
        <!-- logo -->
       <div class="d-flex justify-content-center mb-4">
    <div class="gradient-logo rounded-4 d-flex align-items-center justify-content-center shadow-lg animate-floaty"
        style="width: 64px; height: 64px;">

        <svg xmlns="http://www.w3.org/2000/svg"
            width="34"
            height="34"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round">

            <path d="M3 9V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2"/>
            <path d="M3 15v2a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2"/>
            <path d="M12 5v14"/>
            <path d="M3 9a2 2 0 1 0 0 6"/>
            <path d="M21 9a2 2 0 1 1 0 6"/>

        </svg>

    </div>
</div>

        <!-- heading -->
        <h1 class="fw-bold mb-3 display-5">
            Welcome On
            <span
                style="background: linear-gradient(to right, #34d399, #0ea5e9); -webkit-background-clip: text; color: transparent;">
                Ticketing
            </span>
        </h1>

        <p class="text-secondary mx-auto mb-4" style="max-width: 420px;">
            Sistem manajemen tiket cerdas untuk acara Anda. Buat, kelola, dan pantau  tiket dengan mudah dalam satu platform yang intuitif.
        </p>

        <!-- tombol login -->
        <a href="{{ route('login', absolute: false) ?? '/login' }}"
            class="gradient-btn d-inline-flex align-items-center">

            Login Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-2" viewBox="0 0 24 24">
                <path d="M5 12h14" />
                <path d="m12 5 7 7-7 7" />
            </svg>
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
