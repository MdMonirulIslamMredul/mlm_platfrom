@extends('frontend.layouts.app')

@section('title', 'Mobile Investment Portal')

@push('styles')
    <style>
        /* Dark Emerald Color Scheme matching App Screenshot */
        .bg-dark-emerald {
            background-color: #034833 !important;
        }

        .text-dark-emerald {
            color: #034833 !important;
        }

        .card-emerald {
            background-color: #034833;
            color: #ffffff;
            border-radius: 1.25rem;
            border: none;
        }

        .card-emerald-item {
            background-color: #034833;
            color: #ffffff;
            border-radius: 1rem;
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-emerald-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(3, 72, 51, 0.2);
        }

        .btn-pill-emerald {
            background-color: #034833;
            color: #ffffff;
            border-radius: 2rem;
            font-weight: 600;
            border: none;
            padding: 0.6rem 1.2rem;
        }

        .btn-pill-emerald:hover {
            background-color: #023626;
            color: #ffffff;
        }

        .btn-action-white {
            background-color: #ffffff;
            color: #034833;
            font-weight: 700;
            border-radius: 2rem;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-action-white:hover {
            background-color: #f8fafc;
            color: #034833;
        }

        .product-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .btn-orange-buy {
            background: linear-gradient(135deg, #ff7e00 0%, #ff5500 100%);
            color: #ffffff;
            font-weight: 700;
            border-radius: 0 1.25rem 1.25rem 1.25rem;
            border: none;
            padding: 0.6rem 1.5rem;
        }

        .btn-orange-buy:hover {
            background: linear-gradient(135deg, #e67100 0%, #e64c00 100%);
            color: #ffffff;
        }

        .progress-bar-custom {
            height: 14px;
            background-color: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff7e00 0%, #ff5500 100%);
            border-radius: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container py-3 px-3" style="max-width: 540px;">

        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Profile Header Banner -->
        <div id="profile-section" class="bg-white p-3 rounded-4 shadow-sm mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 bg-success bg-opacity-10 p-2 text-center" style="width: 58px; height: 58px;">
                        <i class="bi bi-person-bounding-box text-success fs-1"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Welcome</div>
                        <div class="fw-bold text-dark-emerald fs-6">{{ $user->phone ?? $user->name ?? $user->email }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Whatsapp Group Button -->
                    <a href="https://chat.whatsapp.com" target="_blank"
                        class="btn btn-dark-emerald rounded-pill btn-sm d-flex align-items-center gap-1 px-3 py-2 text-white text-decoration-none fw-bold"
                        style="background: #034833; font-size: 0.8rem;">
                        <i class="bi bi-whatsapp text-success fs-5"></i>
                        <span>Whatsapp Group</span>
                    </a>

                    <!-- Power Logout Button -->
                    <form action="{{ url('/logout') }}" method="POST" class="d-inline mb-0">
                        @csrf
                        <button type="submit"
                            class="btn btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;" title="Logout">
                            <i class="bi bi-power fs-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Current Balance Card -->
        <div id="home" class="card-emerald p-4 mb-3 shadow">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="small text-light opacity-75 fw-semibold mb-1">Current Balance</div>
                    <h2 class="fw-black mb-0 text-white">৳{{ number_format($balance, 2) }}</h2>
                </div>
                <div class="col-6 d-flex flex-column gap-2 text-end">
                    <button class="btn btn-action-white w-100 d-flex align-items-center justify-content-center gap-2"
                        data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        <i class="bi bi-box-arrow-up-right text-success"></i> Withdraw Money
                    </button>
                    <a href="{{ route('user.deposit') }}" class="btn btn-action-white w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none">
                        <i class="bi bi-wallet-fill text-primary"></i> Deposit Money
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Action Pills Row -->
        <div class="row g-2 mb-4">
            <div class="col-6">
                <a href="{{ route('user.plans') }}"
                    class="btn btn-pill-emerald w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none shadow-sm">
                    <i class="bi bi-journal-check text-warning fs-5"></i> My Active Plans
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('user.team') }}"
                    class="btn btn-pill-emerald w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none shadow-sm">
                    <i class="bi bi-people-fill text-info fs-5"></i> My Team
                </a>
            </div>
        </div>

        <!-- Over View Card Section -->
        <div class="mb-4">
            <h5 class="fw-bold text-dark-emerald mb-3">Over view</h5>
            <div class="card-emerald p-3 shadow-sm">
                <div class="row text-center g-3">
                    <div class="col-4 border-end border-light border-opacity-10">
                        <div class="small opacity-75 text-light mb-1">Total Withdraw</div>
                        <div class="fw-bold fs-6">৳0.00</div>
                    </div>
                    <div class="col-4 border-end border-light border-opacity-10">
                        <div class="small opacity-75 text-light mb-1">Total recharge</div>
                        <div class="fw-bold fs-6">৳{{ number_format($balance, 2) }}</div>
                    </div>
                    <a href="{{ route('user.referral-bonus') }}" class="col-4 text-decoration-none text-white d-block cursor-pointer">
                        <div class="small opacity-75 text-light mb-1">Total Refer Bonus</div>
                        <div class="fw-bold fs-6 text-white">৳{{ number_format($totalReferBonus, 2) }} <i class="bi bi-chevron-right text-light opacity-50" style="font-size: 0.75rem;"></i></div>
                    </a>

                    <div class="col-12">
                        <hr class="my-1 opacity-10">
                    </div>

                    <div class="col-4 border-end border-light border-opacity-10">
                        <div class="small opacity-75 text-light mb-1">Pending Withdraw</div>
                        <div class="fw-bold fs-6">৳0.00</div>
                    </div>
                    <div class="col-4 border-end border-light border-opacity-10">
                        <div class="small opacity-75 text-light mb-1">Total Team</div>
                        <div class="fw-bold fs-6">{{ $totalTeam }}</div>
                    </div>
                    <div class="col-4">
                        <div class="small opacity-75 text-light mb-1">Pending Deposit</div>
                        <div class="fw-bold fs-6">$0.00</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invite Friends Card -->
        <div id="team-section" class="card-emerald p-3 mb-4 shadow-sm">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-warning p-2 rounded-3 text-dark d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px;">
                    <i class="bi bi-envelope-open-heart-fill fs-3 text-dark"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1 text-white">Invite Friends</h6>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control bg-white text-dark border-0 rounded-start-2"
                            id="referralUrlAppInput" readonly
                            value="{{ url('/register?invite_code=' . ($user->referral_code ?? '')) }}">
                        <button class="btn btn-success rounded-end-2 px-3" type="button" onclick="copyAppReferralLink()">
                            <i class="bi bi-link-45deg me-1"></i> Copy Link
                        </button>
                    </div>
                </div>
            </div>

            <!-- Referral Code Copy Row -->
            <div class="bg-black bg-opacity-20 p-2.5 rounded-3 mb-2">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-light opacity-75 small fw-semibold me-2">Referral Code:</span>
                    <span id="appCodeCopyToast" class="text-warning fw-bold small d-none"><i class="bi bi-check2-circle"></i> Code Copied!</span>
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control bg-white text-dark border-0 rounded-start-2 fw-bold text-center"
                        id="referralCodeAppInput" readonly style="letter-spacing: 2px;"
                        value="{{ $user->referral_code ?? 'N/A' }}">
                    <button class="btn btn-warning rounded-end-2 px-3 fw-bold text-dark" type="button" onclick="copyAppReferralCode()">
                        <i class="bi bi-copy me-1"></i> Copy Code
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-2 small">
                <span class="opacity-75">Earn 10% bonus when friends join</span>
                <span id="appCopyToast" class="text-warning fw-bold d-none"><i class="bi bi-check2-circle"></i> Link Copied!</span>
            </div>
        </div>

        <!-- Account History Section -->
        <div id="history-section" class="mb-4">
            <h5 class="fw-bold text-dark-emerald mb-3">Account History</h5>
            <div class="d-flex flex-column gap-2">

                <!-- Item 1: Total Plans -->
                <a href="{{ route('user.plans') }}" class="text-decoration-none">
                    <div class="card-emerald-item p-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-warning text-dark d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;">
                                <i class="bi bi-sprout-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white">Total Plans</div>
                                <div class="fw-bold fs-5 text-white">{{ $activePlans }}</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-light opacity-50 fs-5"></i>
                    </div>
                </a>

                <!-- Item 2: Team Total Withdrawals -->
                <a href="{{ route('user.team-withdrawals') }}" class="text-decoration-none">
                    <div class="card-emerald-item p-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-info text-white d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white">Team Total Withdrawals</div>
                                <div class="fw-bold fs-5 text-white">৳{{ number_format($teamTotalWithdrawals ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-light opacity-50 fs-5"></i>
                    </div>
                </a>

                <!-- Item 3: Total Refer Bonus -->
                <a href="{{ route('user.referral-bonus') }}" class="text-decoration-none">
                    <div class="card-emerald-item p-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-danger text-white d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;">
                                <i class="bi bi-heart-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white">Total Refer Bonus</div>
                                <div class="fw-bold fs-5 text-white">৳{{ number_format($totalReferBonus, 2) }}</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-light opacity-50 fs-5"></i>
                    </div>
                </a>

                <!-- Item 4: Total Team Invest -->
                <a href="{{ route('user.team-invest') }}" class="text-decoration-none">
                    <div class="card-emerald-item p-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;">
                                <i class="bi bi-puzzle-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white">Total Team Invest</div>
                                <div class="fw-bold fs-5 text-white">৳{{ number_format($teamTotalInvest ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-light opacity-50 fs-5"></i>
                    </div>
                </a>

                <!-- Item 5: Total Refer Count -->
                <a href="{{ route('user.team') }}" class="text-decoration-none">
                    <div class="card-emerald-item p-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-warning text-dark d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white">Total Refer Count</div>
                                <div class="fw-bold fs-5 text-white">{{ $totalTeam }}</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-light opacity-50 fs-5"></i>
                    </div>
                </a>

                <!-- Item 6: My Balance History -->
                <a href="{{ route('user.balance-history') }}" class="text-decoration-none">
                    <div class="card-emerald-item p-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-secondary text-white d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white">My Balance History</div>
                                <div class="small text-light opacity-75">All transaction logs & filters</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-light opacity-50 fs-5"></i>
                    </div>
                </a>

            </div>
        </div>

        <!-- Recent Package Orders Section -->
        @if(isset($recentPackageOrders) && $recentPackageOrders->count() > 0)
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark-emerald mb-0"><i class="bi bi-cart-check me-2"></i> Recent Package Orders</h5>
                    <a href="{{ route('user.packages') }}" class="small fw-bold text-dark-emerald text-decoration-none">View All <i class="bi bi-chevron-right"></i></a>
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($recentPackageOrders as $ord)
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold text-dark mb-0">{{ $ord->package_name }}</div>
                                    <div class="small text-muted"><i class="bi bi-hash me-1"></i>TxID: <code>{{ $ord->transaction_id }}</code> ({{ $ord->payment_method_name }})</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-danger">৳{{ number_format($ord->package_price, 2) }}</div>
                                    @if($ord->status === 'pending')
                                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i> Pending</span>
                                    @elseif($ord->status === 'approved')
                                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.7rem;"><i class="bi bi-check-circle me-1"></i> Approved</span>
                                    @else
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 0.7rem;"><i class="bi bi-x-circle me-1"></i> Declined</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Product / Packages Section (Matching Screenshot 3) -->
        <div id="packages-section" class="mb-4">
            <div class="text-center mb-3">
                <h4 class="fw-bold text-dark-emerald">Product & Packages</h4>
                <!-- Tab Toggle -->
                <div class="d-inline-flex bg-white p-1 rounded-pill shadow-sm border border-light">
                    <button class="btn btn-emerald rounded-pill px-4 py-1.5 fw-bold"
                        style="font-size: 0.85rem;">Invest</button>
                    <button class="btn btn-light rounded-pill px-4 py-1.5 text-muted fw-bold"
                        style="font-size: 0.85rem;">Savings</button>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                @forelse($packages as $index => $package)
                    <div class="product-card p-3 position-relative">
                        <h5 class="fw-bold text-dark mb-3">VIP {{ $index + 1 }} - {{ $package->name }}</h5>
                        <div class="row align-items-center mb-3">
                            <!-- Product Icon -->
                            <div class="col-4 text-center">
                                <div class="bg-light rounded-4 p-3 d-flex align-items-center justify-content-center"
                                    style="height: 90px;">
                                    <i class="bi bi-graph-up-arrow text-danger fs-1"></i>
                                </div>
                            </div>
                            <!-- Product Details -->
                            <div class="col-8">
                                <div class="d-flex justify-content-between py-1 border-bottom border-light">
                                    <span class="text-muted">Price :</span>
                                    <span class="fw-bold text-danger">৳{{ number_format($package->price, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom border-light">
                                    <span class="text-muted">Cycle :</span>
                                    <span class="fw-bold text-dark">{{ $package->cycle_days }} Day</span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted">Daily :</span>
                                    <span class="fw-bold text-danger">৳{{ number_format($package->daily_return, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar & Buy Button -->
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="flex-grow-1">
                                @php
                                    $progress = min(100, round(($package->daily_return / max(1, $package->price)) * 100, 1));
                                @endphp
                                <div class="progress-bar-custom position-relative">
                                    <div class="progress-bar-fill" style="width: {{ max(15, $progress) }}%;"></div>
                                    <span class="position-absolute top-50 start-50 translate-middle text-white fw-bold"
                                        style="font-size: 0.65rem;">{{ $progress }}%</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-orange-buy d-flex align-items-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#buyPackageModal{{ $package->id }}">
                                <span>Buy</span> <i class="bi bi-coin fs-5"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Package Purchase Choice Modal for {{ $package->name }} -->
                    <div class="modal fade" id="buyPackageModal{{ $package->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h5 class="modal-title fw-bold text-dark-emerald">
                                        <i class="bi bi-cart-check-fill me-2"></i> Purchase Package
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body py-3">
                                    <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-dark fs-5">{{ $package->name }}</span>
                                            <span class="fw-bold text-danger fs-5">৳{{ number_format($package->price, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-muted small">
                                            <span>Daily Return: <strong class="text-danger">৳{{ number_format($package->daily_return, 2) }}</strong></span>
                                            <span>Cycle: <strong class="text-dark">{{ $package->cycle_days }} Days</strong></span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-white rounded-3 border">
                                        <span class="text-muted small fw-semibold">Available Wallet Balance:</span>
                                        <span class="fw-bold text-success fs-6">৳{{ number_format($balance, 2) }}</span>
                                    </div>

                                    <!-- Option 1: Buy with Balance -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small mb-1">Option 1: Purchase using Wallet Balance</label>
                                        @if($balance < $package->price)
                                            <button type="button" class="btn btn-secondary w-100 py-2.5 rounded-3 fw-bold opacity-75" disabled>
                                                <i class="bi bi-wallet2 me-1"></i> Buy with Balance (Insufficient Funds)
                                            </button>
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-exclamation-triangle me-1"></i> Required: ৳{{ number_format($package->price, 2) }} | Short by: ৳{{ number_format($package->price - $balance, 2) }}
                                            </div>
                                        @else
                                            <form action="{{ route('packages.buy') }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                                <button type="submit" class="btn btn-success w-100 py-2.5 rounded-3 fw-bold shadow-sm">
                                                    <i class="bi bi-wallet2 me-1"></i> Buy with Balance (৳{{ number_format($package->price, 2) }})
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="text-center my-2 text-muted small position-relative">
                                        <span class="bg-white px-2">OR</span>
                                    </div>

                                    <!-- Option 2: Make Payment (Direct Deposit) -->
                                    <div>
                                        <label class="form-label fw-bold text-dark small mb-1">Option 2: Direct Payment (MFS / Bank Transfer)</label>
                                        <a href="{{ route('user.packages.checkout', $package->id) }}" class="btn btn-dark-emerald w-100 py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow text-decoration-none text-white fs-6">
                                            <i class="bi bi-credit-card-2-front-fill fs-5"></i> Make Payment (MFS / Bank)
                                        </a>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="product-card p-4 text-center text-muted">
                        <i class="bi bi-box-seam fs-1 text-success mb-2"></i>
                        <p class="mb-0">No investment packages currently available.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Deposit Modal Placeholder -->
    <div class="modal fade" id="depositModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark-emerald"><i class="bi bi-wallet-fill me-2"></i> Deposit Funds
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-qr-code-scan text-success display-3 mb-3"></i>
                    <p class="text-muted">To deposit funds, please contact system admin or use instant recharge options.</p>
                    <button type="button" class="btn btn-dark-emerald rounded-pill px-4"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdraw Modal Placeholder -->
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark-emerald"><i class="bi bi-box-arrow-up-right me-2"></i> Withdraw
                        Money</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-bank text-primary display-3 mb-3"></i>
                    <p class="text-muted">Available Balance: <strong
                            class="text-success">৳{{ number_format($balance, 2) }}</strong></p>
                    <p class="text-muted small">Minimum withdrawal amount is ৳10.00.</p>
                    <button type="button" class="btn btn-dark-emerald rounded-pill px-4"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyAppReferralLink() {
            const copyText = document.getElementById("referralUrlAppInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            const toast = document.getElementById("appCopyToast");
            toast.classList.remove("d-none");
            setTimeout(() => {
                toast.classList.add("d-none");
            }, 3000);
        }

        function copyAppReferralCode() {
            const copyText = document.getElementById("referralCodeAppInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            const toast = document.getElementById("appCodeCopyToast");
            toast.classList.remove("d-none");
            setTimeout(() => {
                toast.classList.add("d-none");
            }, 3000);
        }
    </script>
@endpush