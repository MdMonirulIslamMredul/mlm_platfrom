@extends('layouts.admin')

@section('admin-title', 'Withdrawal Requests')

@section('admin-content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Withdrawal Requests</h3>
            <p class="text-muted small mb-0">Check user receiving details and approve or decline withdrawal requests</p>
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
        <a href="{{ route('admin.withdrawals.index', ['status' => 'all']) }}" 
           class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold position-relative shadow-sm">
            All Requests <span class="badge {{ $status === 'all' ? 'bg-white text-primary' : 'bg-secondary' }} ms-1">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" 
           class="btn {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold position-relative shadow-sm">
            Pending <span class="badge bg-dark text-white ms-1">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('admin.withdrawals.index', ['status' => 'approved']) }}" 
           class="btn {{ $status === 'approved' ? 'btn-success' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold position-relative shadow-sm">
            Approved <span class="badge {{ $status === 'approved' ? 'bg-white text-success' : 'bg-secondary' }} ms-1">{{ $approvedCount }}</span>
        </a>
        <a href="{{ route('admin.withdrawals.index', ['status' => 'rejected']) }}" 
           class="btn {{ $status === 'rejected' ? 'btn-danger' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold position-relative shadow-sm">
            Declined <span class="badge {{ $status === 'rejected' ? 'bg-white text-danger' : 'bg-secondary' }} ms-1">{{ $rejectedCount }}</span>
        </a>
    </div>

    <!-- Withdrawal Requests Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Receiving Account</th>
                            <th>Account Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $w)
                            <tr>
                                <td class="ps-4">
                                    @if($w->user)
                                        <a href="{{ route('admin.users.activity', $w->user->id) }}" 
                                           class="btn btn-link p-0 text-start text-decoration-none fw-bold text-dark hover-primary">
                                            <span class="text-primary text-decoration-underline">{{ $w->user->name }}</span>
                                            <i class="bi bi-box-arrow-up-right text-primary ms-1 small" style="font-size: 0.75rem;"></i>
                                            <div class="small text-muted fw-normal">{{ $w->user->email ?? '' }}</div>
                                        </a>
                                    @else
                                        <div class="fw-bold text-dark">User #{{ $w->user_id }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="fs-5 fw-bold text-danger">৳{{ number_format($w->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-dark text-white me-1"><i class="bi bi-bank me-1"></i>{{ $w->payment_method_name }}</span>
                                </td>
                                <td>
                                    <code class="fs-6 fw-bold text-dark bg-light px-2 py-1 rounded border"><i class="bi bi-telephone me-1"></i>{{ $w->account_number }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-dark px-2.5 py-1 rounded-pill">{{ $w->account_type ?? 'Personal' }}</span>
                                </td>
                                <td>
                                    @if($w->status === 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-semibold"><i class="bi bi-clock me-1"></i> Pending</span>
                                    @elseif($w->status === 'approved')
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-semibold"><i class="bi bi-check-circle me-1"></i> Approved</span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-semibold"><i class="bi bi-x-circle me-1"></i> Declined</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small text-muted" title="{{ $w->created_at->format('Y-m-d H:i:s') }}">
                                        {{ $w->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    @if($w->status === 'pending')
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal{{ $w->id }}">
                                                <i class="bi bi-check-lg me-1"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $w->id }}">
                                                <i class="bi bi-x-lg me-1"></i> Decline
                                            </button>
                                        </div>
                                    @else
                                        <span class="small text-muted fst-italic">{{ $w->admin_note ? Str::limit($w->admin_note, 30) : 'Completed' }}</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Approve Modal -->
                            @if($w->status === 'pending')
                                <div class="modal fade" id="approveModal{{ $w->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-success"><i class="bi bi-check-circle-fill me-2"></i> Approve Withdrawal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body py-3">
                                                    <p class="text-muted">Are you sure you want to approve the withdrawal of <strong class="text-danger">৳{{ number_format($w->amount, 2) }}</strong> for <strong class="text-dark">{{ $w->user->name ?? 'User' }}</strong>?</p>
                                                    <div class="bg-light p-3 rounded-3 mb-3 text-start small">
                                                        <div><strong>Method:</strong> {{ $w->payment_method_name }}</div>
                                                        <div><strong>Account:</strong> {{ $w->account_number }} ({{ $w->account_type ?? 'Personal' }})</div>
                                                    </div>
                                                    <div class="mb-3 text-start">
                                                        <label class="form-label small fw-semibold text-dark">Admin Note (Optional)</label>
                                                        <input type="text" name="admin_note" class="form-control rounded-3" placeholder="Payout sent via bKash/Nagad agent...">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Confirm Approval</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $w->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle-fill me-2"></i> Decline Withdrawal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body py-3">
                                                    <p class="text-muted">Declining will automatically refund <strong class="text-success">৳{{ number_format($w->amount, 2) }}</strong> back to <strong>{{ $w->user->name ?? 'User' }}</strong>'s account balance.</p>
                                                    <div class="mb-3 text-start">
                                                        <label class="form-label small fw-semibold text-dark">Reason for Decline / Admin Note</label>
                                                        <textarea name="admin_note" class="form-control rounded-3" rows="3" placeholder="Incorrect account number, insufficient details, etc."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Confirm Decline & Refund</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                    No withdrawal requests found for this filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($withdrawals->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $withdrawals->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
