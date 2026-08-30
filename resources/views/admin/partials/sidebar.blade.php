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
            @canany(['outlets.view', 'ticket-qrcodes.view'])
                <li class="nav-item nav-category">MASTER</li>
            @endcanany


            @can('outlets.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-outlets" role="button" aria-expanded="false"
                        aria-controls="menu-outlets">
                        <i class="link-icon" data-feather="hard-drive"></i>
                        <span class="link-title">Outlets</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.outlets.*') ? 'show' : '' }}" id="menu-outlets">
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


            @can('ticket-qrcode.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-ticket-qrcode" role="button"
                        aria-expanded="false" aria-controls="menu-ticket-qrcode">
                        <i class="link-icon" data-feather="hard-drive"></i>
                        <span class="link-title">Ticket Qrcode</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.ticket-qrcode.*') ? 'show' : '' }}"
                        id="menu-ticket-qrcode">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.ticket-qrcode.index') }}"
                                    class="nav-link {{ request()->routeIs('super.ticket-qrcode.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan


            @can('user-outlets.view')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-user-outlets" role="button"
                        aria-expanded="false" aria-controls="menu-user-outlets">
                        <i class="link-icon" data-feather="hard-drive"></i>
                        <span class="link-title">User Outlet</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('super.user-outlets.*') ? 'show' : '' }}"
                        id="menu-user-outlets">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('super.user-outlets.index') }}"
                                    class="nav-link {{ request()->routeIs('super.user-outlets.index') ? 'active' : '' }}">
                                    Show
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan



            {{-- ================= SCAN MANAGEMENT ================= --}}
            @canany(['scan-records.view', 'scan-records.create'])
                <li class="nav-item nav-category">
                    SCAN MANAGEMENT
                </li>
            @endcanany


            {{-- ====================================================== --}}
            {{-- SCAN --}}
            {{-- ====================================================== --}}

            @can('scan-records.create')
                <li class="nav-item">

                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-scan" role="button"
                        aria-expanded="{{ request()->routeIs('super.scan-records.camera', 'super.scan-records.scanner') ? 'true' : 'false' }}"
                        aria-controls="menu-scan">

                        <i class="link-icon" data-feather="maximize"></i>

                        <span class="link-title">
                            Scan
                        </span>

                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>


                    <div class="collapse
            {{ request()->routeIs('super.scan-records.camera', 'super.scan-records.scanner') ? 'show' : '' }}"
                        id="menu-scan">

                        <ul class="nav sub-menu">

                            {{-- CAMERA --}}

                            <li class="nav-item">

                                <a href="{{ route('super.scan-records.camera') }}"
                                    class="nav-link
                        {{ request()->routeIs('super.scan-records.camera') ? 'active' : '' }}">

                                    Camera Scanner

                                </a>

                            </li>


                            {{-- BARCODE --}}

                            <li class="nav-item">

                                <a href="{{ route('super.scan-records.scanner') }}"
                                    class="nav-link
                        {{ request()->routeIs('super.scan-records.scanner') ? 'active' : '' }}">

                                    Barcode Scanner

                                </a>

                            </li>

                        </ul>

                    </div>

                </li>
            @endcan


            {{-- ====================================================== --}}
            {{-- SCAN RECORD / REPORT --}}
            {{-- ====================================================== --}}

            @can('scan-records.view')
                <li class="nav-item">

                    <a class="nav-link" data-bs-toggle="collapse" href="#menu-scan-report" role="button"
                        aria-expanded="{{ request()->routeIs('super.scan-records.index') ? 'true' : 'false' }}"
                        aria-controls="menu-scan-report">

                        <i class="link-icon" data-feather="file-text"></i>

                        <span class="link-title">
                            Scan Record
                        </span>

                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>


                    <div class="collapse
            {{ request()->routeIs('super.scan-records.index') ? 'show' : '' }}"
                        id="menu-scan-report">

                        <ul class="nav sub-menu">

                            <li class="nav-item">

                                <a href="{{ route('super.scan-records.index') }}"
                                    class="nav-link
                        {{ request()->routeIs('super.scan-records.index') ? 'active' : '' }}">

                                    Show

                                </a>

                            </li>

                        </ul>

                    </div>

                </li>
            @endcan



        </ul>

    </div>



</nav>
