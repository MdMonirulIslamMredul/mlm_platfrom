@extends('layouts.admin')

@section('admin-title', 'Logo Setting')

@section('admin-content')
<div class="content-card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-image"></i> Logo Settings
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.settings.logo.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Header Logo -->
            <div class="mb-4">
                <label for="header_logo" class="form-label">Header Logo</label>

                @if($settings->header_logo)
                <div class="mb-3">
                    <div class="image-preview-container">
                        <img src="{{ asset($settings->header_logo) }}" alt="Header Logo" class="img-thumbnail" style="max-width: 300px; max-height: 150px;">
                    </div>
                    <small class="text-muted d-block mt-2">Current header logo</small>
                </div>
                @endif

                <input type="file" class="form-control @error('header_logo') is-invalid @enderror"
                       id="header_logo" name="header_logo" accept="image/*">

                @error('header_logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="form-text text-muted">
                    Recommended size: 200x60 pixels. Max file size: 2MB. Formats: JPG, PNG, GIF, SVG
                </small>
            </div>

            <!-- Footer Logo -->
            <div class="mb-4">
                <label for="footer_logo" class="form-label">Footer Logo</label>

                @if($settings->footer_logo)
                <div class="mb-3">
                    <div class="image-preview-container">
                        <img src="{{ asset($settings->footer_logo) }}" alt="Footer Logo" class="img-thumbnail" style="max-width: 300px; max-height: 150px;">
                    </div>
                    <small class="text-muted d-block mt-2">Current footer logo</small>
                </div>
                @endif

                <input type="file" class="form-control @error('footer_logo') is-invalid @enderror"
                       id="footer_logo" name="footer_logo" accept="image/*">

                @error('footer_logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="form-text text-muted">
                    Recommended size: 200x60 pixels. Max file size: 2MB. Formats: JPG, PNG, GIF, SVG
                </small>
            </div>

            <!-- Favicon -->
            <div class="mb-4">
                <label for="fav_icon" class="form-label">Favicon</label>

                @if($settings->fav_icon)
                <div class="mb-3">
                    <div class="image-preview-container">
                        <img src="{{ asset($settings->fav_icon) }}" alt="Favicon" class="img-thumbnail" style="max-width: 64px; max-height: 64px;">
                    </div>
                    <small class="text-muted d-block mt-2">Current favicon</small>
                </div>
                @endif

                <input type="file" class="form-control @error('fav_icon') is-invalid @enderror"
                       id="fav_icon" name="fav_icon" accept="image/*,.ico">

                @error('fav_icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="form-text text-muted">
                    Recommended size: 32x32 or 64x64 pixels. Max file size: 1MB. Formats: ICO, PNG, JPG, SVG
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .image-preview-container {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        display: inline-block;
    }

    .image-preview-container img {
        display: block;
        background: white;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview image before upload
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const existingPreview = input.parentElement.querySelector('.image-preview-container');
                    if (!existingPreview) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'image-preview-container mb-3';
                        previewDiv.innerHTML = `
                            <img src="${event.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 150px;">
                            <small class="text-muted d-block mt-2">New upload preview</small>
                        `;
                        input.parentElement.insertBefore(previewDiv, input);
                    } else {
                        existingPreview.querySelector('img').src = event.target.result;
                        existingPreview.querySelector('small').textContent = 'New upload preview';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
