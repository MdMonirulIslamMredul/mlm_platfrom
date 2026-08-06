@extends('layouts.admin')

@section('admin-title', 'About Settings')

@section('admin-content')
<div class="settings-container">
    <div class="settings-header mb-4">
        <h3>About Page Settings</h3>
        <p class="text-muted">Manage your About Us page content</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.about.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="about_title" class="form-label">Page Title</label>
                    <input type="text" class="form-control @error('about_title') is-invalid @enderror" id="about_title" name="about_title" value="{{ old('about_title', $settings->about_title) }}" required>
                    @error('about_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="about_subtitle" class="form-label">Subtitle</label>
                    <input type="text" class="form-control @error('about_subtitle') is-invalid @enderror" id="about_subtitle" name="about_subtitle" value="{{ old('about_subtitle', $settings->about_subtitle) }}">
                    @error('about_subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="about_description" class="form-label">Description</label>
                    <textarea class="form-control @error('about_description') is-invalid @enderror" id="about_description" name="about_description" rows="6">{{ old('about_description', $settings->about_description) }}</textarea>
                    @error('about_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="mission_statement" class="form-label">Mission Statement</label>
                    <textarea class="form-control @error('mission_statement') is-invalid @enderror" id="mission_statement" name="mission_statement" rows="4">{{ old('mission_statement', $settings->mission_statement) }}</textarea>
                    @error('mission_statement')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="vision_statement" class="form-label">Vision Statement</label>
                    <textarea class="form-control @error('vision_statement') is-invalid @enderror" id="vision_statement" name="vision_statement" rows="4">{{ old('vision_statement', $settings->vision_statement) }}</textarea>
                    @error('vision_statement')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                <h5 class="mb-3">About Page Images</h5>

                <!-- About Image 1 -->
                <div class="mb-4">
                    <label for="about_image_1" class="form-label">About Image 1</label>
                    @if($settings->about_image_1)
                    <div class="mb-3">
                        <img src="{{ asset($settings->about_image_1) }}" alt="About Image 1" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                        <small class="text-muted d-block mt-2">Current image 1</small>
                    </div>
                    @endif
                    <input type="file" class="form-control @error('about_image_1') is-invalid @enderror" id="about_image_1" name="about_image_1" accept="image/*">
                    @error('about_image_1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Max file size: 2MB. Formats: JPG, PNG, GIF, SVG</small>
                </div>

                <!-- About Image 2 -->
                <div class="mb-4">
                    <label for="about_image_2" class="form-label">About Image 2</label>
                    @if($settings->about_image_2)
                    <div class="mb-3">
                        <img src="{{ asset($settings->about_image_2) }}" alt="About Image 2" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                        <small class="text-muted d-block mt-2">Current image 2</small>
                    </div>
                    @endif
                    <input type="file" class="form-control @error('about_image_2') is-invalid @enderror" id="about_image_2" name="about_image_2" accept="image/*">
                    @error('about_image_2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Max file size: 2MB. Formats: JPG, PNG, GIF, SVG</small>
                </div>

                <!-- About Image 3 -->
                <div class="mb-4">
                    <label for="about_image_3" class="form-label">About Image 3</label>
                    @if($settings->about_image_3)
                    <div class="mb-3">
                        <img src="{{ asset($settings->about_image_3) }}" alt="About Image 3" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                        <small class="text-muted d-block mt-2">Current image 3</small>
                    </div>
                    @endif
                    <input type="file" class="form-control @error('about_image_3') is-invalid @enderror" id="about_image_3" name="about_image_3" accept="image/*">
                    @error('about_image_3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Max file size: 2MB. Formats: JPG, PNG, GIF, SVG</small>
                </div>

                <!-- About Image 4 -->
                <div class="mb-4">
                    <label for="about_image_4" class="form-label">About Image 4</label>
                    @if($settings->about_image_4)
                    <div class="mb-3">
                        <img src="{{ asset($settings->about_image_4) }}" alt="About Image 4" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                        <small class="text-muted d-block mt-2">Current image 4</small>
                    </div>
                    @endif
                    <input type="file" class="form-control @error('about_image_4') is-invalid @enderror" id="about_image_4" name="about_image_4" accept="image/*">
                    @error('about_image_4')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Max file size: 2MB. Formats: JPG, PNG, GIF, SVG</small>
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-save"></i> Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
