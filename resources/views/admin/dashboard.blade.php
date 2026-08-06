@extends('layouts.admin')

@section('admin-title', 'Admin Dashboard')

@section('admin-content')
@php
    $totalUsers = \App\Models\User::where('role', 'user')->count();
    $totalActiveInvestments = \App\Models\Investment::where('status', 'active')->count();
    $totalInvestedAmount = \App\Models\Investment::sum('invested_amount');
    $totalReferralBonuses = \App\Models\Transaction::where('type', 'referral_bonus')->where('status', 'completed')->sum('amount');
    $totalPackages = \App\Models\Package::count();
    $pendingDeposits = \App\Models\Deposit::where('status', 'pending')->count();
    $totalApprovedDeposits = \App\Models\Deposit::where('status', 'approved')->sum('amount');

    $recentUsers = \App\Models\User::where('role', 'user')->latest()->take(5)->get();
    $recentInvestments = \App\Models\Investment::with(['user', 'package'])->latest()->take(5)->get();
@endphp

<div class="dashboard-container">
    <!-- Welcome Header -->
    <div class="welcome-section mb-4">
        <h2 class="fw-bold text-primary">Welcome to Admin Panel, {{ Auth::user()->name ?? 'Admin' }}!</h2>
        <p class="text-muted">Overview of MLM investment platform performance, packages, and registered users.</p>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-primary border-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Users</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalUsers) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-warning border-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-wallet-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending Deposit Requests</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($pendingDeposits) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-success border-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Approved Deposit Total</div>
                        <h3 class="fw-bold mb-0 text-dark">৳{{ number_format($totalApprovedDeposits, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-success border-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Active Investments</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalActiveInvestments) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-info border-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Invested Volume</div>
                        <h3 class="fw-bold mb-0 text-dark">৳{{ number_format($totalInvestedAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-secondary border-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Investment Packages</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalPackages) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="card border-0 shadow-sm p-4 mb-4 bg-white rounded-3">
        <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge me-2 text-warning"></i> Quick Management Actions</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.deposits.index') }}" class="btn btn-warning position-relative text-dark fw-bold">
                <i class="bi bi-wallet2 me-1"></i> Check Deposit Requests
                @if($pendingDeposits > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $pendingDeposits }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-dark">
                <i class="bi bi-gear me-1"></i> Payment Methods
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                <i class="bi bi-people me-1"></i> Manage Users List
            </a>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-success">
                <i class="bi bi-box-seam me-1"></i> Investment Packages
            </a>
        </div>
            <a href="{{ route('admin.packages.create') }}" class="btn btn-outline-success">
                <i class="bi bi-plus-circle me-1"></i> Create New Package
            </a>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="row g-4">
        <!-- Recent Registered Users Table -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-person-plus me-2 text-primary"></i> Recently Registered Users</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-link text-decoration-none">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $userItem)
                                <tr>
                                    <td><span class="fw-bold">{{ $userItem->name }}</span></td>
                                    <td class="small text-muted">{{ $userItem->email }}</td>
                                    <td class="small">{{ $userItem->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.users.activity', $userItem) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i> Activity
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No registered users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Investments Table -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-cart-check me-2 text-success"></i> Recent Package Purchases</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Package</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvestments as $investment)
                                <tr>
                                    <td>
                                        @if($investment->user)
                                            <a href="{{ route('admin.users.activity', $investment->user) }}" class="fw-bold text-decoration-none text-dark">
                                                {{ $investment->user->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-primary">{{ $investment->package->name ?? 'Package' }}</span></td>
                                    <td class="fw-bold text-success">৳{{ number_format($investment->invested_amount, 2) }}</td>
                                    <td class="small">{{ $investment->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No package purchases logged yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
