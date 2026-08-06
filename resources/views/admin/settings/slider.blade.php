@extends('layouts.admin')

@section('admin-title', 'Slider Setting')

@section('admin-content')
<div class="content-card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-sliders"></i> Slider Settings
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.settings.slider.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Universal Slider -->
            <div class="mb-4">
                <label for="universal_slider" class="form-label">Universal Slider Image</label>

                @if($settings->universal_slider)
                <div class="mb-3">
                    <div class="image-preview-container">
                        <img src="{{ asset($settings->universal_slider) }}" alt="Universal Slider" class="img-thumbnail" style="max-width: 100%; max-height: 400px;">
                    </div>
                    <small class="text-muted d-block mt-2">Current slider image</small>
                </div>
                @endif

                <input type="file" class="form-control @error('universal_slider') is-invalid @enderror"
                       id="universal_slider" name="universal_slider" accept="image/*">

                @error('universal_slider')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="form-text text-muted">
                    Recommended size: 1920x600 pixels. Max file size: 5MB. Formats: JPG, PNG, GIF, SVG
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
        width: 100%;
        text-align: center;
    }

    .image-preview-container img {
        display: block;
        background: white;
        margin: 0 auto;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('universal_slider').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const existingPreview = document.querySelector('.image-preview-container');
                if (!existingPreview) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview-container mb-3';
                    previewDiv.innerHTML = `
                        <img src="${event.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 400px;">
                        <small class="text-muted d-block mt-2">New upload preview</small>
                    `;
                    document.getElementById('universal_slider').parentElement.insertBefore(previewDiv, document.getElementById('universal_slider'));
                } else {
                    existingPreview.querySelector('img').src = event.target.result;
                    existingPreview.querySelector('small').textContent = 'New upload preview';
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
