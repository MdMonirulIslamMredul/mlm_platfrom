<!-- Frontend Mobile-Optimized Top Navbar Header -->
<header class="app-header bg-dark-emerald text-white sticky-top py-2 px-3 shadow-sm">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <!-- Left: Hamburger Offcanvas Toggle & Brand -->
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-emerald-circle p-0 d-flex align-items-center justify-content-center" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar"
                style="width: 42px; height: 42px; background: rgba(255,255,255,0.15); border-radius: 50%;">
                <i class="bi bi-list fs-3 text-white"></i>
            </button>

            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none text-white ms-1">
                <div class="brand-logo-icon d-flex align-items-center justify-content-center bg-primary text-white rounded-circle"
                    style="width: 34px; height: 34px;">
                    <i class="bi bi-globe2 fs-5"></i>
                </div>
                <span class="fw-bold fs-5 tracking-wide text-white">{{ $generalSettings->site_name ?? 'Mobile' }}</span>
            </a>
        </div>

        <!-- Right: Actions (Whatsapp Group, Profile Avatar & Logout) -->
        <div class="d-flex align-items-center gap-2">
            <!-- Whatsapp Group Button -->
            <a href="https://chat.whatsapp.com" target="_blank"
                class="btn btn-emerald-action btn-sm rounded-pill text-white d-flex align-items-center gap-1 px-2.5 py-1"
                style="background: rgba(255,255,255,0.15); font-size: 0.8rem;">
                <i class="bi bi-whatsapp text-success fs-6"></i>
                <span class="d-none d-sm-inline">Whatsapp</span>
            </a>

            @auth
                <!-- User Profile Avatar -->
                <a href="{{ route('user.profile') }}" class="text-decoration-none" title="My Profile">
                    <div class="avatar-sm rounded-circle bg-warning p-1 d-flex align-items-center justify-content-center"
                        style="width: 38px; height: 38px;">
                        <i class="bi bi-person-fill text-dark fs-5"></i>
                    </div>
                </a>

                <!-- Logout Button -->
                <form action="{{ url('/logout') }}" method="POST" class="d-inline mb-0">
                    @csrf
                    <button type="submit"
                        class="btn btn-danger btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;" title="Logout">
                        <i class="bi bi-power fs-6"></i>
                    </button>
                </form>
            @else
                <a href="{{ url('/login') }}" class="btn btn-light btn-sm rounded-pill px-3">Login</a>
            @endauth
        </div>
    </div>
</header>

<!-- Offcanvas Mobile Sidebar Menu -->
<div class="offcanvas offcanvas-start bg-dark-emerald text-white" tabindex="-1" id="appSidebar"
    aria-labelledby="appSidebarLabel">
    <div class="offcanvas-header border-bottom border-light border-opacity-10">
        <h5 class="offcanvas-title fw-bold" id="appSidebarLabel"><i class="bi bi-globe2 text-primary me-2"></i>
            {{ $generalSettings->site_name ?? 'GMS-WORLD' }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="p-3 bg-black bg-opacity-20 mb-2">
            @auth
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar bg-warning rounded-circle p-2 text-dark fs-4">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-white">{{ Auth::user()->name ?? 'User' }}</h6>
                        <small class="text-light opacity-75">{{ Auth::user()->email }}</small>
                    </div>
                </div>
            @else
                <p class="mb-0 text-white">Welcome Guest! Please log in.</p>
            @endauth
        </div>

        <div class="list-group list-group-flush">
            <a href="{{ route('dashboard') }}"
                class="list-group-item list-group-item-action bg-transparent text-white border-0 py-3 px-4">
                <i class="bi bi-house-door-fill me-3 text-emerald-accent"></i> Home
            </a>
            <a href="{{ route('user.packages') }}"
                class="list-group-item list-group-item-action bg-transparent text-white border-0 py-3 px-4">
                <i class="bi bi-box-seam-fill me-3 text-warning"></i> Packages & Products
            </a>
            <a href="{{ route('user.team') }}"
                class="list-group-item list-group-item-action bg-transparent text-white border-0 py-3 px-4">
                <i class="bi bi-people-fill me-3 text-info"></i> My Team
            </a>
            <a href="{{ route('user.history') }}"
                class="list-group-item list-group-item-action bg-transparent text-white border-0 py-3 px-4">
                <i class="bi bi-clock-history me-3 text-success"></i> Account History
            </a>
            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin'], true))
                <a href="{{ route('admin.dashboard') }}"
                    class="list-group-item list-group-item-action bg-transparent text-warning border-0 py-3 px-4 fw-bold">
                    <i class="bi bi-speedometer2 me-3"></i> Admin Dashboard
                </a>
            @endif
        </div>
    </div>
</div>