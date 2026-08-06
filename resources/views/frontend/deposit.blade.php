@extends('frontend.layouts.app')

@section('title', 'Deposit Money')

@section('content')
<div class="container py-4 px-3" style="max-width: 580px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background: #034833; border-radius: 50%; color: #fff; text-decoration: none;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark-emerald mb-0">Deposit Money</h4>
            <span class="text-muted small">Recharge your account wallet</span>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the errors below:</div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Balance Card -->
    <div class="card-emerald p-4 mb-4 shadow rounded-4 text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="small opacity-75 fw-semibold mb-1">Available Balance</div>
                <h2 class="fw-black mb-0">৳{{ number_format($balance, 2) }}</h2>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-wallet2 fs-2 text-white"></i>
            </div>
        </div>
    </div>

    <!-- Deposit Form Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <h5 class="fw-bold text-dark-emerald mb-3"><i class="bi bi-cash-coin me-2"></i> Make a Deposit</h5>

        @if($paymentMethods->isEmpty())
            <div class="alert alert-warning border-0 rounded-3 text-center py-4 mb-0">
                <i class="bi bi-exclamation-circle display-4 d-block mb-2 text-warning"></i>
                <p class="mb-0 fw-semibold">No active payment methods available right now.</p>
                <small class="text-muted">Please check back later or contact system admin.</small>
            </div>
        @else
            <form action="{{ route('user.deposit.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Select Payment Method -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark mb-2">1. Select Payment Method <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        @foreach($paymentMethods as $index => $pm)
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment_method_id" id="pm_{{ $pm->id }}" value="{{ $pm->id }}" {{ $index === 0 ? 'checked' : '' }} onchange="selectPaymentMethod({{ json_encode($pm) }})">
                                <label class="card h-100 border p-3 rounded-3 text-start cursor-pointer pm-card position-relative" for="pm_{{ $pm->id }}" style="cursor: pointer; transition: all 0.2s;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        @if($pm->image)
                                            <img src="{{ asset($pm->image) }}" alt="{{ $pm->name }}" class="rounded-2 border" style="width: 32px; height: 32px; object-fit: contain;">
                                        @else
                                            <div class="rounded-2 bg-light border d-flex align-items-center justify-content-center text-dark-emerald fw-bold" style="width: 32px; height: 32px; font-size: 0.65rem;">
                                                {{ substr($pm->name, 0, 3) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark small leading-tight">{{ $pm->name }}</div>
                                            <span class="badge {{ $pm->type === 'MFS' ? 'bg-primary' : 'bg-success' }} text-white" style="font-size: 0.65rem;">{{ $pm->type }}</span>
                                        </div>
                                    </div>
                                    <div class="small text-muted text-truncate" style="font-size: 0.75rem;">{{ $pm->number }}</div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Selected Payment Method Details Box -->
                <div id="selectedPmDetails" class="p-3 mb-4 rounded-3 border bg-light">
                    <div class="d-flex align-items-start gap-3">
                        <img id="selectedPmImage" src="" alt="PM Logo" class="rounded-3 border bg-white d-none" style="width: 65px; height: 65px; object-fit: contain;">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h6 id="selectedPmName" class="fw-bold text-dark mb-0"></h6>
                                <span id="selectedPmType" class="badge bg-dark text-white" style="font-size: 0.7rem;"></span>
                            </div>
                            <div class="small text-muted mb-2">Send Money / Deposit to Account:</div>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" id="selectedPmNumber" class="form-control fw-bold bg-white text-dark border" readonly>
                                <button type="button" class="btn btn-dark-emerald px-3" onclick="copyPaymentNumber()">
                                    <i class="bi bi-copy"></i> Copy
                                </button>
                            </div>
                            <div id="selectedPmInstruction" class="small text-muted alert alert-info border-0 p-2 mb-0" style="font-size: 0.78rem;"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Deposit Details Form -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">2. Deposit Amount ($) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-dark-emerald">$</span>
                        <input type="number" step="0.01" min="1" name="amount" class="form-control form-control-lg fw-bold" placeholder="0.00" value="{{ old('amount') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Your Name <span class="text-danger">*</span></label>
                    <input type="text" name="user_name" class="form-control" placeholder="Enter submitter account holder name" value="{{ old('user_name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Your Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="user_phone" class="form-control" placeholder="Enter sender phone number" value="{{ old('user_phone', $user->phone) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Transaction ID / Number <span class="text-danger">*</span></label>
                    <input type="text" name="transaction_id" class="form-control font-monospace" placeholder="Enter Transaction ID (e.g. 9J87XX4K)" value="{{ old('transaction_id') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Upload Payment Screenshot <span class="text-danger">*</span></label>
                    <input type="file" name="screenshot" id="screenshotInput" class="form-control" accept="image/*" required onchange="previewScreenshot(this)">
                    <div id="screenshotPreviewContainer" class="mt-2 d-none text-center">
                        <img id="screenshotPreview" src="" alt="Screenshot Preview" class="img-fluid rounded-3 border shadow-sm" style="max-height: 180px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-dark-emerald w-100 py-3 rounded-pill fw-bold shadow">
                    <i class="bi bi-send-fill me-2"></i> Submit Deposit Request
                </button>
            </form>
        @endif
    </div>

    <!-- Deposit Request History -->
    <h5 class="fw-bold text-dark-emerald mb-3"><i class="bi bi-clock-history me-2"></i> My Deposit History</h5>
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($deposits as $deposit)
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-dark-emerald text-white">{{ $deposit->payment_method_type }}</span>
                            <span class="fw-bold text-dark">{{ $deposit->payment_method_name }}</span>
                        </div>
                        <div class="small text-muted"><i class="bi bi-hash me-1"></i>TxID: <code>{{ $deposit->transaction_id }}</code></div>
                        <div class="small text-muted opacity-75">{{ $deposit->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5 text-success">+৳{{ number_format($deposit->amount, 2) }}</div>
                        @if($deposit->status === 'pending')
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill"><i class="bi bi-clock me-1"></i> Pending</span>
                        @elseif($deposit->status === 'approved')
                            <span class="badge bg-success text-white px-3 py-1 rounded-pill"><i class="bi bi-check-circle me-1"></i> Approved</span>
                        @else
                            <span class="badge bg-danger text-white px-3 py-1 rounded-pill"><i class="bi bi-x-circle me-1"></i> Declined</span>
                        @endif
                    </div>
                </div>
                @if($deposit->admin_note && $deposit->status === 'rejected')
                    <div class="mt-2 p-2 bg-danger bg-opacity-10 text-danger rounded-3 small">
                        <strong>Reason:</strong> {{ $deposit->admin_note }}
                    </div>
                @endif
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                <i class="bi bi-wallet-fill fs-1 text-success mb-2"></i>
                <p class="mb-0">No deposit history recorded yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($deposits->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $deposits->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const firstPm = @json($paymentMethods->first() ?? null);

    document.addEventListener('DOMContentLoaded', function() {
        if (firstPm) {
            selectPaymentMethod(firstPm);
        }
    });

    function selectPaymentMethod(pm) {
        document.getElementById('selectedPmName').innerText = pm.name;
        document.getElementById('selectedPmType').innerText = pm.type;
        document.getElementById('selectedPmNumber').value = pm.number;

        const imgEl = document.getElementById('selectedPmImage');
        if (pm.image) {
            imgEl.src = "{{ asset('') }}" + pm.image;
            imgEl.classList.remove('d-none');
        } else {
            imgEl.classList.add('d-none');
        }

        const instrEl = document.getElementById('selectedPmInstruction');
        if (pm.instruction && pm.instruction.trim() !== '') {
            instrEl.innerText = pm.instruction;
            instrEl.classList.remove('d-none');
        } else {
            instrEl.classList.add('d-none');
        }
    }

    function copyPaymentNumber() {
        const input = document.getElementById('selectedPmNumber');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value);

        alert('Account/Phone number copied to clipboard: ' + input.value);
    }

    function previewScreenshot(input) {
        const container = document.getElementById('screenshotPreviewContainer');
        const img = document.getElementById('screenshotPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                container.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            container.classList.add('d-none');
        }
    }
</script>
<style>
    .pm-card {
        border-color: #dee2e6 !important;
    }
    .btn-check:checked + .pm-card {
        border-color: #034833 !important;
        background-color: rgba(3, 72, 51, 0.05) !important;
        box-shadow: 0 0 0 2px rgba(3, 72, 51, 0.2);
    }
</style>
@endpush
