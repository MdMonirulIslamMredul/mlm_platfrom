@extends('layouts.admin')

@section('admin-title', 'View User Record')

@section('admin-content')
<div class="user-record-view-container">
    <div class="view-header">
        <h3>User Record Details</h3>
        <div class="header-actions">
            <a href="{{ route('admin.user-records.edit', $userRecord) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.user-records.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="view-content">
        <!-- User Image -->
        @if($userRecord->user_image)
            <div class="user-image-section">
                <img src="{{ asset($userRecord->user_image) }}" alt="User Image" class="user-image">
            </div>
        @endif

        <!-- Personal Information Card -->
        <div class="info-card">
            <h5 class="card-title">Personal Information</h5>
            <div class="info-rows">
                <div class="info-row">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value">{{ $userRecord->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $userRecord->email ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Father's Name:</span>
                    <span class="info-value">{{ $userRecord->father_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mother's Name:</span>
                    <span class="info-value">{{ $userRecord->mother_name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Immigration Information Card -->
        <div class="info-card">
            <h5 class="card-title">Immigration Details</h5>
            <div class="info-rows">
                <div class="info-row">
                    <span class="info-label">ID Number:</span>
                    <span class="info-value">{{ $userRecord->passport ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">IRCC / Application Number:</span>
                    <span class="info-value">{{ $userRecord->ircc ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NID Number:</span>
                    <span class="info-value">{{ $userRecord->nid_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        @if($userRecord->documents->count() > 0)
            <div class="info-card">
                <h5 class="card-title">Attached Documents ({{ $userRecord->documents->count() }})</h5>
                <div class="documents-grid">
                    @foreach($userRecord->documents as $doc)
                        <div class="document-card">
                            @php
                                $extension = strtolower($doc->file_type);
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp

                            @if($isImage)
                                <div class="document-image-preview">
                                    <img src="{{ asset($doc->document_path) }}" alt="{{ $doc->document_name }}" class="document-thumbnail">
                                    <div class="image-overlay">
                                        <i class="bi bi-image-fill"></i>
                                    </div>
                                </div>
                            @elseif($extension === 'pdf')
                                <div class="document-pdf-preview" data-pdf-url="{{ asset($doc->document_path) }}" data-doc-id="{{ $doc->id }}">
                                    <canvas id="pdf-canvas-{{ $doc->id }}" class="pdf-thumbnail-canvas"></canvas>
                                    <div class="pdf-overlay">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                        <span>PDF</span>
                                    </div>
                                </div>
                            @else
                                <div class="document-icon">
                                    @if(in_array($extension, ['doc', 'docx']))
                                        <i class="bi bi-file-earmark-word-fill"></i>
                                    @elseif($extension === 'txt')
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    @else
                                        <i class="bi bi-file-earmark-fill"></i>
                                    @endif
                                </div>
                            @endif

                            <div class="document-info">
                                <h6>{{ $doc->document_name }}</h6>
                                <small class="text-muted">{{ strtoupper($doc->file_type) }}</small>
                            </div>
                            <div class="document-actions">
                                <a href="{{ asset($doc->document_path) }}" class="btn btn-sm btn-info" download title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="{{ asset($doc->document_path) }}" class="btn btn-sm btn-primary" target="_blank" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="info-card">
                <p class="text-muted">No documents attached</p>
            </div>
        @endif

        <!-- Metadata -->
        <div class="info-card metadata">
            <div class="info-row">
                <span class="info-label">Record ID:</span>
                <span class="info-value">{{ $userRecord->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Created:</span>
                <span class="info-value">{{ $userRecord->created_at->format('M d, Y H:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Last Updated:</span>
                <span class="info-value">{{ $userRecord->updated_at->format('M d, Y H:i A') }}</span>
            </div>
        </div>
    </div>
</div>

<style>
.user-record-view-container {
    max-width: 900px;
}

.view-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.view-header h3 {
    color: #333;
    font-weight: 700;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.user-image-section {
    text-align: center;
    margin-bottom: 30px;
}

.user-image {
    max-width: 300px;
    max-height: 300px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.info-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
}

.info-card.metadata {
    background: #f8f9fa;
}

.card-title {
    color: #333;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e0e0e0;
}

.info-rows {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-row {
    display: flex;
    gap: 15px;
}

.info-label {
    font-weight: 600;
    color: #666;
    min-width: 180px;
}

.info-value {
    color: #333;
    word-break: break-word;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.document-card {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.document-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.document-icon {
    font-size: 2.5rem;
    color: #dc3545;
    margin-bottom: 10px;
}

.document-image-preview {
    position: relative;
    width: 100%;
    height: 150px;
    margin-bottom: 10px;
    border-radius: 6px;
    overflow: hidden;
}

.document-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
}

.image-overlay {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    padding: 5px 8px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.document-pdf-preview {
    position: relative;
    width: 100%;
    height: 150px;
    margin-bottom: 10px;
    border-radius: 6px;
    overflow: hidden;
    background: #f0f0f0;
}

.pdf-thumbnail-canvas {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: white;
}

.pdf-overlay {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.pdf-overlay i {
    font-size: 1rem;
}

.document-info h6 {
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 5px;
    word-break: break-word;
    color: #333;
}

.document-actions {
    display: flex;
    gap: 5px;
    justify-content: center;
    margin-top: 10px;
}

.document-actions .btn {
    flex: 1;
}

@media (max-width: 768px) {
    .view-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions .btn {
        flex: 1;
    }

    .info-row {
        flex-direction: column;
    }

    .info-label {
        min-width: auto;
    }

    .documents-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
// Configure PDF.js worker
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

// Function to render PDF thumbnail
function renderPDFThumbnail(pdfUrl, canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const loadingTask = pdfjsLib.getDocument(pdfUrl);

    loadingTask.promise.then(function(pdf) {
        // Get first page
        pdf.getPage(1).then(function(page) {
            const scale = 0.5;
            const viewport = page.getViewport({ scale: scale });

            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            page.render(renderContext);
        });
    }).catch(function(error) {
        console.error('Error loading PDF:', error);
        // If PDF fails to load, show fallback icon
        canvas.style.display = 'none';
    });
}

// Load all PDF thumbnails when page loads
document.addEventListener('DOMContentLoaded', function() {
    const pdfPreviews = document.querySelectorAll('.document-pdf-preview');

    pdfPreviews.forEach(function(preview) {
        const pdfUrl = preview.getAttribute('data-pdf-url');
        const docId = preview.getAttribute('data-doc-id');
        const canvasId = 'pdf-canvas-' + docId;

        renderPDFThumbnail(pdfUrl, canvasId);
    });
});
</script>
@endsection
