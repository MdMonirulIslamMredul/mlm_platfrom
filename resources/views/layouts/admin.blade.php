<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('admin-title', 'Admin Panel') -Admin </title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Admin Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <!-- Page Specific Styles -->
    @stack('styles')
</head>

<body class="admin-body">
    <div class="admin-container">
        <!-- Admin Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h5 class="m-0">
                    <i class="bi bi-gear"></i> Admin Panel
                </h5>
            </div>

            <div class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>All Users</span>
                </a>
                <a href="{{ route('admin.packages.index') }}"
                    class="menu-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Investment Packages</span>
                </a>
                <a href="{{ route('admin.payment-methods.index') }}"
                    class="menu-item {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i>
                    <span>Payment Methods</span>
                </a>
                <a href="{{ route('admin.deposits.index') }}"
                    class="menu-item {{ request()->routeIs('admin.deposits.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                    <span>Deposit Requests</span>
                    @php
                        $pendingDepositsCount = \App\Models\Deposit::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingDepositsCount > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingDepositsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.withdrawals.index') }}"
                    class="menu-item {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                    <span>Withdrawal Requests</span>
                    @php
                        $pendingWithdrawalsCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingWithdrawalsCount > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingWithdrawalsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.package-orders.index') }}"
                    class="menu-item {{ request()->routeIs('admin.package-orders.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check-fill"></i>
                    <span>Package Orders</span>
                    @php
                        $pendingPackageOrdersCount = \App\Models\PackageOrder::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingPackageOrdersCount > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingPackageOrdersCount }}</span>
                    @endif
                </a>
                {{-- <a href="{{ route('admin.messages.index') }}"
                    class="menu-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>
                    <span>Messages</span>
                    @php
                    $unreadCount = \App\Models\Message::where('is_read', false)->count();
                    @endphp
                    @if ($unreadCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a> --}}
                <a href="{{ url('/') }}" class="menu-item">
                    <i class="bi bi-house"></i>
                    <span>Back to Home</span>
                </a>

                <a href="{{ route('admin.profile.edit') }}"
                    class="menu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>Edit Profile</span>
                </a>

                <!-- Site Settings Collapsible Menu -->
                @if (Auth::user() && Auth::user()->role === 'super_admin')
                    <div class="menu-item-collapsible">
                        <a href="#siteSettingsMenu" class="menu-item" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="bi bi-gear-fill"></i>
                            <span>Site Settings</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="siteSettingsMenu">

                            <a href="{{ route('admin.profile.edit') }}"
                                class="menu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                                <i class="bi bi-person-gear"></i>
                                <span>Edit Profile</span>
                            </a>

                            <a href="{{ route('admin.users.index') }}"
                                class="menu-item submenu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill"></i>
                                <span>All Users</span>
                            </a>

                            <a href="{{ route('admin.settings.general') }}"
                                class="menu-item submenu-item {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                                <i class="bi bi-sliders"></i>
                                <span>General Setting</span>
                            </a>
                            <a href="{{ route('admin.settings.about') }}"
                                class="menu-item submenu-item {{ request()->routeIs('admin.settings.about') ? 'active' : '' }}">
                                <i class="bi bi-info-circle"></i>
                                <span>About Setting</span>
                            </a>
                            <a href="{{ route('admin.settings.contact') }}"
                                class="menu-item submenu-item {{ request()->routeIs('admin.settings.contact') ? 'active' : '' }}">
                                <i class="bi bi-telephone"></i>
                                <span>Contact Us Setting</span>
                            </a>
                            <a href="{{ route('admin.settings.logo') }}"
                                class="menu-item submenu-item {{ request()->routeIs('admin.settings.logo') ? 'active' : '' }}">
                                <i class="bi bi-image"></i>
                                <span>Logo Setting</span>
                            </a>
                            <a href="{{ route('admin.settings.slider') }}"
                                class="menu-item submenu-item {{ request()->routeIs('admin.settings.slider') ? 'active' : '' }}">
                                <i class="bi bi-sliders"></i>
                                <span>Slider Setting</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="sidebar-footer">
                <form action="{{ url('/logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="menu-item w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Admin Content -->
        <div class="admin-content">
            <!-- Admin Top Bar -->
            <div class="admin-topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h4 class="page-title m-0">@yield('admin-title', 'Dashboard')</h4>
                </div>
                <div class="topbar-right">
                    <span class="user-info">
                        <i class="bi bi-person-circle"></i>
                        {{ Auth::user()->name ?? Auth::user()->email }}
                    </span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="admin-main">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('admin-content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>

    <!-- Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>

</html>