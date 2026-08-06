@extends('layouts.admin')

@section('admin-title', 'Payment Methods')

@section('admin-content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Payment Methods</h3>
            <p class="text-muted small mb-0">Manage MFS and Bank deposit methods for users</p>
        </div>
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
            <i class="bi bi-plus-circle"></i> Add Payment Method
        </button>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Payment Methods Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Image / QR</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Account / Mobile Number</th>
                            <th>Instruction</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentMethods as $pm)
                            <tr>
                                <td class="ps-4">
                                    @if($pm->image)
                                        <img src="{{ asset($pm->image) }}" alt="{{ $pm->name }}" class="rounded-3 border" style="width: 50px; height: 50px; object-fit: contain; background: #f8f9fa;">
                                    @else
                                        <div class="rounded-3 border bg-light text-muted d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px; font-size: 0.75rem;">
                                            NO LOGO
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $pm->name }}</strong>
                                </td>
                                <td>
                                    @if($pm->type === 'MFS')
                                        <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill fw-semibold">
                                            <i class="bi bi-phone me-1"></i> MFS
                                        </span>
                                    @else
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-semibold">
                                            <i class="bi bi-bank me-1"></i> Bank
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <code class="fs-6 fw-bold text-dark px-2 py-1 bg-light border rounded-2">{{ $pm->number }}</code>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ Str::limit($pm->instruction ?? 'N/A', 40) }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.payment-methods.toggle', $pm->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm border-0 bg-transparent p-0">
                                            @if($pm->is_active)
                                                <span class="badge bg-success px-3 py-1.5 rounded-pill"><i class="bi bi-check-circle me-1"></i> Active</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-1.5 rounded-pill"><i class="bi bi-x-circle me-1"></i> Inactive</span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-sm btn-outline-primary rounded-2 me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editPaymentMethodModal{{ $pm->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.payment-methods.destroy', $pm->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete {{ $pm->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-2">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal for {{ $pm->name }} -->
                            <div class="modal fade" id="editPaymentMethodModal{{ $pm->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <form action="{{ route('admin.payment-methods.update', $pm->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Payment Method</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Method Type <span class="text-danger">*</span></label>
                                                    <select name="type" class="form-select rounded-3" required>
                                                        <option value="MFS" {{ $pm->type === 'MFS' ? 'selected' : '' }}>MFS (Mobile Financial Services e.g. bKash, Nagad, Rocket)</option>
                                                        <option value="Bank" {{ $pm->type === 'Bank' ? 'selected' : '' }}>Bank (Bank Account)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Name / Provider <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control rounded-3" value="{{ $pm->name }}" placeholder="e.g. bKash Personal, City Bank" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Account / Phone Number <span class="text-danger">*</span></label>
                                                    <input type="text" name="number" class="form-control rounded-3" value="{{ $pm->number }}" placeholder="e.g. 01712345678 or AC# 123456789" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Logo / QR Image</label>
                                                    @if($pm->image)
                                                        <div class="mb-2">
                                                            <img src="{{ asset($pm->image) }}" alt="Preview" class="rounded border" style="max-height: 70px;">
                                                        </div>
                                                    @endif
                                                    <input type="file" name="image" class="form-control rounded-3" accept="image/*">
                                                    <small class="text-muted">Leave empty to keep existing image</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Deposit Instructions / Note</label>
                                                    <textarea name="instruction" class="form-control rounded-3" rows="3" placeholder="e.g. Send Money to this personal bKash number with your user email in reference.">{{ $pm->instruction }}</textarea>
                                                </div>
                                                <div class="form-check form-switch mb-2">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editActive{{ $pm->id }}" {{ $pm->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="editActive{{ $pm->id }}">Active Status</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-3 px-4">Update Payment Method</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-wallet2 display-4 d-block text-secondary mb-2"></i>
                                    No payment methods created yet. Click <strong>Add Payment Method</strong> to add your first one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($paymentMethods->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $paymentMethods->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create Payment Method Modal -->
<div class="modal fade" id="addPaymentMethodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i> Add Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Method Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select rounded-3" required>
                            <option value="MFS">MFS (Mobile Financial Services e.g. bKash, Nagad, Rocket)</option>
                            <option value="Bank">Bank (Bank Account / Wire Transfer)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Name / Provider <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. bKash Personal, Nagad Agent, Islami Bank" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Account / Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="number" class="form-control rounded-3" placeholder="e.g. 01700000000 or A/C 2050..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Logo / QR Code Image</label>
                        <input type="file" name="image" class="form-control rounded-3" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Deposit Instructions / Note</label>
                        <textarea name="instruction" class="form-control rounded-3" rows="3" placeholder="Instructions shown to users when depositing..."></textarea>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="addActive" checked>
                        <label class="form-check-label fw-semibold" for="addActive">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Save Payment Method</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
