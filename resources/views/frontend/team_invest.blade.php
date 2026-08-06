@extends('frontend.layouts.app')

@section('title', 'Total Team Invest')

@section('content')
<div class="container py-4 px-3" style="max-width: 540px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #034833; border-radius: 50%; color: #fff;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h4 class="fw-bold text-dark-emerald mb-0">Total Team Invest</h4>
    </div>

    <!-- Summary Banner -->
    <div class="card-emerald p-4 mb-4 shadow">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-light opacity-75 fw-semibold mb-1">Total Team Invested</div>
                <h2 class="fw-black mb-0 text-white">৳{{ number_format($totalTeamInvest, 2) }}</h2>
            </div>
            <div class="p-3 rounded-4 bg-primary text-white">
                <i class="bi bi-puzzle-fill fs-1"></i>
            </div>
        </div>
    </div>

    <!-- Team Investments List -->
    <div class="d-flex flex-column gap-3">
        @forelse($teamInvestments as $investment)
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $investment->user->name ?? $investment->user->email ?? 'Member' }}</h6>
                        <small class="text-muted">Package: {{ $investment->package->name ?? 'Investment' }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary fs-5">৳{{ number_format($investment->invested_amount, 2) }}</div>
                        <small class="text-muted d-block">{{ $investment->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                <i class="bi bi-pie-chart fs-1 text-primary mb-2"></i>
                <p class="mb-0">No team investment records found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $teamInvestments->links() }}
    </div>
</div>
@endsection
