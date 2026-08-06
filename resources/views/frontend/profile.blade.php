@extends('frontend.layouts.app')

@section('title', 'My Profile & Account Settings')

@section('content')
<div class="container py-4 px-3" style="max-width: 540px;">
    <!-- Page Header Bar -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-emerald-circle p-0 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #034833; border-radius: 50%; color: #fff;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h4 class="fw-bold text-dark-emerald mb-0">Profile & Settings</h4>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Profile Overview Card -->
    <div class="card-emerald p-4 mb-4 shadow">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle bg-warning p-2 text-dark d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                <i class="bi bi-person-fill display-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1 text-white">{{ $user->name ?? 'User Profile' }}</h5>
                <div class="small text-light opacity-75">{{ $user->email }}</div>
                <div class="small text-light opacity-75">{{ $user->phone ?? 'No phone added' }}</div>
            </div>
        </div>
        <hr class="my-3 border-light opacity-10">
        <div class="row text-center g-2 small">
            <div class="col-6 border-end border-light border-opacity-10">
                <div class="text-light opacity-75">Available Balance</div>
                <div class="fw-bold text-white fs-6">৳{{ number_format($user->balance, 2) }}</div>
            </div>
            <div class="col-6">
                <div class="text-light opacity-75">My Referral Code</div>
                <div class="fw-bold text-warning fs-6">{{ $user->referral_code ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 1: Update Profile Form -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h5 class="fw-bold text-dark-emerald mb-3"><i class="bi bi-person-gear me-2"></i> Edit Profile Details</h5>
        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label small fw-semibold text-muted">Full Name</label>
                <input type="text" class="form-control rounded-3" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label small fw-semibold text-muted">Phone Number (Readonly)</label>
                <input type="text" class="form-control rounded-3 bg-light text-muted" id="phone" value="{{ $user->phone ?? 'N/A' }}" readonly>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label small fw-semibold text-muted">Email Address (Readonly)</label>
                <input type="email" class="form-control rounded-3 bg-light text-muted" id="email" value="{{ $user->email }}" readonly>
            </div>

            <button type="submit" class="btn btn-emerald w-100 rounded-pill py-2 fw-bold">
                <i class="bi bi-check2-circle me-1"></i> Save Profile Details
            </button>
        </form>
    </div>

    <!-- Section 2: Change Password Form -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h5 class="fw-bold text-dark-emerald mb-3"><i class="bi bi-shield-lock me-2"></i> Change Password</h5>
        <form action="{{ route('user.profile.password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="current_password" class="form-label small fw-semibold text-muted">Current Password</label>
                <input type="password" class="form-control rounded-3" id="current_password" name="current_password" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-muted">New Password</label>
                <input type="password" class="form-control rounded-3" id="password" name="password" required minlength="8">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label small fw-semibold text-muted">Confirm New Password</label>
                <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation" required minlength="8">
            </div>

            <button type="submit" class="btn btn-warning w-100 rounded-pill py-2 fw-bold text-dark">
                <i class="bi bi-key me-1"></i> Update Password
            </button>
        </form>
    </div>
</div>
@endsection
