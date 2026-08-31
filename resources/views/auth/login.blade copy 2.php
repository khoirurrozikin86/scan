<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords" content="nobleui, bootstrap, admin, dashboard, template, responsive, ui">

    <title>Login - {{ config('app.name') }}</title>

    <!-- Fonts -->
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
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="card shadow">
                            <div class="row">
                                <div class="col-md-4 pe-md-0">
                                    <div class="auth-side-wrapper"></div>
                                </div>

                                <div class="col-md-8 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="{{ url('/') }}" class="noble-ui-logo d-block mb-2">
                                            {{ config('app.name') }}
                                        </a>
                                        <h5 class="text-muted fw-normal mb-4">
                                            Welcome back! Log in to your account.
                                        </h5>

                                        {{-- Session Status --}}
                                        @if (session('status'))
                                            <div class="alert alert-success">{{ session('status') }}</div>
                                        @endif

                                        {{-- Login Form --}}
                                        <form class="forms-sample" method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email address</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email" name="email" value="{{ old('email') }}"
                                                    placeholder="Email" required autofocus autocomplete="username">

                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" autocomplete="current-password"
                                                    placeholder="Password" required>

                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="remember_me"
                                                    name="remember">
                                                <label class="form-check-label" for="remember_me">
                                                    Remember me
                                                </label>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between">
                                                <button type="submit" class="btn btn-primary text-white">
                                                    Log in
                                                </button>

                                                {{-- @if (Route::has('password.request'))
                                                    <a href="{{ route('password.request') }}" class="text-muted">
                                                        Forgot your password?
                                                    </a>
                                                @endif --}}
                                            </div>

                                            {{-- @if (Route::has('register'))
                                                <a href="{{ route('register') }}" class="d-block mt-3 text-muted">
                                                    Not a user? Sign up
                                                </a>
                                            @endif --}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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
    <!-- endinject -->
</body>

</html>
