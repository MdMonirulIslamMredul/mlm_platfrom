@extends('frontend.layouts.app')

@section('title', 'Total Refer Bonus History')

@section('content')
<div class="container py-4 px-3" style="max-width: 580px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background: #034833; border-radius: 50%; color: #fff; text-decoration: none;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark-emerald mb-0">Total Refer Bonus</h4>
            <span class="text-muted small">Detailed referral commission logs</span>
        </div>
    </div>

    <!-- Summary Banner -->
    <div class="card-emerald p-4 mb-4 shadow rounded-4 text-white">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-light opacity-75 fw-semibold mb-1">Total Earned Referral Bonus</div>
                <h2 class="fw-black mb-0">৳{{ number_format($user->total_refer_bonus, 2) }}</h2>
            </div>
            <div class="p-3 rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-heart-fill fs-2 text-white"></i>
            </div>
        </div>
    </div>

    <!-- Bonus Records List -->
    <h5 class="fw-bold text-dark-emerald mb-3"><i class="bi bi-gift me-2"></i> Commission Records</h5>
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($bonuses as $bonus)
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1 me-2">
                        <!-- Top Pill Badge -->
                        <div class="mb-2">
                            <span class="badge text-white px-3 py-1.5 rounded-3 fw-bold" style="background-color: #034833 !important; font-size: 0.8rem;">
                                {{ number_format($bonus->bonus_percentage, 0) }}% Referral Bonus
                            </span>
                        </div>
                        
                        <!-- User Name & Phone with Info Button -->
                        <div class="d-flex align-items-center flex-wrap gap-1 text-dark mb-1">
                            <span class="text-secondary opacity-75 small">From:</span>
                            <strong class="text-dark fs-6">{{ $bonus->fromUser->name ?? ('User #' . $bonus->from_user_id) }}</strong>
                            @if($bonus->fromUser && $bonus->fromUser->phone)
                                <span class="text-muted small">({{ $bonus->fromUser->phone }})</span>
                            @endif
                            <button type="button" class="btn btn-sm btn-light text-success border-0 rounded-circle p-0 d-inline-flex align-items-center justify-content-center ms-1" style="width: 26px; height: 26px; background-color: #e6f4ea;" data-bs-toggle="modal" data-bs-target="#userModal_bonus_{{ $bonus->id }}" title="View Team Member Info">
                                <i class="bi bi-info-circle-fill fs-6" style="color: #034833;"></i>
                            </button>
                        </div>
                        
                        <!-- Package Title & Date -->
                        <div class="small text-muted mb-1">
                            Package: <strong>{{ $bonus->package_name }}</strong> (৳{{ number_format($bonus->package_price, 2) }})
                        </div>
                        <div class="small text-muted opacity-75">
                            {{ $bonus->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                    
                    <!-- Right Side Status & Amount -->
                    <div class="text-end">
                        <div class="fw-bold fs-4" style="color: #034833;">+৳{{ number_format($bonus->bonus_amount, 2) }}</div>
                        <span class="badge text-white px-3 py-1.5 rounded-pill fw-semibold" style="background-color: #034833 !important; font-size: 0.75rem;">Completed</span>
                    </div>
                </div>
            </div>

            <!-- Team Member Details Modal -->
            <div class="modal fade" id="userModal_bonus_{{ $bonus->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold text-dark-emerald">
                                <i class="bi bi-person-circle me-2 text-primary"></i> Team Member Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-3 text-start">
                            @if($bonus->fromUser)
                                <div class="text-center mb-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-2" style="width: 55px; height: 55px;">
                                        <i class="bi bi-person-fill fs-2" style="color: #034833;"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">{{ $bonus->fromUser->name ?? 'User #' . $bonus->fromUser->id }}</h5>
                                    <span class="badge text-white rounded-pill px-3 py-1 mt-1" style="background-color: #034833;">Referred Member</span>
                                </div>
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="row g-2">
                                        <div class="col-5 text-muted small">User ID:</div>
                                        <div class="col-7 fw-bold text-dark text-end small">#{{ $bonus->fromUser->id }}</div>

                                        <div class="col-5 text-muted small">Full Name:</div>
                                        <div class="col-7 fw-bold text-dark text-end small">{{ $bonus->fromUser->name ?? 'N/A' }}</div>

                                        <div class="col-5 text-muted small">Phone Number:</div>
                                        <div class="col-7 fw-bold text-dark text-end font-monospace small">{{ $bonus->fromUser->phone ?? 'N/A' }}</div>

                                        <div class="col-5 text-muted small">Email Address:</div>
                                        <div class="col-7 fw-bold text-dark text-end small text-truncate">{{ $bonus->fromUser->email ?? 'N/A' }}</div>

                                        <div class="col-5 text-muted small">Joined Date:</div>
                                        <div class="col-7 text-dark text-end small">{{ $bonus->fromUser->created_at ? $bonus->fromUser->created_at->format('M d, Y') : 'N/A' }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning border-0 rounded-3 mb-0">
                                    Team member #{{ $bonus->from_user_id }} profile details are unavailable.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            @if($fallbackBonuses && $fallbackBonuses->count() > 0)
                @foreach($fallbackBonuses as $tx)
                    @php
                        $fromUser = null;
                        if (preg_match('/user #(\d+)/i', $tx->description, $matches)) {
                            $fromUser = \App\Models\User::find($matches[1]);
                        }
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white mb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1 me-2">
                                <!-- Top Pill Badge -->
                                <div class="mb-2">
                                    <span class="badge text-white px-3 py-1.5 rounded-3 fw-bold" style="background-color: #034833 !important; font-size: 0.8rem;">
                                        10% Referral Bonus
                                    </span>
                                </div>

                                <!-- User Name & Phone with Info Button -->
                                <div class="d-flex align-items-center flex-wrap gap-1 text-dark mb-1">
                                    <span class="text-secondary opacity-75 small">From:</span>
                                    <strong class="text-dark fs-6">{{ $fromUser->name ?? ($fromUser ? 'User #' . $fromUser->id : 'Team Member') }}</strong>
                                    @if($fromUser && $fromUser->phone)
                                        <span class="text-muted small">({{ $fromUser->phone }})</span>
                                    @endif
                                    @if($fromUser)
                                        <button type="button" class="btn btn-sm text-success border-0 rounded-circle p-0 d-inline-flex align-items-center justify-content-center ms-1" style="width: 26px; height: 26px; background-color: #e6f4ea;" data-bs-toggle="modal" data-bs-target="#userModal_tx_{{ $tx->id }}" title="View Team Member Info">
                                            <i class="bi bi-info-circle-fill fs-6" style="color: #034833;"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Description & Date -->
                                <div class="small text-muted mb-1">{{ $tx->description }}</div>
                                <div class="small text-muted opacity-75">
                                    {{ $tx->created_at->format('M d, Y H:i') }}
                                </div>
                            </div>

                            <!-- Right Side Status & Amount -->
                            <div class="text-end">
                                <div class="fw-bold fs-4" style="color: #034833;">+৳{{ number_format($tx->amount, 2) }}</div>
                                <span class="badge text-white px-3 py-1.5 rounded-pill fw-semibold" style="background-color: #034833 !important; font-size: 0.75rem;">Completed</span>
                            </div>
                        </div>
                    </div>

                    @if($fromUser)
                        <!-- Fallback User Details Modal -->
                        <div class="modal fade" id="userModal_tx_{{ $tx->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold text-dark-emerald">
                                            <i class="bi bi-person-circle me-2 text-primary"></i> Team Member Info
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-3 text-start">
                                        <div class="text-center mb-3">
                                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-2" style="width: 55px; height: 55px;">
                                                <i class="bi bi-person-fill fs-2" style="color: #034833;"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-0">{{ $fromUser->name ?? 'User #' . $fromUser->id }}</h5>
                                            <span class="badge text-white rounded-pill px-3 py-1 mt-1" style="background-color: #034833;">Referred Member</span>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 border">
                                            <div class="row g-2">
                                                <div class="col-5 text-muted small">User ID:</div>
                                                <div class="col-7 fw-bold text-dark text-end small">#{{ $fromUser->id }}</div>

                                                <div class="col-5 text-muted small">Full Name:</div>
                                                <div class="col-7 fw-bold text-dark text-end small">{{ $fromUser->name ?? 'N/A' }}</div>

                                                <div class="col-5 text-muted small">Phone Number:</div>
                                                <div class="col-7 fw-bold text-dark text-end font-monospace small">{{ $fromUser->phone ?? 'N/A' }}</div>

                                                <div class="col-5 text-muted small">Email Address:</div>
                                                <div class="col-7 fw-bold text-dark text-end small text-truncate">{{ $fromUser->email ?? 'N/A' }}</div>

                                                <div class="col-5 text-muted small">Joined Date:</div>
                                                <div class="col-7 text-dark text-end small">{{ $fromUser->created_at ? $fromUser->created_at->format('M d, Y') : 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                    <i class="bi bi-gift-fill fs-1 text-secondary mb-2"></i>
                    <p class="mb-0 fw-semibold">No referral bonus records earned yet.</p>
                    <small>Share your referral code or link with friends to earn 10% commission on package investments!</small>
                </div>
            @endif
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bonuses->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $bonuses->links() }}
        </div>
    @elseif($fallbackBonuses && $fallbackBonuses->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $fallbackBonuses->links() }}
        </div>
    @endif
</div>
@endsection
