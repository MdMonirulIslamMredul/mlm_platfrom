@extends('frontend.layouts.app')

@section('title', 'Packages & Products')

@push('styles')
<style>
    .product-card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
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
<div class="container py-4 px-3" style="max-width: 540px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #034833; border-radius: 50%; color: #fff;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h4 class="fw-bold text-dark-emerald mb-0">Packages & Products</h4>
    </div>

    <!-- Balance Banner -->
    <div class="card-emerald p-4 mb-4 shadow">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-light opacity-75 fw-semibold mb-1">Available Balance</div>
                <h2 class="fw-black mb-0 text-white">৳{{ number_format($balance, 2) }}</h2>
            </div>
            <div class="p-3 rounded-4 bg-warning text-dark">
                <i class="bi bi-box-seam-fill fs-1"></i>
            </div>
        </div>
    </div>

    <!-- Product & Packages Section -->
    <div class="mb-4">
        <div class="text-center mb-3">
            <!-- Tab Toggle -->
            <div class="d-inline-flex bg-white p-1 rounded-pill shadow-sm border border-light">
                <button class="btn btn-emerald rounded-pill px-4 py-1.5 fw-bold" style="font-size: 0.85rem;">Invest</button>
                <button class="btn btn-light rounded-pill px-4 py-1.5 text-muted fw-bold" style="font-size: 0.85rem;">Savings</button>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($packages as $index => $package)
                <div class="product-card p-3 position-relative">
                    <h5 class="fw-bold text-dark mb-3">VIP {{ $index + 1 }} - {{ $package->name }}</h5>
                    <div class="row align-items-center mb-3">
                        <!-- Product Icon -->
                        <div class="col-4 text-center">
                            <div class="bg-light rounded-4 p-3 d-flex align-items-center justify-content-center" style="height: 90px;">
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
                                <span class="position-absolute top-50 start-50 translate-middle text-white fw-bold" style="font-size: 0.65rem;">{{ $progress }}%</span>
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

    <!-- My Package Orders History Section -->
    <div class="mt-5">
        <h5 class="fw-bold text-dark-emerald mb-3"><i class="bi bi-clock-history me-2"></i> My Package Orders History</h5>
        <div class="d-flex flex-column gap-3 mb-4">
            @forelse($packageOrders as $order)
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-dark-emerald text-white">{{ $order->payment_method_type }}</span>
                                <span class="fw-bold text-dark">{{ $order->package_name }}</span>
                            </div>
                            <div class="small text-muted mb-1"><i class="bi bi-wallet2 me-1"></i>Via {{ $order->payment_method_name }}</div>
                            <div class="small text-muted"><i class="bi bi-hash me-1"></i>TxID: <code>{{ $order->transaction_id }}</code></div>
                            <div class="small text-muted opacity-75 mt-1">{{ $order->created_at->format('M d, Y H:i') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold fs-5 text-danger">৳{{ number_format($order->package_price, 2) }}</div>
                            @if($order->status === 'pending')
                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill"><i class="bi bi-clock me-1"></i> Pending Review</span>
                            @elseif($order->status === 'approved')
                                <span class="badge bg-success text-white px-3 py-1 rounded-pill"><i class="bi bi-check-circle me-1"></i> Approved</span>
                            @else
                                <span class="badge bg-danger text-white px-3 py-1 rounded-pill"><i class="bi bi-x-circle me-1"></i> Declined</span>
                            @endif
                        </div>
                    </div>
                    @if($order->admin_note && $order->status === 'rejected')
                        <div class="mt-2 p-2 bg-danger bg-opacity-10 text-danger rounded-3 small">
                            <strong>Declined Reason:</strong> {{ $order->admin_note }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                    <i class="bi bi-inbox fs-1 text-secondary mb-2"></i>
                    <p class="mb-0">No direct payment package orders recorded yet.</p>
                </div>
            @endforelse
        </div>

        @if($packageOrders->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $packageOrders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
