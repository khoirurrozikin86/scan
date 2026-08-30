{{-- resources/views/partials/_navbar.blade.php --}}
<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>

    <div class="navbar-content">
        {{-- Search --}}
        {{-- <form class="search-form" action="" method="GET">
            <div class="input-group">
                <div class="input-group-text">
                    <i data-feather="search"></i>
                </div>
                <input type="text" name="q" class="form-control" id="navbarForm" placeholder="Search here..."
                    value="{{ request('q') }}">
            </div>
        </form> --}}

        <ul class="navbar-nav">
            {{-- Apps dropdown (static contoh) --}}
            {{-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="grid"></i>
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="appsDropdown">
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                        <p class="mb-0 fw-bold">Web Apps</p>
                        <a href="javascript:;" class="text-muted">Edit</a>
                    </div>
                    <div class="row g-0 p-1">
                        <div class="col-3 text-center">
                            <a href=""
                                class="dropdown-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70">
                                <i data-feather="message-square" class="icon-lg mb-1"></i>
                                <p class="tx-12">Chat</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href=""
                                class="dropdown-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70">
                                <i data-feather="calendar" class="icon-lg mb-1"></i>
                                <p class="tx-12">Calendar</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href=""
                                class="dropdown-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70">
                                <i data-feather="mail" class="icon-lg mb-1"></i>
                                <p class="tx-12">Email</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href=""
                                class="dropdown-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70">
                                <i data-feather="instagram" class="icon-lg mb-1"></i>
                                <p class="tx-12">Profile</p>
                            </a>
                        </div>
                    </div>
                    <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                        <a href="">View all</a>
                    </div>
                </div>
            </li> --}}

            @auth
                @php
                    /** @var \App\Models\User $user */
                    $user = auth()->user();

                    // Avatar fallback: pakai kolom profile_photo_url (Jetstream) atau gravatar/ui-avatars
                    $avatar =
                        $user->profile_photo_url ??
                        'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=80&d=identicon';
                @endphp


                {{-- Profile --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Profile menu">
                        <img class="wd-30 ht-30 rounded-circle" src="{{ $avatar }}" alt="profile">
                    </a>
                    <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                        <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                            <div class="mb-3">
                                <img class="wd-80 ht-80 rounded-circle" src="{{ $avatar }}" alt="avatar">
                            </div>
                            <div class="text-center">
                                <p class="tx-16 fw-bolder mb-0">{{ $user->name }}</p>
                                <p class="tx-12 text-muted mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                        <ul class="list-unstyled p-1">
                            {{-- <li class="dropdown-item py-2">
                                <a href="" class="text-body ms-0">
                                    <i class="me-2 icon-md" data-feather="user"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                            <li class="dropdown-item py-2">
                                <a href="{{ route('profile.edit') }}" class="text-body ms-0">
                                    <i class="me-2 icon-md" data-feather="edit-2"></i>
                                    <span>Edit Profile</span>
                                </a>
                            </li> --}}
                            @can('impersonate')
                                <li class="dropdown-item py-2">
                                    <a href="{{ route('users.switch') }}" class="text-body ms-0">
                                        <i class="me-2 icon-md" data-feather="repeat"></i>
                                        <span>Switch User</span>
                                    </a>
                                </li>
                            @endcan
                            <li class="dropdown-item py-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-body ms-0 p-0">
                                        <i class="me-2 icon-md" data-feather="log-out"></i>
                                        <span>Log Out</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            @endauth

            @guest
                {{-- Jika belum login, tampilkan tombol login/register --}}
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">
                        <i data-feather="log-in" class="me-1"></i> Login
                    </a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">
                            <i data-feather="user-plus" class="me-1"></i> Register
                        </a>
                    </li>
                @endif
            @endguest
        </ul>
    </div>
</nav>

{{-- Pastikan Feather di-init setelah elemen tersedia --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.feather) feather.replace();
        });
    </script>
@endpush
