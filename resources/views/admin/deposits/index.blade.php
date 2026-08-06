@extends('layouts.admin')

@section('admin-title', 'Deposit Requests')

@section('admin-content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Deposit Requests</h3>
            <p class="text-muted small mb-0">Check, verify screenshots, and approve or decline user deposit requests</p>
        </div>
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

    <!-- Status Filter Navigation Pills -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.deposits.index', ['status' => 'all']) }}" 
           class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold position-relative shadow-sm">
            All Requests <span class="badge {{ $status === 'all' ? 'bg-white text-primary' : 'bg-secondary' }} ms-1">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}" 
           class="btn {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold position-relative shadow-sm">
            Pending <span class="badge bg-dark text-white ms-1">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('admin.deposits.index', ['status' => 'approved']) }}" 
           class="btn {{ $status === 'approved' ? 'btn-success' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold position-relative shadow-sm">
            Approved <span class="badge {{ $status === 'approved' ? 'bg-white text-success' : 'bg-secondary' }} ms-1">{{ $approvedCount }}</span>
        </a>
        <a href="{{ route('admin.deposits.index', ['status' => 'rejected']) }}" 
           class="btn {{ $status === 'rejected' ? 'btn-danger' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold position-relative shadow-sm">
            Declined <span class="badge {{ $status === 'rejected' ? 'bg-white text-danger' : 'bg-secondary' }} ms-1">{{ $rejectedCount }}</span>
        </a>
    </div>

    <!-- Deposit Requests Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">User</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Submitted Name & Phone</th>
                            <th>Transaction ID</th>
                            <th>Screenshot</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $dep)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $dep->user->name ?? 'User #' . $dep->user_id }}</div>
                                    <div class="small text-muted">{{ $dep->user->email ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="fs-5 fw-bold text-success">৳{{ number_format($dep->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $dep->payment_method_type === 'MFS' ? 'bg-primary' : 'bg-success' }} text-white me-1">{{ $dep->payment_method_type }}</span>
                                    <strong class="text-dark">{{ $dep->payment_method_name }}</strong>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $dep->user_name }}</div>
                                    <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $dep->user_phone }}</div>
                                </td>
                                <td>
                                    <code class="fs-6 fw-bold text-dark bg-light px-2 py-1 rounded border">{{ $dep->transaction_id }}</code>
                                </td>
                                <td>
                                    @if($dep->screenshot)
                                        <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-3" data-bs-toggle="modal" data-bs-target="#screenshotModal{{ $dep->id }}">
                                            <img src="{{ asset($dep->screenshot) }}" alt="Receipt" style="width: 45px; height: 45px; object-fit: cover;" class="rounded border">
                                        </button>
                                    @else
                                        <span class="text-muted small">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    @if($dep->status === 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-semibold"><i class="bi bi-clock me-1"></i> Pending</span>
                                    @elseif($dep->status === 'approved')
                                        <span class="badge bg-success px-3 py-1.5 rounded-pill fw-semibold"><i class="bi bi-check-circle me-1"></i> Approved</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-1.5 rounded-pill fw-semibold"><i class="bi bi-x-circle me-1"></i> Declined</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $dep->created_at->format('M d, Y H:i') }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-sm btn-info text-white rounded-2 me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $dep->id }}">
                                        <i class="bi bi-eye"></i> Details
                                    </button>
                                    @if($dep->status === 'pending')
                                        <button class="btn btn-sm btn-success rounded-2 me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $dep->id }}">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $dep->id }}">
                                            <i class="bi bi-x-lg"></i> Decline
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Details Popup Modal -->
                            <div class="modal fade" id="detailsModal{{ $dep->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark">
                                                <i class="bi bi-info-circle me-2 text-primary"></i> Deposit Request Details (#{{ $dep->id }})
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-3">
                                            <div class="row g-3">
                                                <!-- User Account Info -->
                                                <div class="col-md-6">
                                                    <div class="p-3 bg-light rounded-3 h-100 border">
                                                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-person-circle me-1"></i> User Account Details</h6>
                                                        <div class="small mb-1"><strong>User ID:</strong> #{{ $dep->user_id }}</div>
                                                        <div class="small mb-1"><strong>Account Name:</strong> {{ $dep->user->name ?? 'N/A' }}</div>
                                                        <div class="small mb-1"><strong>Account Email:</strong> {{ $dep->user->email ?? 'N/A' }}</div>
                                                        <div class="small mb-1"><strong>Account Phone:</strong> {{ $dep->user->phone ?? 'N/A' }}</div>
                                                        <div class="small"><strong>Current Balance:</strong> ৳{{ number_format($dep->user->balance ?? 0, 2) }}</div>
                                                    </div>
                                                </div>

                                                <!-- Submitter & Payment Info -->
                                                <div class="col-md-6">
                                                    <div class="p-3 bg-light rounded-3 h-100 border">
                                                        <h6 class="fw-bold text-success mb-2"><i class="bi bi-credit-card me-1"></i> Deposit & Payment Info</h6>
                                                        <div class="small mb-1"><strong>Deposit Amount:</strong> <span class="fs-6 fw-bold text-success">৳{{ number_format($dep->amount, 2) }}</span></div>
                                                        <div class="small mb-1"><strong>Payment Method:</strong> {{ $dep->payment_method_name }} ({{ $dep->payment_method_type }})</div>
                                                        <div class="small mb-1"><strong>Method Account #:</strong> <code>{{ $dep->paymentMethod->number ?? 'N/A' }}</code></div>
                                                        <div class="small mb-1"><strong>Submitted Name:</strong> {{ $dep->user_name }}</div>
                                                        <div class="small mb-1"><strong>Submitted Phone:</strong> {{ $dep->user_phone }}</div>
                                                        <div class="small mb-1"><strong>Transaction ID:</strong> <code class="fw-bold text-primary">{{ $dep->transaction_id }}</code></div>
                                                        <div class="small mb-1"><strong>Status:</strong> 
                                                            @if($dep->status === 'pending')
                                                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Pending</span>
                                                            @elseif($dep->status === 'approved')
                                                                <span class="badge bg-success text-white px-2 py-1 rounded-pill">Approved</span>
                                                            @else
                                                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill">Declined</span>
                                                            @endif
                                                        </div>
                                                        <div class="small"><strong>Requested At:</strong> {{ $dep->created_at->format('M d, Y h:i A') }}</div>
                                                    </div>
                                                </div>

                                                @if($dep->admin_note)
                                                    <div class="col-12">
                                                        <div class="p-3 bg-warning bg-opacity-10 text-dark rounded-3 border border-warning border-opacity-25 small">
                                                            <strong>Admin Note:</strong> {{ $dep->admin_note }}
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Payment Screenshot Receipt -->
                                                @if($dep->screenshot)
                                                    <div class="col-12 text-center">
                                                        <h6 class="fw-bold text-dark text-start mb-2"><i class="bi bi-file-image me-1"></i> Payment Receipt Screenshot</h6>
                                                        <img src="{{ asset($dep->screenshot) }}" alt="Payment Receipt" class="img-fluid rounded-3 border shadow-sm" style="max-height: 350px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            @if($dep->status === 'pending')
                                                <button type="button" class="btn btn-success rounded-3 me-2" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#approveModal{{ $dep->id }}">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </button>
                                                <button type="button" class="btn btn-outline-danger rounded-3 me-auto" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $dep->id }}">
                                                    <i class="bi bi-x-lg"></i> Decline
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Screenshot Lightbox Modal -->
                            @if($dep->screenshot)
                                <div class="modal fade" id="screenshotModal{{ $dep->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-dark">
                                                    <i class="bi bi-image me-2 text-primary"></i> Payment Screenshot Receipt (TxID: {{ $dep->transaction_id }})
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center py-3">
                                                <img src="{{ asset($dep->screenshot) }}" alt="Payment Screenshot" class="img-fluid rounded-3 border shadow-sm" style="max-height: 500px;">
                                                <div class="mt-3 p-3 bg-light rounded-3 text-start">
                                                    <div class="row g-2">
                                                        <div class="col-6"><strong>User:</strong> {{ $dep->user->name ?? 'User #' . $dep->user_id }}</div>
                                                        <div class="col-6"><strong>Amount:</strong> ৳{{ number_format($dep->amount, 2) }}</div>
                                                        <div class="col-6"><strong>Payment Method:</strong> {{ $dep->payment_method_name }} ({{ $dep->payment_method_type }})</div>
                                                        <div class="col-6"><strong>Sender Phone:</strong> {{ $dep->user_phone }}</div>
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

                            <!-- Approve Modal -->
                            @if($dep->status === 'pending')
                                <div class="modal fade" id="approveModal{{ $dep->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('admin.deposits.approve', $dep->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-success"><i class="bi bi-check-circle-fill me-2"></i> Confirm Approve Deposit</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body py-3 text-start">
                                                    <p class="text-dark">Are you sure you want to approve this deposit of <strong class="text-success">৳{{ number_format($dep->amount, 2) }}</strong> for user <strong>{{ $dep->user->name ?? 'User' }}</strong>?</p>
                                                    <div class="alert alert-info border-0 rounded-3 mb-3">
                                                        <small><i class="bi bi-info-circle me-1"></i> Approving will immediately credit <strong>৳{{ number_format($dep->amount, 2) }}</strong> to the user's account balance and record a deposit transaction log.</small>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold text-dark">Admin Note (Optional)</label>
                                                        <input type="text" name="admin_note" class="form-control rounded-3" value="Deposit approved by admin" placeholder="Note to user or records">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success rounded-3 px-4">Yes, Approve Deposit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Decline / Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $dep->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('admin.deposits.reject', $dep->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle-fill me-2"></i> Decline Deposit Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body py-3 text-start">
                                                    <p class="text-dark">Are you sure you want to decline this deposit request of <strong class="text-danger">৳{{ number_format($dep->amount, 2) }}</strong> from <strong>{{ $dep->user_name }}</strong> (TxID: <code>{{ $dep->transaction_id }}</code>)?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold text-dark">Reason for Declining (Optional)</label>
                                                        <textarea name="admin_note" class="form-control rounded-3" rows="3" placeholder="e.g. Invalid Transaction ID, Screenshot unreadable, amount mismatch"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger rounded-3 px-4">Decline Deposit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-4 d-block text-secondary mb-2"></i>
                                    No deposit requests found in this category.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($deposits->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $deposits->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
