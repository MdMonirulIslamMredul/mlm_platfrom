@extends('layouts.admin')

@section('admin-title', 'Contact Us Settings')

@section('admin-content')
<div class="settings-container">
    <div class="settings-header mb-4">
        <h3>Contact Us Settings</h3>
        <p class="text-muted">Manage contact information and social media links</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.contact.update') }}" method="POST">
                @csrf

                <h5 class="mb-3">Contact Information</h5>

                <div class="mb-3">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}">
                    @error('contact_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact_email_secondary" class="form-label">Secondary Email</label>
                    <input type="email" class="form-control @error('contact_email_secondary') is-invalid @enderror" id="contact_email_secondary" name="contact_email_secondary" value="{{ old('contact_email_secondary', $settings->contact_email_secondary) }}">
                    @error('contact_email_secondary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact_phone" class="form-label">Contact Phone</label>
                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}">
                    @error('contact_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact_address" class="form-label">Office Address</label>
                    <textarea class="form-control @error('contact_address') is-invalid @enderror" id="contact_address" name="contact_address" rows="2">{{ old('contact_address', $settings->contact_address) }}</textarea>
                    @error('contact_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="opening_hours" class="form-label">Opening Hours</label>
                    <input type="text" class="form-control @error('opening_hours') is-invalid @enderror" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $settings->opening_hours) }}">
                    @error('opening_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Social Media Links</h5>

                <div class="mb-3">
                    <label for="facebook_url" class="form-label">
                        <i class="bi bi-facebook"></i> Facebook URL
                    </label>
                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" placeholder="https://facebook.com/yourpage">
                    @error('facebook_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="twitter_url" class="form-label">
                        <i class="bi bi-twitter"></i> Twitter URL
                    </label>
                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}" placeholder="https://twitter.com/yourhandle">
                    @error('twitter_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="youtube_url" class="form-label">
                        <i class="bi bi-youtube"></i> YouTube URL
                    </label>
                    <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $settings->youtube_url) }}" placeholder="https://youtube.com/yourchannel">
                    @error('youtube_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="linkedin_url" class="form-label">
                        <i class="bi bi-linkedin"></i> LinkedIn URL
                    </label>
                    <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}" placeholder="https://linkedin.com/company/yourcompany">
                    @error('linkedin_url')
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
