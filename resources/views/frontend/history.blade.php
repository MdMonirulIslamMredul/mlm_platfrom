@extends('frontend.layouts.app')

@section('title', 'Account History')

@section('content')
<div class="container py-4 px-3" style="max-width: 540px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #034833; border-radius: 50%; color: #fff;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h4 class="fw-bold text-dark-emerald mb-0">Account History</h4>
    </div>

    <!-- Overview Sub-nav Links -->
    <div class="row g-2 mb-4 text-center">
        <div class="col-6">
            <a href="{{ route('user.plans') }}" class="card border-0 shadow-sm p-3 text-decoration-none text-dark rounded-4">
                <i class="bi bi-journal-check text-warning fs-3 mb-1"></i>
                <div class="fw-bold small">Total Plans</div>
            </a>
        </div>
        <div class="col-6">
            <a href="{{ route('user.referral-bonus') }}" class="card border-0 shadow-sm p-3 text-decoration-none text-dark rounded-4">
                <i class="bi bi-heart-fill text-danger fs-3 mb-1"></i>
                <div class="fw-bold small">Referral Bonus</div>
            </a>
        </div>
        <div class="col-6">
            <a href="{{ route('user.team-withdrawals') }}" class="card border-0 shadow-sm p-3 text-decoration-none text-dark rounded-4">
                <i class="bi bi-cash-coin text-info fs-3 mb-1"></i>
                <div class="fw-bold small">Team Withdrawals</div>
            </a>
        </div>
        <div class="col-6">
            <a href="{{ route('user.team-invest') }}" class="card border-0 shadow-sm p-3 text-decoration-none text-dark rounded-4">
                <i class="bi bi-puzzle-fill text-primary fs-3 mb-1"></i>
                <div class="fw-bold small">Team Investments</div>
            </a>
        </div>
    </div>

    <!-- Recent Transactions Section -->
    <h5 class="fw-bold text-dark-emerald mb-3">All Account Transactions</h5>
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($transactions as $tx)
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <span class="badge bg-dark-emerald text-white mb-1">{{ strtoupper(str_replace('_', ' ', $tx->type)) }}</span>
                        <div class="small text-muted">{{ $tx->description ?? 'Transaction Log' }}</div>
                        <div class="small text-muted opacity-75">{{ $tx->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5 {{ in_array($tx->type, ['deposit', 'referral_bonus', 'daily_return']) ? 'text-success' : 'text-danger' }}">
                            {{ in_array($tx->type, ['deposit', 'referral_bonus', 'daily_return']) ? '+' : '-' }}৳{{ number_format($tx->amount, 2) }}
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($tx->status) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                <i class="bi bi-clock-history fs-1 text-success mb-2"></i>
                <p class="mb-0">No transactions recorded yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
