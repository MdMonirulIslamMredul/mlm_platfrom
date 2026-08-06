@extends('layouts.admin')

@section('admin-title', 'General Settings')

@section('admin-content')
<div class="settings-container">
    <div class="settings-header mb-4">
        <h3>General Settings</h3>
        <p class="text-muted">Manage your site's general configuration</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.general.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="site_name" class="form-label">Site Name</label>
                    <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ old('site_name', $settings->site_name) }}" required>
                    @error('site_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="site_email" class="form-label">Site Email</label>
                    <input type="email" class="form-control @error('site_email') is-invalid @enderror" id="site_email" name="site_email" value="{{ old('site_email', $settings->site_email) }}" required>
                    @error('site_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="site_phone" class="form-label">Site Phone</label>
                    <input type="text" class="form-control @error('site_phone') is-invalid @enderror" id="site_phone" name="site_phone" value="{{ old('site_phone', $settings->site_phone) }}" required>
                    @error('site_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="site_address" class="form-label">Site Address</label>
                    <textarea class="form-control @error('site_address') is-invalid @enderror" id="site_address" name="site_address" rows="3">{{ old('site_address', $settings->site_address) }}</textarea>
                    @error('site_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="footer_text" class="form-label">Footer Text</label>
                    <textarea class="form-control @error('footer_text') is-invalid @enderror" id="footer_text" name="footer_text" rows="2">{{ old('footer_text', $settings->footer_text) }}</textarea>
                    @error('footer_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-save"></i> Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
