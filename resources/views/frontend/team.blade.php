@extends('frontend.layouts.app')

@section('title', 'Total Refer Count - My Team')

@section('content')
<div class="container py-4 px-3" style="max-width: 540px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #034833; border-radius: 50%; color: #fff;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h4 class="fw-bold text-dark-emerald mb-0">Total Refer Count</h4>
    </div>

    <!-- Summary Banner -->
    <div class="card-emerald p-4 mb-4 shadow">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-light opacity-75 fw-semibold mb-1">Direct Team Referrals</div>
                <h2 class="fw-black mb-0 text-white">{{ $totalTeam }} Members</h2>
            </div>
            <div class="p-3 rounded-4 bg-warning text-dark">
                <i class="bi bi-people-fill fs-1"></i>
            </div>
        </div>
    </div>

    <!-- Referrals List -->
    <div class="d-flex flex-column gap-3">
        @forelse($teamMembers as $member)
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm rounded-circle bg-success bg-opacity-10 p-2 text-success fw-bold d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-person-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $member->name ?? 'Team Member' }}</h6>
                            <small class="text-muted">{{ $member->email }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                        <div class="small text-muted opacity-75 mt-1">{{ $member->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                <i class="bi bi-person-plus fs-1 text-warning mb-2"></i>
                <p class="mb-0">No direct referrals yet. Share your invite code to build your team!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $teamMembers->links() }}
    </div>
</div>
@endsection
