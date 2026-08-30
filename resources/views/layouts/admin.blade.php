<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>

    {{-- Fonts (opsional) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    {{-- core:css --}}
    <link rel="stylesheet" href="{{ asset('vendor/nobleui/assets/vendors/core/core.css') }}">
    {{-- Plugin css (contoh: DataTables Bootstrap 5) --}}

    <!-- Plugin css for this page -->
    <link rel="stylesheet"
        href="{{ asset('vendor/nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
    <!-- End plugin css for this page -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('vendor/nobleui/assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <!-- End plugin css for this page -->

    @stack('vendor-styles')
    {{-- inject:css --}}
    <link rel="stylesheet" href="{{ asset('vendor/nobleui/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/nobleui/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    {{-- Layout styles --}}
    <link rel="stylesheet" href="{{ asset('vendor/nobleui/assets/css/demo1/style.css') }}">

    <link rel="shortcut icon" href="{{ asset('vendor/nobleui/assets/images/favicon.png') }}" />
    @stack('styles')
</head>

<body>
    <div class="main-wrapper">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        <nav class="settings-sidebar">@includeWhen(View::exists('admin.partials.settings'), 'admin.partials.settings')</nav>

        <div class="page-wrapper">
            {{-- Navbar --}}
            @include('admin.partials.navbar')

            <div class="page-content">
                @yield('breadcrumb')
                @yield('content')
            </div>

            {{-- Footer --}}
            @include('admin.partials.footer')
        </div>
    </div>

    {{-- core:js --}}
    <script src="{{ asset('vendor/nobleui/assets/vendors/core/core.js') }}"></script>
    {{-- Plugins per halaman --}}
    @stack('vendor-scripts')
    {{-- inject:js --}}

    <!-- Plugin js for this page -->
    <script src="{{ asset('vendor/nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendor/nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>


    <!-- Plugin js for this page -->
    <script src="{{ asset('vendor/nobleui/assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- End plugin js for this page -->


    <!-- Custom js for this page -->
    <script src="{{ asset('vendor/nobleui/assets/js/sweet-alert.js') }}"></script>
    <!-- End custom js for this page -->


    <script src="{{ asset('vendor/nobleui/assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('vendor/nobleui/assets/js/template.js') }}"></script>
    {{-- Custom per halaman --}}
    @stack('scripts')



    <script>
        (function exactActiveSidebar() {
            function normalizePath(u) {
                try {
                    const url = new URL(u, location.origin);
                    return (url.pathname || '/').replace(/\/+$/, '') || '/';
                } catch {
                    return '/';
                }
            }

            function run() {
                const current = normalizePath(location.href);

                // Hanya di sidebar
                const $sidebar = $('.sidebar, .sidebar-body, nav.sidebar'); // sesuaikan wrapper sidebarmu
                if (!$sidebar.length) return;

                // Bersihkan state auto-active bawaan tema
                $sidebar.find('.nav-link.active').removeClass('active');
                $sidebar.find('.nav-item.active').removeClass('active');
                $sidebar.find('.collapse.show').removeClass('show');

                // Set active berdasar exact path
                $sidebar.find('.nav-link[href]').each(function() {
                    const $a = $(this);
                    const hrefPath = normalizePath($a.attr('href'));
                    if (hrefPath === current) {
                        $a.addClass('active');
                        $a.closest('.collapse').addClass('show');
                        $a.parents('.nav-item').last().addClass('active');
                    }
                });

                // Opsional: kalau mau grup terbuka saat di area /super/xxx/*
                // tinggal tambahkan aturan startsWith di sini per grup bila perlu.
            }

            // Jalankan setelah vendor inisialisasi
            if (document.readyState === 'complete') setTimeout(run, 0);
            else window.addEventListener('load', () => setTimeout(run, 0));

            // Ulangi bila SPA/Livewire/Turbo
            document.addEventListener('turbo:load', run);
            document.addEventListener('pjax:end', run);
            document.addEventListener('livewire:navigated', run);
        })();
    </script>

</body>

</html>
