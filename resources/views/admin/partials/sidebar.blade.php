<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('super.dashboard') }}" class="sidebar-brand">
            QR<span> Scan</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="sidebar-body">
        <ul class="nav">
            {{-- ================= MAIN ================= --}}
            <li class="nav-item nav-category">Main</li>
            @can('dashboard.view')
                <li class="nav-item">
                    <a href="{{ route('super.dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Dashboard</span>
                    </a>
                </li>
            @endcan

            {{-- ================= ACCESS CONTROL ================= --}}
            @canany(['user.menu', 'role.menu', 'permission.menu'])
                <li class="nav-item nav-category">Access Control</li>
            @endcanany

            {{-- Users --}}
            @can('user.menu')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-users" role="button" aria-expanded="false"
                        aria-controls="menu-users">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Users</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.user.*') ? 'show' : '' }}" id="menu-users">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.user.index') }}"
                                    class="nav-link {{ request()->routeIs('super.user.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- Roles --}}
            @can('role.menu')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-roles" role="button" aria-expanded="false"
                        aria-controls="menu-roles">
                        <i class="link-icon" data-feather="shield"></i>
                        <span class="link-title">Roles</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.role.*') ? 'show' : '' }}" id="menu-roles">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.roles.index') }}"
                                    class="nav-link {{ request()->routeIs('super.role.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- Permissions --}}
            @can('permission.menu')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-permissions" role="button"
                        aria-expanded="false" aria-controls="menu-permissions">
                        <i class="link-icon" data-feather="key"></i>
                        <span class="link-title">Permissions</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.permission.*') ? 'show' : '' }}"
                        id="menu-permissions">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.permissions.index') }}"
                                    class="nav-link {{ request()->routeIs('super.permission.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan


      {{-- ================= MASTER ================= --}}
            @canany(['outlets.view', 'tickets.view'])
                <li class="nav-item nav-category">MASTER</li>
            @endcanany


              @can('outlets.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-outlets" role="button"
                        aria-expanded="false" aria-controls="menu-outlets">
                        <i class="link-icon" data-feather="hard-drive"></i>
                        <span class="link-title">Outlets</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.outlets.*') ? 'show' : '' }}"
                        id="menu-outlets">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.outlets.index') }}"
                                    class="nav-link {{ request()->routeIs('super.outlets.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan


            {{-- ================= SETTINGS ================= --}}
            @canany(['categories.view', 'tickets.view'])
                <li class="nav-item nav-category">TICKET MANAGEMENT</li>
            @endcanany


            @can('categories.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-categories" role="button"
                        aria-expanded="false" aria-controls="menu-categories">
                        <i class="link-icon" data-feather="hard-drive"></i>
                        <span class="link-title">Categories</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.categories.*') ? 'show' : '' }}"
                        id="menu-categories">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.categories.index') }}"
                                    class="nav-link {{ request()->routeIs('super.categories.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan



            @can('tickets.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-tickets" role="button" aria-expanded="false"
                        aria-controls="menu-tickets">
                        <i class="link-icon" data-feather="list"></i>
                        <span class="link-title">Tickets</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.tickets.*') ? 'show' : '' }}" id="menu-tickets">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.tickets.index') }}"
                                    class="nav-link {{ request()->routeIs('super.tickets.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan





            {{-- ================= SETTINGS ================= --}}
            {{-- @canany(['pakets.view', 'servers.view', 'pelanggans.view', 'bulans.view'])
                <li class="nav-item nav-category">Settings</li>
            @endcanany

            @can('pakets.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-pakets" role="button" aria-expanded="false"
                        aria-controls="menu-pakets">
                        <i class="link-icon" data-feather="list"></i>
                        <span class="link-title">Pakets</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.pakets.*') ? 'show' : '' }}" id="menu-pakets">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.pakets.index') }}"
                                    class="nav-link {{ request()->routeIs('super.pakets.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            @can('servers.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-servers" role="button" aria-expanded="false"
                        aria-controls="menu-servers">
                        <i class="link-icon" data-feather="hard-drive"></i>
                        <span class="link-title">Servers</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.servers.*') ? 'show' : '' }}" id="menu-servers">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.servers.index') }}"
                                    class="nav-link {{ request()->routeIs('super.servers.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            @can('pelanggans.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-pelanggans" role="button"
                        aria-expanded="false" aria-controls="menu-pelanggans">
                        <i class="link-icon" data-feather="user"></i>
                        <span class="link-title">Pelanggans</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.pelanggans.*') ? 'show' : '' }}"
                        id="menu-pelanggans">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.pelanggans.index') }}"
                                    class="nav-link {{ request()->routeIs('super.pelanggans.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            @can('bulans.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-bulans" role="button"
                        aria-expanded="false" aria-controls="menu-bulans">
                        <i class="link-icon" data-feather="calendar"></i>
                        <span class="link-title">Bulans</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.bulans.*') ? 'show' : '' }}" id="menu-bulans">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.bulans.index') }}"
                                    class="nav-link {{ request()->routeIs('super.bulans.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan --}}

            {{-- ================= PAYMENT ================= --}}
            {{-- @canany(['tagihans.view', 'payments.view'])
                <li class="nav-item nav-category">Payment</li>
            @endcanany

            @can('tagihans.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-tagihans" role="button"
                        aria-expanded="{{ request()->routeIs('super.tagihans.*') ? 'true' : 'false' }}"
                        aria-controls="menu-tagihans">
                        <i class="link-icon" data-feather="credit-card"></i>
                        <span class="link-title">Tagihans</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('super.tagihans.*') ? 'show' : '' }}" id="menu-tagihans">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.tagihans.index') }}"
                                    class="nav-link {{ request()->routeIs('super.tagihans.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('super.tagihans.unpaid') }}"
                                    class="nav-link {{ request()->routeIs('super.tagihans.unpaid') ? 'active' : '' }}">
                                    <i data-feather="alert-circle" class="icon-sm me-1"></i>
                                    Belum Lunas
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            @can('payments.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-payments" role="button"
                        aria-expanded="{{ request()->routeIs('super.payments.*') ? 'true' : 'false' }}"
                        aria-controls="menu-payments">
                        <i class="link-icon" data-feather="dollar-sign"></i>
                        <span class="link-title">Payments</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('super.payments.*') ? 'show' : '' }}" id="menu-payments">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.payments.index') }}"
                                    class="nav-link {{ request()->routeIs('super.payments.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('super.payments.lookup') }}"
                                    class="nav-link {{ request()->routeIs('super.payments.lookup') ? 'active' : '' }}">
                                    <i data-feather="search" class="icon-sm"></i>
                                    <span>Pembayaran</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan --}}


            {{-- ================= PAYMENT ================= --}}
            {{-- @canany(['monitoring.topology'])
                <li class="nav-item nav-category">monitoring</li>
            @endcanany

            @can('monitoring.topology')
                <li class="nav-item">
                    <a href="{{ route('super.monitoring.index') }}"
                        class="nav-link {{ request()->routeIs('super.monitoring.*') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="map-pin"></i>
                        <span class="link-title">Network Topology</span>
                    </a>
                </li>
            @endcan --}}


        </ul>
    </div>
</nav>
