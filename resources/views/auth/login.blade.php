@extends('layouts.auth')

@section('title', 'Login - Immigration and Citizenship')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-section">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <h2>Welcome Back</h2>
                        <p>Login to access your account</p>
                    </div>

                    <div class="auth-body">
                        <form id="loginForm" method="POST" action="{{ url('/login') }}">
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email Address <span class="text-accent">*</span>
                                </label>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       placeholder="Enter your email"
                                       required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password <span class="text-accent">*</span>
                                </label>
                                <div class="password-wrapper">
                                    <input type="password"
                                           class="form-control"
                                           id="password"
                                           name="password"
                                           placeholder="Enter your password"
                                           required>
                                    <button type="button" class="password-toggle" data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="remember"
                                           name="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="forgot-password">Forgot Password?</a>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-auth btn-lg w-100 mt-4" id="loginBtn">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>

                        <div class="auth-footer">
                            <p>Don't have an account? <a href="{{ url('/register') }}">Register here</a></p>
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
