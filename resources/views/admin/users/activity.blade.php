@extends('layouts.admin')

@section('admin-title', 'User Activity - ' . $user->name)

@section('admin-content')
<div class="content-card mb-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 text-primary fw-bold"><i class="bi bi-activity me-2"></i>User Activity Report</h4>
            <span class="text-muted">Detailed activities & metrics for <strong>{{ $user->name }}</strong></span>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to All Users
        </a>
    </div>

    <!-- User Information Overview Card -->
    <div class="card border-0 bg-light p-3 rounded-3 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary text-white p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                        <div class="small text-muted mb-1"><i class="bi bi-envelope me-1"></i> {{ $user->email }} | <i class="bi bi-telephone me-1"></i> {{ $user->phone ?? 'N/A' }}</div>
                        <div class="badge bg-secondary">Joined: {{ $user->created_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="mb-1">
                    <span class="small text-muted">Referral Code:</span>
                    <span class="badge bg-success fs-6 ms-1">{{ $user->referral_code ?? 'N/A' }}</span>
                </div>
                @if($referrer)
                    <div class="small text-muted mt-1">
                        Invited By: <a href="{{ route('admin.users.activity', $referrer) }}" class="fw-bold text-decoration-none">{{ $referrer->name }}</a> ({{ $referrer->email }})
                    </div>
                @else
                    <div class="small text-muted mt-1">Invited By: <em>Direct Registration</em></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Activity Metric Cards Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-primary border-4 shadow-sm p-3 bg-white">
                <div class="small text-muted text-uppercase fw-semibold">Available Balance</div>
                <div class="fs-4 fw-bold text-primary">৳{{ number_format($user->balance, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-warning border-4 shadow-sm p-3 bg-white">
                <div class="small text-muted text-uppercase fw-semibold">Total Refer Bonus</div>
                <div class="fs-4 fw-bold text-warning">৳{{ number_format($user->total_refer_bonus, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-danger border-4 shadow-sm p-3 bg-white">
                <div class="small text-muted text-uppercase fw-semibold">Total User Withdrawals</div>
                <div class="fs-4 fw-bold text-danger">৳{{ number_format($totalUserWithdrawals, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-success border-4 shadow-sm p-3 bg-white">
                <div class="small text-muted text-uppercase fw-semibold">Active Investments</div>
                <div class="fs-4 fw-bold text-success">{{ $activePlans }} Plans</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-start border-info border-4 shadow-sm p-3 bg-white">
                <div class="small text-muted text-uppercase fw-semibold">Direct Team Members</div>
                <div class="fs-4 fw-bold text-info">{{ $totalTeamCount }} Users</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-start border-secondary border-4 shadow-sm p-3 bg-white">
                <div class="small text-muted text-uppercase fw-semibold">Team Total Investments</div>
                <div class="fs-4 fw-bold text-secondary">৳{{ number_format($totalTeamInvest, 2) }}</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-start border-danger border-4 shadow-sm p-3 bg-white">
                <div class="small text-muted text-uppercase fw-semibold">Team Total Withdrawals</div>
                <div class="fs-4 fw-bold text-danger">৳{{ number_format($totalTeamWithdrawals, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs for Detailed Activity Sections -->
    <ul class="nav nav-tabs mb-3" id="activityTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="team-tab" data-bs-toggle="tab" data-bs-target="#team" type="button" role="tab">
                <i class="bi bi-people me-1"></i> Referred Team Members ({{ $totalTeamCount }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="withdrawals-tab" data-bs-toggle="tab" data-bs-target="#withdrawals" type="button" role="tab">
                <i class="bi bi-box-arrow-up-right me-1"></i> Withdrawal History ({{ $withdrawals->total() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="investments-tab" data-bs-toggle="tab" data-bs-target="#investments" type="button" role="tab">
                <i class="bi bi-box-seam me-1"></i> Package Purchases ({{ $investments->total() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab">
                <i class="bi bi-list-columns-reverse me-1"></i> Transaction History ({{ $transactions->total() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="activityTabContent">
        <!-- Tab 1: Team Members List -->
        <div class="tab-pane fade show active" id="team" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Member Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Balance</th>
                            <th>Joined Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamMembers as $member)
                            <tr>
                                <td>{{ $member->id }}</td>
                                <td><span class="fw-bold">{{ $member->name }}</span></td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->phone ?? 'N/A' }}</td>
                                <td><span class="text-success fw-semibold">৳{{ number_format($member->balance, 2) }}</span></td>
                                <td>{{ $member->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.activity', $member) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-eye"></i> View Activity
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No team members registered under this user's referral code yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $teamMembers->links() }}</div>
        </div>

        <!-- Tab 2: Withdrawal Requests History -->
        <div class="tab-pane fade" id="withdrawals" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Receiving Account</th>
                            <th>Account Type</th>
                            <th>Status</th>
                            <th>Admin Note</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $w)
                            <tr>
                                <td>{{ $w->id }}</td>
                                <td><span class="fw-bold text-danger">৳{{ number_format($w->amount, 2) }}</span></td>
                                <td><span class="badge bg-dark text-white">{{ $w->payment_method_name }}</span></td>
                                <td><code>{{ $w->account_number }}</code></td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-dark">{{ $w->account_type ?? 'Personal' }}</span></td>
                                <td>
                                    @if($w->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Pending</span>
                                    @elseif($w->status === 'approved')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Approved</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Declined</span>
                                    @endif
                                </td>
                                <td><span class="small text-muted">{{ $w->admin_note ?? 'N/A' }}</span></td>
                                <td>{{ $w->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.withdrawals.index', ['status' => $w->status]) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No withdrawal requests recorded for this user yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $withdrawals->links() }}</div>
        </div>

        <!-- Tab 3: Package Purchases / Investments -->
        <div class="tab-pane fade" id="investments" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Invested Amount</th>
                            <th>Daily Return</th>
                            <th>Status</th>
                            <th>Purchased At</th>
                            <th>Expires At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($investments as $invest)
                            <tr>
                                <td>{{ $invest->id }}</td>
                                <td><span class="fw-bold text-primary">{{ $invest->package->name ?? 'Package #' . $invest->package_id }}</span></td>
                                <td>৳{{ number_format($invest->invested_amount, 2) }}</td>
                                <td>৳{{ number_format($invest->daily_return, 2) }}/day</td>
                                <td>
                                    @if($invest->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Completed</span>
                                    @endif
                                </td>
                                <td>{{ $invest->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $invest->expires_at ? \Carbon\Carbon::parse($invest->expires_at)->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No package purchases found for this user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $investments->links() }}</div>
        </div>

        <!-- Tab 3: Transaction History -->
        <div class="tab-pane fade" id="transactions" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trans)
                            <tr>
                                <td>{{ $trans->id }}</td>
                                <td>
                                    <span class="badge bg-{{ $trans->type === 'referral_bonus' ? 'warning text-dark' : ($trans->type === 'package_buy' ? 'primary' : 'secondary') }}">
                                        {{ strtoupper(str_replace('_', ' ', $trans->type)) }}
                                    </span>
                                </td>
                                <td><span class="fw-bold">৳{{ number_format($trans->amount, 2) }}</span></td>
                                <td><span class="badge bg-success">{{ ucfirst($trans->status) }}</span></td>
                                <td>{{ $trans->note ?? 'N/A' }}</td>
                                <td>{{ $trans->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No transaction logs found for this user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $transactions->links() }}</div>
        </div>
    </div>
</div>
@endsection
