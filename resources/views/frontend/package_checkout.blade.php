@extends('frontend.layouts.app')

@section('title', 'Package Payment Checkout')

@section('content')
<div class="container py-4 px-3" style="max-width: 580px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('user.packages') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background: #034833; border-radius: 50%; color: #fff; text-decoration: none;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark-emerald mb-0">Package Checkout</h4>
            <span class="text-muted small">Direct Payment for Investment Plan</span>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
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

    <!-- Package Summary Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-1">SELECTED PACKAGE</span>
                <h4 class="fw-bold text-dark-emerald mb-0">{{ $package->name }}</h4>
            </div>
            <div class="text-end">
                <div class="small text-muted">Price</div>
                <h3 class="fw-black text-danger mb-0">৳{{ number_format($package->price, 2) }}</h3>
            </div>
        </div>
        <div class="row text-center g-2 bg-light p-3 rounded-3">
            <div class="col-4 border-end">
                <div class="small text-muted">Cycle</div>
                <div class="fw-bold text-dark">{{ $package->cycle_days }} Days</div>
            </div>
            <div class="col-4 border-end">
                <div class="small text-muted">Daily Return</div>
                <div class="fw-bold text-danger">৳{{ number_format($package->daily_return, 2) }}</div>
            </div>
            <div class="col-4">
                <div class="small text-muted">Total Return</div>
                <div class="fw-bold text-success">৳{{ number_format($package->daily_return * $package->cycle_days, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Direct Payment Form -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <h5 class="fw-bold text-dark-emerald mb-3"><i class="bi bi-credit-card-2-front me-2"></i> Payment Details</h5>

        @if($paymentMethods->isEmpty())
            <div class="alert alert-warning border-0 rounded-3 text-center py-4 mb-0">
                <i class="bi bi-exclamation-circle display-4 d-block mb-2 text-warning"></i>
                <p class="mb-0 fw-semibold">No active payment methods available right now.</p>
                <small class="text-muted">Please contact system admin.</small>
            </div>
        @else
            <form action="{{ route('user.packages.store_order', $package->id) }}" method="POST" enctype="multipart/form-data">
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
                        <img id="selectedPmImage" src="" alt="PM Logo" class="rounded-3 border bg-white d-none" style="width: 150px; height: 150px; object-fit: contain;">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h6 id="selectedPmName" class="fw-bold text-dark mb-0"></h6>
                                <span id="selectedPmType" class="badge bg-dark text-white" style="font-size: 0.7rem;"></span>
                            </div>
                            <div class="small text-muted mb-2">Send Payment to Account:</div>
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

                <!-- Step 2: Form Inputs -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Your Name <span class="text-danger">*</span></label>
                    <input type="text" name="user_name" class="form-control" placeholder="Enter submitter name" value="{{ old('user_name', $user->name) }}" required>
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
                    <label class="form-label fw-bold text-dark">Upload Payment Screenshot Receipt <span class="text-danger">*</span></label>
                    <input type="file" name="screenshot" id="screenshotInput" class="form-control" accept="image/*" required onchange="previewScreenshot(this)">
                    <div id="screenshotPreviewContainer" class="mt-2 d-none text-center">
                        <img id="screenshotPreview" src="" alt="Screenshot Preview" class="img-fluid rounded-3 border shadow-sm" style="max-height: 180px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-dark-emerald w-100 py-3 rounded-pill fw-bold shadow">
                    <i class="bi bi-check-circle-fill me-2"></i> Submit Package Order (৳{{ number_format($package->price, 2) }})
                </button>
            </form>
        @endif
    </div>
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
