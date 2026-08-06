@extends('layouts.auth')

@section('title', 'Register - Canada Visa Processing')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-section">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="auth-card">
                    <div class="auth-header">
                        <i class="bi bi-person-plus-fill"></i>
                        <h2>Create Account</h2>
                        <p>Register to access our visa services</p>
                    </div>

                    <div class="auth-body">
                        <form id="registerForm" method="POST" action="{{ url('/register') }}">
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person"></i> Full Name (Optional)
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="name"
                                       name="name"
                                       placeholder="Enter your full name">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       placeholder="google@mail.com"
                                       required>
                                <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">Format: google@mail.com</small>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Phone Field -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone"></i> Phone Number (Optional)
                                </label>
                                <input type="tel"
                                       class="form-control"
                                       id="phone"
                                       name="phone"
                                       maxlength="11"
                                       pattern="^01[3-9]\d{8}$"
                                       placeholder="e.g. 01712345678">
                                <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">11-digit Bangladeshi number starting with 01 (e.g. 01712345678)</small>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Invite Code Field -->
                            <div class="mb-3">
                                <label for="invite_code" class="form-label">
                                    <i class="bi bi-ticket-perforated"></i> Invite Code (Optional)
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="invite_code"
                                       name="invite_code"
                                       placeholder="Enter invite / referral code">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password <span class="text-danger">*</span>
                                </label>
                                <div class="password-wrapper">
                                    <input type="password"
                                           class="form-control"
                                           id="password"
                                           name="password"
                                           placeholder="Enter password (min 8 characters)"
                                           required>
                                    <button type="button" class="password-toggle" data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                                <div class="password-strength" id="passwordStrength"></div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="password-wrapper">
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Confirm your password"
                                           required>
                                    <button type="button" class="password-toggle" data-target="password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-danger btn-lg w-100 mt-4" id="registerBtn">
                                <i class="bi bi-person-check"></i> Register
                            </button>
                        </form>

                        <div class="auth-footer">
                            <p>Already have an account? <a href="{{ url('/login') }}">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush
