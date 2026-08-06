@extends('frontend.layouts.app')

@section('title', 'My Total Plans')

@section('content')
<div class="container py-4 px-3" style="max-width: 580px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background: #034833; border-radius: 50%; color: #fff; text-decoration: none;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark-emerald mb-0">Total Investment Plans</h4>
            <span class="text-muted small">Cycle validity & daily return tracking</span>
        </div>
    </div>

    <!-- Summary Banner -->
    <div class="card-emerald p-4 mb-4 shadow rounded-4 text-white">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-light opacity-75 fw-semibold mb-1">Active Packages</div>
                <h2 class="fw-black mb-0">৳{{ number_format($investments->where('status', 'active')->sum('invested_amount'), 2) }}</h2>
                <div class="small text-light opacity-75 mt-1">{{ $investments->where('status', 'active')->count() }} Active Investments</div>
            </div>
            <div class="p-3 rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-sprout-fill fs-2 text-white"></i>
            </div>
        </div>
    </div>

    <!-- Plans List -->
    <div class="d-flex flex-column gap-3">
        @forelse($investments as $investment)
            @php
                $cycleDays = $investment->package->cycle_days ?? 30;
                $daysLeft = $investment->days_left;
                $progress = $investment->progress_percent;
            @endphp
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark fs-5">{{ $investment->package->name ?? 'Investment Package' }}</h6>
                        <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>Cycle: <strong>{{ $cycleDays }} Days</strong></span>
                    </div>
                    <div class="text-end">
                        @if($investment->status === 'active')
                            <span class="badge text-white px-3 py-1.5 rounded-pill fw-semibold" style="background-color: #034833 !important;">
                                <i class="bi bi-check-circle me-1"></i> Active
                            </span>
                        @else
                            <span class="badge bg-secondary px-3 py-1.5 rounded-pill fw-semibold">
                                <i class="bi bi-clock-history me-1"></i> Completed
                            </span>
                        @endif
                        <div class="small fw-bold text-danger mt-1">
                            @if($investment->status === 'active')
                                @if($investment->created_at && $investment->created_at->isToday() && $investment->days_received == 0)
                                    <span class="text-primary"><i class="bi bi-clock-history me-1"></i>Profit Starts Tomorrow</span>
                                @else
                                    <i class="bi bi-hourglass-split me-1"></i>{{ $daysLeft }} Days Left
                                @endif
                            @else
                                <i class="bi bi-check2-all me-1"></i>Cycle Ended
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="my-2">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Progress ({{ $investment->days_received }}/{{ $cycleDays }} days)</span>
                        <span class="fw-bold text-dark">{{ $progress }}%</span>
                    </div>
                    <div class="progress rounded-pill style-progress" style="height: 8px; background-color: #e9ecef;">
                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $progress }}%; background-color: #034833;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="p-2.5 bg-light rounded-3 mt-2 border">
                    <div class="row text-center g-2 small">
                        <div class="col-3 border-end">
                            <div class="text-muted opacity-75">Invested</div>
                            <div class="fw-bold text-dark">৳{{ number_format($investment->invested_amount, 2) }}</div>
                        </div>
                        <div class="col-3 border-end">
                            <div class="text-muted opacity-75">Daily Return</div>
                            <div class="fw-bold text-success">+৳{{ number_format($investment->daily_return, 2) }}</div>
                        </div>
                        <div class="col-3 border-end">
                            <div class="text-muted opacity-75">Payouts Paid</div>
                            <div class="fw-bold text-primary">{{ $investment->days_received }} Days</div>
                        </div>
                        <div class="col-3">
                            <div class="text-muted opacity-75">Total Earned</div>
                            <div class="fw-bold text-success">+৳{{ number_format($investment->total_earned, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                <i class="bi bi-box-seam fs-1 text-secondary mb-2"></i>
                <p class="mb-0 fw-semibold">No active or past investment plans found.</p>
                <small>Explore available investment packages to start earning daily returns!</small>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($investments->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $investments->links() }}
        </div>
    @endif
</div>
@endsection
