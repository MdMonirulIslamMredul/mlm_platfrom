@extends('frontend.layouts.app')

@section('title', 'My Balance History')

@section('content')
<div class="container py-4 px-3" style="max-width: 580px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background: #034833; border-radius: 50%; color: #fff; text-decoration: none;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark-emerald mb-0">My Balance History</h4>
            <span class="text-muted small">All account transactions & activity logs</span>
        </div>
    </div>

    <!-- Balance Banner -->
    <div class="card-emerald p-4 mb-4 shadow rounded-4 text-white">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="small text-light opacity-75 fw-semibold mb-1">Current Wallet Balance</div>
                <h2 class="fw-black mb-0">৳{{ number_format($user->balance, 2) }}</h2>
            </div>
            <div class="p-3 rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-wallet2 fs-2 text-white"></i>
            </div>
        </div>
        <div class="row pt-2 border-top border-white border-opacity-25 text-center g-2">
            <div class="col-4 border-end border-white border-opacity-10 px-1">
                <div class="small text-light opacity-75" style="font-size: 0.72rem;">Total Deposits</div>
                <div class="fw-bold text-white" style="font-size: 0.82rem;">৳{{ number_format($totalDeposits ?? 0, 2) }}</div>
            </div>
            <div class="col-4 border-end border-white border-opacity-10 px-1">
                <div class="small text-light opacity-75" style="font-size: 0.72rem;">Total Withdraw</div>
                <div class="fw-bold text-white" style="font-size: 0.82rem;">৳{{ number_format($totalWithdrawals ?? 0, 2) }}</div>
            </div>
            <div class="col-4 px-1">
                <div class="small text-light opacity-75" style="font-size: 0.72rem;">Refer Bonus</div>
                <div class="fw-bold text-white" style="font-size: 0.82rem;">৳{{ number_format($totalReferralBonuses ?? 0, 2) }}</div>
            </div>
            <div class="col-6 border-end border-white border-opacity-10 px-1 mt-2 pt-2 border-top border-white border-opacity-10">
                <div class="small text-light opacity-75" style="font-size: 0.72rem;">Daily Profit Bonus</div>
                <div class="fw-bold text-white" style="font-size: 0.85rem;">+৳{{ number_format($totalDailyReturns ?? 0, 2) }}</div>
            </div>
            <div class="col-6 px-1 mt-2 pt-2 border-top border-white border-opacity-10">
                <div class="small text-light opacity-75" style="font-size: 0.72rem;">Total Package Spend</div>
                <div class="fw-bold text-white" style="font-size: 0.85rem;">৳{{ number_format($totalPackageBuy ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Category Filter Navigation Pills -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-1.5 p-1.5 bg-white rounded-4 shadow-sm border">
            <a href="{{ route('user.balance-history', ['type' => 'all']) }}" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none {{ $filterType === 'all' ? 'btn-emerald' : 'btn-light text-muted' }}" 
               style="font-size: 0.8rem;">
               All
            </a>
            <a href="{{ route('user.balance-history', ['type' => 'deposit']) }}" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none {{ $filterType === 'deposit' ? 'btn-emerald' : 'btn-light text-muted' }}" 
               style="font-size: 0.8rem;">
               Deposit
            </a>
            <a href="{{ route('user.balance-history', ['type' => 'withdraw']) }}" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none {{ $filterType === 'withdraw' ? 'btn-emerald' : 'btn-light text-muted' }}" 
               style="font-size: 0.8rem;">
               Withdraw
            </a>
            <a href="{{ route('user.balance-history', ['type' => 'referral_bonus']) }}" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none {{ $filterType === 'referral_bonus' ? 'btn-emerald' : 'btn-light text-muted' }}" 
               style="font-size: 0.8rem;">
               Referral Bonus
            </a>
            <a href="{{ route('user.balance-history', ['type' => 'daily_return']) }}" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none {{ $filterType === 'daily_return' ? 'btn-emerald' : 'btn-light text-muted' }}" 
               style="font-size: 0.8rem;">
               Daily Profit
            </a>
            <a href="{{ route('user.balance-history', ['type' => 'package_buy']) }}" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none {{ $filterType === 'package_buy' ? 'btn-emerald' : 'btn-light text-muted' }}" 
               style="font-size: 0.8rem;">
               Package Buy
            </a>
        </div>
    </div>

    <!-- Transactions History List -->
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($transactions as $tx)
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Type Icon Circle -->
                        @if($tx->type === 'deposit')
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                                <i class="bi bi-box-arrow-in-down fs-4"></i>
                            </div>
                        @elseif($tx->type === 'withdraw')
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                                <i class="bi bi-box-arrow-up fs-4"></i>
                            </div>
                        @elseif($tx->type === 'referral_bonus')
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                                <i class="bi bi-gift-fill fs-4"></i>
                            </div>
                        @elseif($tx->type === 'daily_return')
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                                <i class="bi bi-graph-up-arrow fs-4"></i>
                            </div>
                        @else
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                                <i class="bi bi-box-seam-fill fs-4"></i>
                            </div>
                        @endif

                        <div>
                            <div class="d-flex align-items-center gap-2 mb-0.5">
                                @if($tx->type === 'deposit')
                                    <span class="badge bg-success text-white px-2.5 py-0.5 rounded-pill small">Deposit</span>
                                @elseif($tx->type === 'withdraw')
                                    <span class="badge bg-danger text-white px-2.5 py-0.5 rounded-pill small">Withdraw</span>
                                @elseif($tx->type === 'referral_bonus')
                                    <span class="badge bg-primary text-white px-2.5 py-0.5 rounded-pill small">Referral Bonus</span>
                                @elseif($tx->type === 'daily_return')
                                    <span class="badge bg-success text-white px-2.5 py-0.5 rounded-pill small">Daily Profit</span>
                                @else
                                    <span class="badge bg-dark-emerald text-white px-2.5 py-0.5 rounded-pill small">Package Buy</span>
                                @endif
                                <span class="small text-muted opacity-75">{{ $tx->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="fw-semibold text-dark small">{{ $tx->description ?? ucfirst(str_replace('_', ' ', $tx->type)) }}</div>
                        </div>
                    </div>

                    <!-- Amount & Status -->
                    <div class="text-end ms-2">
                        @if(in_array($tx->type, ['deposit', 'referral_bonus', 'daily_return'], true))
                            <div class="fw-bold fs-5 text-success">+৳{{ number_format($tx->amount, 2) }}</div>
                        @else
                            <div class="fw-bold fs-5 text-danger">-৳{{ number_format($tx->amount, 2) }}</div>
                        @endif

                        @if($tx->status === 'completed' || $tx->status === 'approved')
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-0.5 rounded-pill small">Completed</span>
                        @elseif($tx->status === 'pending')
                            <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-0.5 rounded-pill small">Pending</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-0.5 rounded-pill small">Declined</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                <i class="bi bi-clock-history fs-1 text-secondary mb-2"></i>
                <p class="mb-0 fw-semibold">No {{ str_replace('_', ' ', $filterType) }} transactions found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $transactions->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
