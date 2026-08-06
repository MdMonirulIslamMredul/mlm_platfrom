<!-- Frontend Mobile Bottom Navigation Bar -->
<div class="mobile-bottom-nav fixed-bottom bg-white border-top shadow-lg d-block">
    <div class="container position-relative">
        <div class="row text-center py-2 align-items-center">

            <!-- Tab 1: Home -->
            <div class="col px-1">
                <a href="{{ route('dashboard') }}"
                    class="nav-item-link text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('dashboard') ? 'active-tab' : 'text-muted' }}">
                    <i class="bi bi-house-door-fill fs-4"></i>
                    <span class="nav-label" style="font-size: 0.72rem; font-weight: 600;">Home</span>
                </a>
            </div>

            <!-- Tab 2: Wallet / Packages -->
            <div class="col px-1">
                <a href="{{ route('user.packages') }}"
                    class="nav-item-link text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('user.packages') ? 'active-tab' : 'text-muted' }}">
                    <i class="bi bi-wallet2 fs-4"></i>
                    <span class="nav-label" style="font-size: 0.72rem; font-weight: 600;">Package</span>
                </a>
            </div>

            <!-- Tab 3: Center Emblem (Floating M Button) -->
            <div class="col px-1 position-relative">
                <div class="floating-center-btn-wrapper">
                    <a href="{{ route('dashboard') }}"
                        class="floating-center-btn d-flex align-items-center justify-content-center text-white text-decoration-none shadow-lg">
                        <span class="fw-black fs-4">M</span>
                    </a>
                </div>
            </div>

            <!-- Tab 4: Team -->
            <div class="col px-1">
                <a href="{{ route('user.team') }}"
                    class="nav-item-link text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('user.team') ? 'active-tab' : 'text-muted' }}">
                    <i class="bi bi-people-fill fs-4"></i>
                    <span class="nav-label" style="font-size: 0.72rem; font-weight: 600;">Team</span>
                </a>
            </div>

            <!-- Tab 5: Profile -->
            <div class="col px-1">
                <a href="{{ route('user.profile') }}"
                    class="nav-item-link text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('user.profile') ? 'active-tab' : 'text-muted' }}">
                    <i class="bi bi-person-circle fs-4"></i>
                    <span class="nav-label" style="font-size: 0.72rem; font-weight: 600;">Profile</span>
                </a>
            </div>

        </div>
    </div>
</div>

<style>
    body {
        padding-bottom: 75px;
        /* Prevent bottom nav from covering page content */
    }

    .mobile-bottom-nav {
        z-index: 1050;
        border-top-left-radius: 1.25rem;
        border-top-right-radius: 1.25rem;
        background: #ffffff;
    }

    .nav-item-link {
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .nav-item-link:hover,
    .nav-item-link.active-tab {
        color: #034833 !important;
        transform: translateY(-2px);
    }

    .floating-center-btn-wrapper {
        position: absolute;
        top: -28px;
        left: 50%;
        transform: translateX(-50%);
    }

    .floating-center-btn {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, #034833 0%, #086b4d 100%);
        transform: rotate(45deg);
        border: 3px solid #ffffff;
        box-shadow: 0 8px 16px rgba(3, 72, 51, 0.35);
        transition: transform 0.25s ease;
    }

    .floating-center-btn span {
        transform: rotate(-45deg);
        font-weight: 900;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .floating-center-btn:hover {
        transform: rotate(45deg) scale(1.08);
    }
</style>