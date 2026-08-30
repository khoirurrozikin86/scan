<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Jasa Pembuatan Aplikasi & Networking di Semarang, kab Semarang dan sekitarnya | OZNET Systems')</title>
    <meta name="description" content="@yield('meta_description', 'Jasa pembuatan aplikasi web, inventory, POS, Purchasing, Mikrotik, RADIUS, CCTV, server & NAS di Semarang & Kab. Semarang. Konsultasi gratis!')" />
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Twitter (share preview) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', 'Jasa Pembuatan Aplikasi & Networking di Semarang | OZNET Systems')">
    <meta property="og:description" content="@yield('og_description', 'Layanan aplikasi, jaringan, Mikrotik, CCTV. Konsultasi gratis!')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-cover.jpg'))">
    <meta name="twitter:card" content="summary_large_image">

    {{-- Robots: index semua halaman utama; noindex untuk arsip/pencarian nanti --}}
    @stack('meta_robots')

    {{-- Bahasa laman --}}
    <link rel="alternate" href="{{ url()->current() }}" hreflang="id" />

    {{-- Favicon (sudah kamu punya) --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}"
        type="image/x-icon">


    {{-- JSON-LD LocalBusiness (rich results) --}}
    {{-- JSON-LD LocalBusiness + SiteNavigationElement --}}
    @verbatim
        <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "OZNET Systems",
  "url": "https://oznets.com",
  "telephone": "+62-896-3749-8586",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Semarang dan Kabupaten Semarang",
    "addressRegion": "Jawa Tengah",
    "addressCountry": "ID"
  },
  "areaServed": ["Semarang", "Kabupaten Semarang", "Jawa Tengah"],
  "sameAs": [
    "https://www.instagram.com/oznetsystems",
    "https://www.facebook.com/oznetsystems"
  ]
}
</script>

        <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    { "@type": "SiteNavigationElement", "name": "Layanan", "url": "https://oznets.com/#services" },
    { "@type": "SiteNavigationElement", "name": "Teknologi", "url": "https://oznets.com/#tech" },
    { "@type": "SiteNavigationElement", "name": "Portfolio", "url": "https://oznets.com/#portfolio" },
    { "@type": "SiteNavigationElement", "name": "Proses", "url": "https://oznets.com/#process" },
    { "@type": "SiteNavigationElement", "name": "Tentang Kami", "url": "https://oznets.com/#about" },
    { "@type": "SiteNavigationElement", "name": "Kontak", "url": "https://oznets.com/#contact" }
  ]
}
</script>
    @endverbatim



    {{-- CSS/JS (Vite build, cepat) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])





    <script>
        // tailwind.config = {
        //     theme: {
        //         extend: {
        //             colors: {
        //                 brand: {
        //                     DEFAULT: '#10B981',
        //                     50: '#ECFDF5',
        //                     100: '#D1FAE5',
        //                     200: '#A7F3D0',
        //                     300: '#6EE7B7',
        //                     400: '#34D399',
        //                     500: '#10B981',
        //                     600: '#059669',
        //                     700: '#047857',
        //                     800: '#065F46',
        //                     900: '#064E3B'
        //                 }
        //             },
        //             boxShadow: {
        //                 glow: '0 0 0 6px rgba(16,185,129,0.15), 0 10px 50px rgba(16,185,129,0.25)'
        //             },
        //             keyframes: {
        //                 floaty: {
        //                     '0%,100%': {
        //                         transform: 'translateY(0)'
        //                     },
        //                     '50%': {
        //                         transform: 'translateY(-12px)'
        //                     }
        //                 },
        //                 shimmer: {
        //                     '100%': {
        //                         transform: 'translateX(100%)'
        //                     }
        //                 },
        //                 blob: {
        //                     '0%': {
        //                         transform: 'translate(0px, 0px) scale(1)'
        //                     },
        //                     '33%': {
        //                         transform: 'translate(20px, -30px) scale(1.05)'
        //                     },
        //                     '66%': {
        //                         transform: 'translate(-20px, 10px) scale(0.975)'
        //                     },
        //                     '100%': {
        //                         transform: 'translate(0px, 0px) scale(1)'
        //                     }
        //                 }
        //             },
        //             animation: {
        //                 floaty: 'floaty 6s ease-in-out infinite',
        //                 shimmer: 'shimmer 1.2s linear infinite',
        //                 blob: 'blob 14s ease-in-out infinite'
        //             }
        //         }
        //     }
        // }
    </script>

    <style>
        /* Reveal on scroll */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .8s ease, transform .8s ease;
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Simple marquee */
        .marquee {
            display: flex;
            gap: 4rem;
            white-space: nowrap;
            animation: marquee 28s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        /* Gradient background */
        .bg-grid {
            background-image: radial-gradient(circle at 1px 1px, rgb(255 255 255 / 8%) 1px, transparent 0);
            background-size: 24px 24px;
        }

        /* Scroll snap for portfolio */
        .snap-x {
            scroll-snap-type: x mandatory;
        }

        .snap-center {
            scroll-snap-align: center;
        }

        /* Glass card */
        .glass {
            background: linear-gradient(180deg, rgb(255 255 255 / .10), rgb(255 255 255 / .06));
            backdrop-filter: blur(8px);
            border: 1px solid rgb(255 255 255 / .12);
        }



        /* Sembunyikan scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }




        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-4px);
            }
        }

        @keyframes pulseDot {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .animate-[shimmer_1\.8s_linear_infinite] {
            animation: shimmer 1.8s linear infinite;
        }

        .animate-floaty {
            animation: floaty 4s ease-in-out infinite;
        }

        .pulse-dot {
            animation: pulseDot 1.8s ease-out infinite;
        }

        .glass {
            background: linear-gradient(180deg, rgba(255, 255, 255, .08), rgba(255, 255, 255, .02));
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, .12);
        }

        /* .text-brand {
            color: #7c9cff;
        } */

        /* sesuaikan brand */
        /* .bg-brand\/20 {
            background-color: rgb(124 156 255 / .2);
        }

        .border-brand\/30 {
            border-color: rgb(124 156 255 / .3);
        } */
    </style>
</head>


<!-- Floating WhatsApp -->
<a href="https://wa.me/6289637498586?text=Halo%20Oznet%20IT%20Solution,%20saya%20ingin%20konsultasi%20tentang%20proyek%20IT."
    target="_blank" rel="noopener" target="_blank" rel="noopener" aria-label="Chat WhatsApp OZNET Systems"
    class="fixed bottom-5 right-5 z-50 group">
    <span class="sr-only">Chat WhatsApp</span>

    <!-- ripple/ping -->
    <span class="absolute inset-0 rounded-full animate-ping bg-[#25D366]/40"></span>

    <!-- button -->
    <div
        class="relative w-14 h-14 rounded-full bg-[#25D366] shadow-glow
              flex items-center justify-center transition-transform
              hover:scale-110 focus:scale-110 focus:outline-none">
        <!-- WhatsApp logo (white) -->
        <!-- WhatsApp icon resmi (centered, no background putih) -->
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M20.52 3.48A11.85 11.85 0 0 0 12 .25a11.75 11.75 0 0 0-10.3 17.64L.25 24l6.28-1.65A11.77 11.77 0 0 0 12 23.75c6.6 0 12-5.37 12-12 0-3.2-1.25-6.22-3.48-8.27ZM12 21.4c-2 0-3.97-.54-5.7-1.56l-.4-.23-3.73.98 1-3.64-.25-.38a9.4 9.4 0 0 1-1.45-5A9.44 9.44 0 0 1 12 2.6a9.4 9.4 0 0 1 9.4 9.4c0 5.2-4.24 9.4-9.4 9.4Zm5.24-7.04c-.29-.15-1.72-.85-1.98-.95-.27-.1-.46-.15-.66.15-.2.29-.76.94-.93 1.14-.17.2-.34.22-.63.07-.29-.15-1.2-.44-2.29-1.4-.85-.76-1.42-1.7-1.59-1.99-.17-.29-.02-.45.13-.6.14-.14.29-.34.44-.51.15-.17.2-.29.29-.48.1-.2.05-.36-.02-.51-.07-.15-.66-1.58-.91-2.17-.24-.58-.49-.5-.66-.51h-.57c-.19 0-.51.07-.78.36-.27.29-1.02 1-1.02 2.44 0 1.44 1.05 2.83 1.2 3.02.15.2 2.07 3.17 5.02 4.45.7.3 1.24.48 1.66.62.7.22 1.34.19 1.85.12.57-.08 1.72-.7 1.97-1.38.24-.68.24-1.26.17-1.38-.07-.12-.27-.2-.56-.35Z" />
        </svg>


        <span
            class="absolute -left-2 -top-8 translate-y-1 opacity-0 group-hover:opacity-100
                     group-hover:-translate-y-1 transition text-xs bg-white/10 border border-white/15
                     px-2 py-1 rounded-lg backdrop-blur">Chat
            WhatsApp</span>
    </div>
</a>


<body class="bg-neutral-950 text-white antialiased selection:bg-brand/30">

    <!-- Sticky Navbar -->
    <header class="sticky top-0 z-40 border-b border-white/10 bg-neutral-950/70 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            {{-- <a href="#home" class="flex items-center gap-3 group">
                <!-- Inline Stylus S icon -->
                <svg width="28" height="28" viewBox="0 0 256 256"
                    class="text-brand group-hover:scale-110 transition-transform duration-300">
                    <g fill="none" stroke="currentColor" stroke-width="36" stroke-linecap="butt">
                        <path d="M200 92 A72 72 0 0 0 56 92" />
                        <path d="M56 164 A72 72 0 0 0 200 164" />
                    </g>
                </svg>
                <span class="font-semibold tracking-wide">OZNET Systemsxx</span>
            </a> --}}
            <a href="#home" class="flex items-center gap-3 group">
                @php $brand = $site['brand'] ?? 'OZNET Systems'; @endphp

                <svg width="28" height="28" viewBox="0 0 256 256"
                    class="text-brand group-hover:scale-110 transition-transform duration-300" aria-hidden="true">
                    <g fill="none" stroke="currentColor" stroke-width="36" stroke-linecap="butt">
                        <path d="M200 92 A72 72 0 0 0 56 92" />
                        <path d="M56 164 A72 72 0 0 0 200 164" />
                    </g>
                </svg>

                <span class="font-semibold tracking-wide">{{ $brand }}</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                @if ($menu)
                    @foreach ($menu->items as $it)
                        <a href="{{ $it->url }}" class="hover:text-brand">{{ $it->label }}</a>
                    @endforeach
                @endif
            </nav>
            <div class="flex items-center gap-3">
                <a href="#contact"
                    class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-brand px-4 py-2 font-semibold shadow-glow hover:scale-[1.02] transition">
                    Konsultasi Gratis
                </a>
                <button id="menuBtn"
                    class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-white/15">
                    <span class="sr-only">Open menu</span>
                    <!-- burger -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 6h18M3 12h18M3 18h18" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- mobile menu -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-white/10">
            <div class="px-4 py-3 space-y-2">
                <a href="#services" class="block py-2">Layanan</a>
                <a href="#tech" class="block py-2">Teknologi</a>
                <a href="#portfolio" class="block py-2">Portfolio</a>
                <a href="#process" class="block py-2">Proses</a>
                <a href="#contact" class="block py-2">Kontak</a>
            </div>
        </div>
    </header>

    <!-- HERO -->
    <section id="home" class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] rounded-full bg-brand/20 blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-40 -right-40 w-[32rem] h-[32rem] rounded-full bg-emerald-600/20 blur-3xl animate-blob [animation-delay:2s]">
            </div>
            <div
                class="absolute -bottom-40 left-1/3 w-[28rem] h-[28rem] rounded-full bg-teal-400/10 blur-3xl animate-blob [animation-delay:4s]">
            </div>
            <div class="absolute inset-0 bg-grid opacity-30"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16 lg:pt-28 lg:pb-24">
            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <div class="reveal">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs text-white/80">
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand/30">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3">
                                <circle cx="12" cy="12" r="4" />
                            </svg>
                        </span>
                        Solusi End‑to‑End: Aplikasi • Infrastruktur • Keamanan
                    </div>
                    <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                        Bangun Produk Digital & Infrastruktur <span class="text-brand">Tanpa Ribet</span>
                    </h1>
                    <p class="mt-4 text-white/80 max-w-xl">
                        Kami mengembangkan aplikasi web (CMS sekolah, hotel, perusahaan, inventory, POS,Purchasing,
                        Peminjaman barang, App Custom Lainya, dll) sekaligus
                        menyiapkan jaringan, Mikrotik, RADIUS, hotspot, CCTV, server & NAS. Satu tim untuk semua
                        kebutuhan Anda.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a href="#contact"
                            class="inline-flex items-center gap-2 rounded-xl bg-brand px-6 py-3 font-semibold shadow-glow hover:scale-[1.02] transition">Diskusikan
                            Proyek</a>
                        <a href="#services"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/15 px-6 py-3 font-semibold hover:border-brand/60 transition">Lihat
                            Layanan</a>
                    </div>
                    <div class="mt-10 text-sm text-white/70">
                        Dipercaya oleh bisnis lokal & institusi pendidikan. Garansi dokumentasi & dukungan purna-jual.
                    </div>
                </div>




                <div class="reveal lg:justify-self-end">
                    <!-- Mock device card -->
                    <div class="relative w-full max-w-md mx-auto rounded-3xl glass p-6 shadow-2xl">
                        <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none">
                            <div class="absolute inset-0 bg-gradient-to-tr from-brand/20 to-transparent"></div>
                            <div
                                class="absolute -inset-1 bg-[linear-gradient(110deg,transparent,rgba(255,255,255,.25),transparent)] animate-[shimmer_1.8s_linear_infinite]">
                            </div>
                        </div>

                        <div class="relative">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-brand/20 flex items-center justify-center">
                                        <!-- S icon -->
                                        <svg width="24" height="24" viewBox="0 0 256 256" class="text-brand">
                                            <g fill="none" stroke="currentColor" stroke-width="36"
                                                stroke-linecap="butt">
                                                <path d="M200 92 A72 72 0 0 0 56 92" />
                                                <path d="M56 164 A72 72 0 0 0 200 164" />
                                            </g>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-white/60">Stylus Dashboard</div>
                                        <div class="font-semibold">Realtime Metrics</div>
                                    </div>
                                </div>
                                <!-- status badge -->
                                <div class="flex items-center gap-2">
                                    <span
                                        class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400 pulse-dot"></span>
                                    <span class="text-xs text-emerald-300/90">Live</span>
                                </div>
                            </div>

                            <!-- Tiles -->
                            <div
                                class="h-64 rounded-2xl bg-white/5 border border-white/10 p-4 grid grid-cols-3 gap-4 text-white">
                                <!-- Active Users + sparkline -->
                                <div
                                    class="col-span-2 rounded-xl border border-brand/30 bg-gradient-to-br from-brand/20 to-transparent p-4 flex flex-col justify-between animate-floaty">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-white/80">Active Users</span>
                                        <span
                                            class="text-[10px] px-2 py-0.5 rounded-full bg-white/10 border border-white/10">Last
                                            5m</span>
                                    </div>
                                    <div class="text-3xl font-bold leading-none">1,248</div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-emerald-300">+12% vs 60m</span>
                                        <!-- sparkline -->
                                        <svg viewBox="0 0 120 28" class="h-8 w-24 opacity-90">
                                            <polyline fill="none" stroke="currentColor" stroke-width="2"
                                                class="text-white/70"
                                                points="2,22 12,18 22,20 32,14 42,16 52,10 62,13 72,9 82,11 92,7 102,10 112,6" />
                                            <polyline fill="currentColor" class="text-white/10"
                                                points="2,28 2,22 12,18 22,20 32,14 42,16 52,10 62,13 72,9 82,11 92,7 102,10 112,6 112,28" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Server Load -->
                                <div class="rounded-xl bg-white/10 border border-white/10 p-3 flex flex-col">
                                    <div class="flex items-center justify-between text-xs text-white/70">
                                        <span>Server Load</span>
                                        <span class="font-medium">78%</span>
                                    </div>
                                    <div class="mt-2 h-2 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full rounded-full bg-white/80 w-[78%]"></div>
                                    </div>
                                    <div class="mt-1 text-[11px] text-amber-300">Moderate</div>
                                    <div class="mt-auto pt-2 text-[10px] text-white/50">avg 5m • 8 nodes</div>
                                </div>

                                <!-- Response Time -->
                                {{-- <div class="rounded-xl bg-white/10 border border-white/10 p-3 flex flex-col">
                                    <div class="flex items-center justify-between text-xs text-white/70">
                                        <span>Latency</span>
                                        <span class="font-medium">142ms</span>
                                    </div>
                                    <div class="mt-2 h-2 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full rounded-full bg-emerald-400 w-[64%]"></div>
                                    </div>
                                    <div class="mt-1 text-[11px] text-emerald-300">OK</div>
                                    <div class="mt-auto pt-2 text-[10px] text-white/50">p95 • APAC</div>
                                </div> --}}

                                <!-- Bottom strip: incidents / uptime / CTA -->
                                <div class="col-span-3 rounded-xl bg-white/10 border border-white/10 p-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="flex -space-x-2">
                                                <span
                                                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 border border-emerald-400/30 text-[10px]">OK</span>
                                                <span
                                                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-amber-500/20 border border-amber-400/30 text-[10px]">API</span>
                                                <span
                                                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-sky-500/20 border border-sky-400/30 text-[10px]">DB</span>
                                            </div>
                                            <div class="text-xs text-white/70">
                                                <span class="font-medium">Uptime 99.97%</span>
                                                <span class="mx-2 text-white/30">•</span>
                                                <span>0 incidents (24h)</span>
                                            </div>
                                        </div>
                                        <button
                                            class="text-xs px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15 transition">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer note -->
                            <div class="mt-4 text-xs text-white/60">
                                Contoh UI: <span class="text-white/80">glassmorphism</span>, shimmer, sparkline, status
                                badge, dan metrik realtime yang kontekstual.
                            </div>
                        </div>
                    </div>
                </div>





            </div>
        </div>

        <!-- marquee tech -->
        <div class="border-t border-white/10 bg-neutral-900/40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 overflow-hidden">
                {{-- wrapper diberi mask halus di sisi kiri/kanan --}}
                <div class="marquee opacity-80 mask-edges">
                    {{-- loop 1 (dinamis) --}}
                    @foreach ($stacks as $t)
                        <span class="tracking-wider">{{ $t->name }}</span>
                    @endforeach

                    {{-- loop 2 (duplikasi konten untuk efek infinite) --}}
                    @foreach ($stacks as $t)
                        <span class="tracking-wider">{{ $t->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>




    <!-- SERVICES -->
    <section id="services" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold">Layanan Kami</h2>
                <p class="mt-3 text-white/70 max-w-2xl mx-auto">

                    Mulai dari aplikasi siap pakai hingga konfigurasi infrastruktur yang aman dan andal.
                    Kami menyediakan jasa pembuatan aplikasi web, sistem POS, sistem inventory, website perusahaan,
                    sistem pembayaran, hingga integrasi server dan jaringan Mikrotik untuk berbagai kebutuhan
                    bisnis dan instansi.

                    OZNET Systems menerima pembuatan aplikasi dan layanan IT di wilayah:
                    Ambarawa, Bawen, Bandungan, Kabupaten Semarang, Kota Semarang, Salatiga, Ungaran, dan seluruh Jawa
                    Tengah.
                    Kami juga melayani kerja sama dan proyek dari luar daerah secara online, dengan konsultasi dan
                    dukungan teknis jarak jauh.

                    Dengan pengalaman di bidang pengembangan aplikasi Laravel, manajemen server Proxmox, konfigurasi
                    jaringan, dan layanan cloud, kami siap membantu Anda membangun sistem digital yang stabil, aman, dan
                    mudah digunakan.

                    <br />

                    🔧 OZNET Systems — Jasa Pembuatan Aplikasi & Infrastruktur IT di Semarang dan Sekitarnya.
                </p>
            </div>

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $s)
                    <div class="reveal rounded-2xl border border-white/10 p-6 hover:border-brand/50 transition glass">
                        <div class="w-12 h-12 rounded-xl bg-brand/20 mb-4 flex items-center justify-center">
                            {{-- icon bisa diganti ke svg dari $s->icon --}}
                            <x-icon :name="$s->icon ?? 'monitor'" class="w-5 h-5" />
                        </div>
                        <h3 class="font-semibold text-lg">{{ $s->name }}</h3>
                        <p class="mt-2 text-white/70">{{ $s->excerpt }}</p>
                    </div>
                @empty
                    <div class="text-white/60">Belum ada layanan.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- TECH STACK -->
    <section id="tech" class="py-16 border-y border-white/10 bg-neutral-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal text-center">
                <h2 class="text-3xl font-extrabold">Teknologi</h2>
                <p class="mt-3 text-white/70">Kami memilih stack yang stabil, aman, dan mudah dirawat.</p>
            </div>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                @foreach ($stacks as $t)
                    <span
                        class="reveal px-4 py-2 rounded-full border border-white/15 bg-white/5">{{ $t->name }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PORTFOLIO (scroll-snap carousel) -->
    <section id="portfolio" class="py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold">Contoh Pekerjaan</h2>
                <p class="mt-3 text-white/70">
                    Beberapa layout dummy yang mewakili jenis proyek yang biasa kami kerjakan.
                </p>
            </div>

            <div class="relative mt-10">
                <!-- Edge Fade (optional, efek pinggir halus) -->
                <div
                    class="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-neutral-950 to-transparent">
                </div>
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-neutral-950 to-transparent">
                </div>

                <!-- Slider Container -->
                <div id="portfolioSlider"
                    class="flex gap-6 snap-x no-scrollbar overflow-x-auto scroll-smooth will-change-transform">
                    @foreach ($portfolios as $p)
                        <div
                            class="snap-center min-w-[320px] sm:min-w-[420px] rounded-2xl border border-white/10 p-5 glass
                      transition-transform duration-500 hover:-translate-y-1 hover:shadow-2xl/20">
                            @php
                                $hasThumb = $p->thumb_path && Storage::disk('public')->exists($p->thumb_path);
                                $thumbUrl = $hasThumb ? asset('storage/' . $p->thumb_path) : null;
                            @endphp

                            <div
                                class="h-48 rounded-xl overflow-hidden border border-white/10 mb-4 bg-white/5
            flex items-center justify-center">
                                @if ($thumbUrl)
                                    <img src="{{ $thumbUrl }}" alt="{{ $p->title }} thumbnail"
                                        class="w-full h-full object-cover" loading="lazy" decoding="async">
                                @else
                                    <!-- Placeholder -->
                                    <svg viewBox="0 0 24 24" class="w-10 h-10 text-white/30" fill="none"
                                        stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                        <path d="M8 13l2.5 3 3.5-5 4 6"></path>
                                        <circle cx="8" cy="8" r="1.5"></circle>
                                    </svg>
                                @endif
                            </div>

                            <div class="font-semibold">{{ $p->title }}</div>
                            <div class="text-sm text-white/70">{{ $p->summary }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>



    <!-- PROCESS -->
    <section id="process" class="py-20 border-y border-white/10 bg-neutral-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal text-center">
                <h2 class="text-3xl font-extrabold">Proses Kerja</h2>
                <p class="mt-3 text-white/70">Transparan, terukur, dan kolaboratif.</p>
            </div>

            <ol class="mt-10 grid md:grid-cols-4 gap-6">
                @forelse($process as $step)
                    <li class="reveal p-6 rounded-2xl border border-white/10 glass">
                        <div class="text-brand font-semibold">
                            {{ $step['no'] ?? '' }} • {{ $step['label'] ?? '' }}
                        </div>
                        <p class="text-white/70 mt-2">{{ $step['desc'] ?? '' }}</p>
                    </li>
                @empty
                    <li class="text-white/60">Belum ada langkah proses.</li>
                @endforelse
            </ol>
        </div>
    </section>


    <!-- CTA -->
    <section class="py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal relative overflow-hidden rounded-3xl border border-white/10 p-10 lg:p-14 glass">
                <div class="absolute -right-32 -top-24 w-72 h-72 rounded-full bg-brand/20 blur-2xl"></div>
                <div class="absolute -left-32 -bottom-24 w-72 h-72 rounded-full bg-teal-400/20 blur-2xl"></div>
                <h3 class="text-2xl md:text-3xl font-extrabold">Siap memulai proyek?</h3>
                <p class="mt-2 text-white/70 max-w-2xl">Ceritakan masalah & target Anda. Kami bantu rancang solusi
                    paling efisien dari sisi biaya & waktu.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="#contact"
                        class="inline-flex items-center gap-2 rounded-xl bg-brand px-6 py-3 font-semibold shadow-glow">Hubungi
                        Kami</a>
                    <a href="#portfolio"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/15 px-6 py-3 font-semibold">Lihat
                        Portfolio</a>
                </div>
            </div>
        </div>
    </section>


    <!-- ABOUT US -->
    <section id="about" class="py-20 border-t border-white/10 bg-neutral-900/30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold">Tentang Kami</h2>
                <p class="mt-3 text-white/70 max-w-2xl mx-auto">
                    {{ $site['brand'] ?? 'OZNET Systems' }} adalah tim profesional di bidang
                    <span class="text-brand">pengembangan aplikasi</span> dan
                    <span class="text-brand">infrastruktur jaringan</span>.
                    Kami membantu bisnis & institusi dengan solusi end-to-end.
                </p>
            </div>

            <div class="mt-12 grid md:grid-cols-3 gap-6">
                {{-- Visi --}}
                <div class="reveal glass p-6 rounded-2xl border border-white/10">
                    <h3 class="font-semibold text-lg text-brand">Visi</h3>
                    <p class="mt-2 text-white/70 text-sm">
                        {{ $about['visi'] ?? 'Visi belum diisi.' }}
                    </p>
                </div>

                {{-- Misi --}}
                <div class="reveal glass p-6 rounded-2xl border border-white/10">
                    <h3 class="font-semibold text-lg text-brand">Misi</h3>
                    <ul class="mt-2 text-white/70 text-sm list-disc list-inside space-y-1 text-left">
                        @forelse(($about['misi'] ?? []) as $m)
                            <li>{{ $m }}</li>
                        @empty
                            <li>Misi belum diisi.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Nilai --}}
                <div class="reveal glass p-6 rounded-2xl border border-white/10">
                    <h3 class="font-semibold text-lg text-brand">Nilai Kami</h3>
                    <p class="mt-2 text-white/70 text-sm">
                        {{ $about['nilai'] ?? 'Nilai/nilai perusahaan belum diisi.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>



    <!-- CONTACT -->

    <section id="contact" class="py-20 border-t border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-start">
            <div class="reveal">
                <h2 class="text-3xl font-extrabold">{{ $contactTitle ?? 'Kontak' }}</h2>
                <p class="mt-3 text-white/70">
                    {{ $contactSub ?? 'Kami responsif via WhatsApp & email. Sertakan gambaran singkat kebutuhan Anda.' }}
                </p>

                <ul class="mt-6 space-y-3 text-white/80">
                    <li class="flex items-center gap-3">
                        {{-- WhatsApp Icon --}}
                        {!! '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1 1.9-5.4" /><path d="M22 4 12 14.01l-3-3" /></svg>' !!}
                        <span>
                            <strong>WhatsApp:</strong>
                            <a class="text-brand hover:underline"
                                href="https://wa.me/{{ preg_replace('/\D+/', '', $wa ?? '+6289637498586') }}"
                                target="_blank" rel="noopener">
                                {{ $wa ?? '+6289637498586' }}
                            </a>
                        </span>
                    </li>

                    <li class="flex items-center gap-3">
                        {{-- Email Icon --}}
                        {!! '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> <path d="M4 4h16v16H4z" /><path d="M22 6 12 13 2 6" /></svg>' !!}
                        <span>
                            <strong>Email:</strong>
                            <a class="text-brand hover:underline"
                                href="mailto:{{ $email ?? 'stylus.smg@gmail.com' }}">
                                {{ $email ?? 'stylus.smg@gmail.com' }}
                            </a>
                        </span>
                    </li>
                </ul>
            </div>

            {{-- FORM KONTAK --}}
            <form method="POST" action="{{ route('contact.store') }}" class="reveal space-y-4" novalidate>
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <input name="name" value="{{ old('name') }}"
                        class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand/60"
                        placeholder="Nama" required />
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand/60"
                        placeholder="Email" required />
                </div>

                <input name="company" value="{{ old('company') }}"
                    class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand/60"
                    placeholder="Perusahaan (opsional)" />

                <textarea rows="5" name="message"
                    class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand/60"
                    placeholder="Ceritakan kebutuhan Anda…" required>{{ old('message') }}</textarea>

                @if ($errors->any())
                    <div class="text-xs text-red-400">
                        @foreach ($errors->all() as $err)
                            <div>• {{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('ok'))
                    <p class="text-xs text-emerald-400">{{ session('ok') }}</p>
                @endif

                <button type="submit" class="rounded-xl bg-brand px-6 py-3 font-semibold shadow-glow">Kirim</button>
            </form>
        </div>
    </section>


    <footer class="py-12 border-t border-white/10 text-white/80">
        @php
            $company = $site['company'] ?? 'OZNET Systems';
            $address = $site['address'] ?? 'Doplang Krajan 01/03, Kec. Bawen, Kab. Semarang';
            $social = $site['social'] ?? [];
            // kalau ada menu khusus footer pakai itu; kalau tidak, fallback ke menu utama
            $footerMenu = $menu?->items ?? collect();
        @endphp

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 items-start">
                {{-- Kolom 1: Nama perusahaan & alamat --}}
                <div>
                    <div class="text-lg font-semibold text-white">{{ $company }}</div>
                    <p class="mt-2 text-sm text-white/70 leading-relaxed">{{ $address }}</p>
                </div>

                {{-- Kolom 2: Menu link --}}
                <nav aria-label="Footer Menu">
                    <ul class="grid grid-cols-2 gap-2 text-sm">
                        @forelse($footerMenu as $it)
                            <li>
                                <a href="{{ $it->url }}" class="hover:text-brand">{{ $it->label }}</a>
                            </li>
                        @empty
                            <li><a href="#services" class="hover:text-brand">Layanan</a></li>
                            <li><a href="#tech" class="hover:text-brand">Teknologi</a></li>
                            <li><a href="#portfolio" class="hover:text-brand">Portfolio</a></li>
                            <li><a href="#process" class="hover:text-brand">Proses</a></li>
                            <li><a href="#about" class="hover:text-brand">About</a></li>
                            <li><a href="#contact" class="hover:text-brand">Kontak</a></li>
                        @endforelse
                    </ul>
                </nav>

                {{-- Kolom 3: Sosial media --}}
                <div class="md:justify-self-end">
                    <div class="text-sm mb-3">Ikuti kami:</div>
                    <div class="flex items-center gap-4">
                        @if (!empty($social['facebook']))
                            <a href="{{ $social['facebook'] }}" target="_blank" rel="noopener"
                                aria-label="Facebook"
                                class="p-2 rounded-lg border border-white/10 hover:border-brand/60 transition">
                                {{-- FB --}}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M13.5 9H16V6h-2.5C11.57 6 10 7.57 10 9.5V11H8v3h2v7h3v-7h2.1l.9-3H13v-1.5c0-.28.22-.5.5-.5Z" />
                                </svg>
                            </a>
                        @endif
                        @if (!empty($social['instagram']))
                            <a href="{{ $social['instagram'] }}" target="_blank" rel="noopener"
                                aria-label="Instagram"
                                class="p-2 rounded-lg border border-white/10 hover:border-brand/60 transition">
                                {{-- IG --}}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm5 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm6.5-.75a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5Z" />
                                    <circle cx="12" cy="12" r="3.5" />
                                </svg>
                            </a>
                        @endif
                        @if (!empty($social['linkedin']))
                            <a href="{{ $social['linkedin'] }}" target="_blank" rel="noopener"
                                aria-label="LinkedIn"
                                class="p-2 rounded-lg border border-white/10 hover:border-brand/60 transition">
                                {{-- IN --}}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M6.94 6.5A2.44 2.44 0 1 1 6.94 1a2.44 2.44 0 0 1 0 4.88ZM1.5 22.5h4.9V8.62H1.5V22.5Zm7.1 0h4.9v-7.13c0-1.7.32-3.35 2.43-3.35 2.08 0 2.11 1.95 2.11 3.45V22.5h4.9v-8.1c0-4.02-.86-7.12-5.51-7.12-2.24 0-3.74 1.23-4.35 2.4h-.06V8.62H8.6V22.5Z" />
                                </svg>
                            </a>
                        @endif
                        @if (!empty($social['youtube']))
                            <a href="{{ $social['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"
                                class="p-2 rounded-lg border border-white/10 hover:border-brand/60 transition">
                                {{-- YT --}}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M23 7.5s-.2-1.6-.8-2.3c-.8-.9-1.7-.9-2.1-1C17.4 4 12 4 12 4h0s-5.4 0-8.1.2c-.4.1-1.3.1-2.1 1C1.2 5.9 1 7.5 1 7.5S.8 9.3.8 11v2c0 1.7.2 3.5.2 3.5s.2 1.6.8 2.3c.8.9 1.9.9 2.4 1 1.8.2 7.8.2 7.8.2s5.4 0 8.1-.2c.4-.1 1.3-.1 2.1-1 .6-.7.8-2.3.8-2.3s.2-1.7.2-3.5v-2c0-1.7-.2-3.5-.2-3.5ZM9.75 14.5V8.9l5.6 2.8-5.6 2.8Z" />
                                </svg>
                            </a>
                        @endif

                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/10 text-center text-white/60">
                © <span id="y"></span> {{ $company }}
            </div>
        </div>
    </footer>


    <!-- JS: reveal on scroll & menu -->
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) e.target.classList.add('show');
            })
        }, {
            threshold: 0.12
        });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

        document.getElementById('y').textContent = new Date().getFullYear();
    </script>


    <script>
        (function() {
            const slider = document.getElementById('portfolioSlider');
            if (!slider) return;

            // Pastikan container benar2 bisa overflow horizontal
            slider.style.overflowX = 'auto';

            // 1) Duplikasi sampai lebar konten cukup untuk loop
            const ensureEnoughWidth = () => {
                const minFactor = 2.2; // target: konten >= 2.2x lebar viewport slider
                const baseItems = Array.from(slider.children).map(n => n.cloneNode(true));
                if (baseItems.length === 0) return false;

                // kalau awalnya sudah tak cukup, gandakan bertahap
                let safety = 0;
                while ((slider.scrollWidth / slider.clientWidth) < minFactor && safety < 10) {
                    baseItems.forEach(node => slider.appendChild(node.cloneNode(true)));
                    safety++;
                }
                // Tambah sekali lagi agar reset mulus
                baseItems.forEach(node => slider.appendChild(node.cloneNode(true)));
                return true;
            };

            if (!ensureEnoughWidth()) return;

            // 2) Auto scroll
            let speed = 0.8; // px per frame (naikkan jika terlalu lambat)
            let paused = false;
            let auto = true; // mode auto jalan
            let raf;

            // Toggle scroll-snap biar tak menghambat gerak auto
            const disableSnap = () => slider.classList.add('snap-none');
            const enableSnap = () => slider.classList.remove('snap-none');

            // Tambahkan util class snap-none via JS (tanpa Tailwind)
            const style = document.createElement('style');
            style.textContent = `.snap-none { scroll-snap-type: none !important; }`;
            document.head.appendChild(style);

            const half = () => Math.floor(slider.scrollWidth / 2);

            function tick() {
                if (!paused && auto) {
                    disableSnap();
                    slider.scrollLeft += speed;
                    if (slider.scrollLeft >= half()) {
                        slider.scrollLeft -= half();
                    }
                }
                raf = requestAnimationFrame(tick);
            }

            // 3) Interaksi user
            const pause = () => {
                paused = true;
            };
            const resume = () => {
                paused = false;
            };

            slider.addEventListener('mouseenter', pause);
            slider.addEventListener('mouseleave', resume);

            slider.addEventListener('touchstart', () => {
                paused = true;
                auto = false; // user mengambil alih → aktifkan snap agar rapi
                enableSnap();
            }, {
                passive: true
            });

            slider.addEventListener('touchend', () => {
                // beri jeda kecil agar snap selesai, lalu lanjut auto lagi
                setTimeout(() => {
                    auto = true;
                    paused = false;
                }, 3000);
            });

            slider.addEventListener('wheel', () => {
                // jika user scroll manual dengan mouse, aktifkan snap sementara
                auto = false;
                enableSnap();
                clearTimeout(slider._wheelTimer);
                slider._wheelTimer = setTimeout(() => {
                    auto = true;
                }, 3000);
            }, {
                passive: true
            });

            // 4) Responsif
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    slider.scrollLeft = slider.scrollLeft % half();
                }, 150);
            });

            // 5) Start
            tick();
        })();
    </script>




</body>

</html>
