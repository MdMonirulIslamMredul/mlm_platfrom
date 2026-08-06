<div class="container-fluid sticky-top">
    <div class="container pt-4 pb-5">
        <div class="row g-1">
            <div class="container">
                <div class="row">
                    <!-- Logo Section - Desktop -->
                    <div class="col-3 d-none d-md-inline bg-white rounded-start p-0 text-center">
                        <div class="py-3">
                            <a href="{{ url('/') }}">
                                <img class="w-50"
                                    src="{{ isset($logoSettings) && $logoSettings->header_logo ? asset($logoSettings->header_logo) : asset('static/home/logo.jpeg') }}"
                                    alt="Canada Visa Processing Logo">
                            </a>
                        </div>
                    </div>

                    <!-- Navigation - Desktop -->
                    <div class="d-none d-md-inline col-6 bg-white text-center p-0 rounded-lg">
                        <div class="py-3">
                            <a href="{{ url('/') }}"
                                class="btn btn-danger py-2 {{ request()->is('/') ? 'active' : '' }}">HOME</a>
                            <a href="{{ url('/contact') }}"
                                class="btn btn-danger py-2 {{ request()->is('contact') ? 'active' : '' }}">CONTACT
                                US</a>
                            <a href="{{ url('/about') }}"
                                class="btn btn-danger py-2 {{ request()->is('about') ? 'active' : '' }}">ABOUT US</a>
                        </div>
                    </div>


                    <div class="col-3 d-none d-md-inline bg-danger rounded-end p-0">
                        <div class="py-3 text-center text-white">
                            <a href="{{ url('/') }}"
                                class="btn btn-danger btn-lg p-0 text-white text-decoration-none">Check Visa Status</a>

                        </div>
                    </div>
                    <!-- Auth & Check Visa Status Button - Desktop -->
                    {{-- <div class="col-3 d-none d-md-inline bg-danger rounded-end p-0">


                        <div class="py-2 text-center text-white">
                            @auth
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-danger dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name ?? Auth::user()->email }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if (in_array(Auth::user()->role, ['admin', 'super_admin'], true))
                                            <li><a class="dropdown-item" href="{{ url('/admin') }}">Admin Panel</a></li>
                                        @endif
                                        <li>
                                            <form action="{{ url('/logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <a href="{{ url('/login') }}" class="btn btn-outline-light btn-sm me-1">Login</a>
                                <a href="{{ url('/register') }}" class="btn btn-light btn-sm">Register</a>
                            @endauth
                            <br>
                            <a href="{{ url('/#visa-check') }}" class="btn btn-danger btn-sm mt-1 text-white text-decoration-none">Check Visa</a>
                        </div>
                    </div> --}}
                </div>

                <!-- Mobile Navigation -->
                <div class="row rounded bg-white">
                    <!-- Mobile Menu - Top -->
                    <div class="col-12 d-inline d-md-none text-center p-0 rounded-lg">
                        <div class="py-2">
                            <a href="{{ url('/') }}"
                                class="btn btn-danger btn-sm py-1 {{ request()->is('/') ? 'active' : '' }}">HOME</a>
                            <a href="{{ url('/contact') }}"
                                class="btn btn-danger btn-sm py-1 {{ request()->is('contact') ? 'active' : '' }}">CONTACT</a>
                            <a href="{{ url('/about') }}"
                                class="btn btn-danger btn-sm py-1 {{ request()->is('about') ? 'active' : '' }}">ABOUT</a>
                            @auth
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-danger btn-sm py-1 dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-person-circle"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if (in_array(Auth::user()->role, ['admin', 'super_admin'], true))
                                            <li><a class="dropdown-item" href="{{ url('/admin') }}">Admin Panel</a></li>
                                        @endif
                                        <li>
                                            <form action="{{ url('/logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <a href="{{ url('/login') }}" class="btn btn-outline-danger btn-sm py-1">Login</a>
                                <a href="{{ url('/register') }}" class="btn btn-outline-danger btn-sm py-1">Register</a>
                            @endauth
                        </div>
                    </div>

                    <!-- Mobile Logo -->
                    <div class="col-4 d-inline d-md-none p-0">
                        <div class="py-2 ps-2 ps-md-3">
                            <a href="{{ url('/') }}">
                                <img class="w-75"
                                    src="{{ isset($logoSettings) && $logoSettings->header_logo ? asset($logoSettings->header_logo) : asset('static/home/logo.jpeg') }}"
                                    alt="Canada Visa Processing Logo">
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Check Visa Button -->
                    <div class="col-8 d-inline d-md-none text-end p-0 pe-2 pe-md-3">
                        <a href="{{ url('/#visa-check') }}" class="btn btn-danger btn-lg my-2">Check Visa Status</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
