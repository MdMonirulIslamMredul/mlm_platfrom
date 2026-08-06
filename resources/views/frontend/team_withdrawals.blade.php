@extends('frontend.layouts.app')

@section('title', 'Team Total Withdrawals')

@section('content')
<div class="container py-4 px-3" style="max-width: 540px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #034833; border-radius: 50%; color: #fff;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h4 class="fw-bold text-dark-emerald mb-0">Team Total Withdrawals</h4>
    </div>

    <!-- Summary Banner -->
    <div class="card-emerald p-4 mb-4 shadow">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-light opacity-75 fw-semibold mb-1">Total Team Withdrawals</div>
                <h2 class="fw-black mb-0 text-white">৳{{ number_format($totalTeamWithdrawals, 2) }}</h2>
            </div>
            <div class="p-3 rounded-4 bg-info text-white">
                <i class="bi bi-cash-coin fs-1"></i>
            </div>
        </div>
    </div>

    <!-- Team Withdrawals List -->
    <div class="d-flex flex-column gap-3">
        @forelse($teamWithdrawals as $withdrawal)
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $withdrawal->user->name ?? $withdrawal->user->email ?? 'Member' }}</h6>
                        <small class="text-muted">{{ $withdrawal->created_at->format('M d, Y H:i') }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-danger fs-5">৳{{ number_format($withdrawal->amount, 2) }}</div>
                        <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($withdrawal->status) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                <i class="bi bi-receipt-cutoff fs-1 text-info mb-2"></i>
                <p class="mb-0">No team withdrawal records found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $teamWithdrawals->links() }}
    </div>
</div>
@endsection
