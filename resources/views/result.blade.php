@extends('layouts.app')

@section('title', 'Visa Status Result - Canada Visa Processing')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 50px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Result Card -->
                <div class="result-card bg-white rounded shadow-lg p-5 mb-4">
                    <div class="text-center mb-4">
                        <h2 class="display-5 fw-bold text-primary mb-2">{{ $userRecord->name }}</h2>
                        <div class="golden-dots" style="color: goldenrod; font-size: 30px;">........</div>
                    </div>

                    <!-- Personal Information Table -->
                    <div class="info-table-wrapper mb-5">
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <td class="fw-bold bg-light" style="width: 30%;">Name</td>
                                    <td>{{ $userRecord->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold bg-light">Passport</td>
                                    <td>{{ $userRecord->passport ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold bg-light">Email</td>
                                    <td>{{ $userRecord->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold bg-light">IRCC Number</td>
                                    <td>{{ $userRecord->ircc ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold bg-light">NID Number</td>
                                    <td>{{ $userRecord->nid_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold bg-light">Father's Name</td>
                                    <td>{{ $userRecord->father_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold bg-light">Mother's Name</td>
                                    <td>{{ $userRecord->mother_name ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Your Result Section -->
                    <div class="result-section">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-danger">Your Result</h3>
                            <div class="golden-dots" style="color: goldenrod; font-size: 30px;">........</div>
                        </div>

                        @if($userRecord->user_image)
                            <div class="text-center mb-4">
                                <img src="{{ asset($userRecord->user_image) }}" alt="{{ $userRecord->name }}" class="img-fluid rounded shadow" style="max-width: 300px;">
                            </div>
                        @endif

                        <!-- Documents Gallery -->
                        @if($userRecord->documents->count() > 0)
                            <div class="documents-gallery mt-4">
                                <div class="row g-4">
                                    @foreach($userRecord->documents as $doc)
                                        @php
                                            $extension = strtolower(pathinfo($doc->document_path, PATHINFO_EXTENSION));
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                        @endphp

                                        <div class="col-md-6">
                                            <div class="document-item-large">
                                                @if($isImage)
                                                    <a href="{{ asset($doc->document_path) }}" target="_blank" class="document-link">
                                                        <div class="document-preview">
                                                            <img src="{{ asset($doc->document_path) }}" alt="{{ $doc->document_name }}" class="img-fluid rounded shadow">
                                                            <div class="document-overlay">
                                                                <i class="bi bi-eye-fill"></i>
                                                                <span>View Image</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @elseif($extension === 'pdf')
                                                    <div class="pdf-preview-container" data-pdf-url="{{ asset($doc->document_path) }}" data-doc-id="{{ $doc->id }}">
                                                        <canvas id="pdf-canvas-{{ $doc->id }}" class="pdf-canvas rounded shadow"></canvas>
                                                        <div class="pdf-badge">
                                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                                            <span>PDF</span>
                                                        </div>
                                                        <a href="{{ asset($doc->document_path) }}" target="_blank" class="pdf-view-btn">
                                                            <i class="bi bi-eye-fill"></i> View PDF
                                                        </a>
                                                    </div>
                                                @else
                                                    <a href="{{ asset($doc->document_path) }}" target="_blank" class="document-link">
                                                        <div class="document-icon-large">
                                                            @if(in_array($extension, ['doc', 'docx']))
                                                                <i class="bi bi-file-earmark-word-fill text-primary"></i>
                                                            @elseif($extension === 'txt')
                                                                <i class="bi bi-file-earmark-text-fill text-secondary"></i>
                                                            @else
                                                                <i class="bi bi-file-earmark-fill text-dark"></i>
                                                            @endif
                                                            <p class="mt-3">{{ $doc->document_name }}</p>
                                                        </div>
                                                    </a>
                                                @endif
                                                <div class="document-name-label mt-2">
                                                    <small class="text-muted">{{ $doc->document_name }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <p class="text-muted">
                                Your visa application documents have been received and are being processed.
                                Please check back regularly for updates on your application status.
                            </p>
                        </div>
                    </div>

                    <!-- Back to Home Button -->
                    <div class="text-center mt-5">
                        <a href="{{ url('/') }}" class="btn btn-danger btn-lg px-5 py-3">
                            <i class="bi bi-house-fill"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .result-card {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .info-table-wrapper table {
        font-size: 1.05rem;
    }

    .info-table-wrapper td {
        padding: 15px;
        vertical-align: middle;
    }

    .golden-dots {
        letter-spacing: 5px;
    }

    /* Document Gallery Styles */
    .document-item-large {
        position: relative;
        height: 100%;
    }

    .document-preview {
        position: relative;
        width: 100%;
        height: 600px;
        overflow: hidden;
        border-radius: 10px;
        cursor: pointer;
    }

    .document-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .document-preview:hover img {
        transform: scale(1.05);
    }

    .document-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        font-size: 1.2rem;
    }

    .document-preview:hover .document-overlay {
        opacity: 1;
    }

    .document-overlay i {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    /* PDF Preview Styles */
    .pdf-preview-container {
        position: relative;
        width: 100%;
        height: 600px;
        border-radius: 10px;
        overflow: hidden;
        background: #f5f5f5;
    }

    .pdf-canvas {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: white;
    }

    .pdf-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(220, 53, 69, 0.95);
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .pdf-badge i {
        font-size: 1.2rem;
    }

    .pdf-view-btn {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(220, 53, 69, 0.95);
        color: white;
        padding: 10px 25px;
        border-radius: 25px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .pdf-view-btn:hover {
        background: rgba(200, 35, 51, 1);
        color: white;
        transform: translateX(-50%) translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    /* Document Icon Large */
    .document-icon-large {
        width: 100%;
        height: 600px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }

    .document-icon-large:hover {
        border-color: #dc3545;
        background: #fff;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .document-icon-large i {
        font-size: 5rem;
    }

    .document-icon-large p {
        font-size: 1rem;
        font-weight: 500;
        color: #495057;
        text-align: center;
        padding: 0 20px;
        word-break: break-word;
    }

    .document-name-label {
        text-align: center;
        padding: 10px;
    }

    .document-name-label small {
        font-size: 0.9rem;
        display: block;
        word-break: break-word;
    }

    .document-link {
        text-decoration: none;
        color: inherit;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }

    function renderPDFThumbnail(pdfUrl, canvasId) {
        if (typeof pdfjsLib === 'undefined') return;

        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const loadingTask = pdfjsLib.getDocument(pdfUrl);

        loadingTask.promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
                const scale = 1.5;
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
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const pdfPreviews = document.querySelectorAll('.pdf-preview-container');
        pdfPreviews.forEach(function(preview) {
            const pdfUrl = preview.getAttribute('data-pdf-url');
            const docId = preview.getAttribute('data-doc-id');
            const canvasId = 'pdf-canvas-' + docId;

            renderPDFThumbnail(pdfUrl, canvasId);
        });
    });
</script>
@endsection
