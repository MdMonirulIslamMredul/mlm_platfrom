@extends('layouts.admin')

@section('admin-title', 'Edit User Record')

@section('admin-content')
    <div class="user-record-form-container">
        <div class="form-header">
            <h3>Edit User Record - {{ $userRecord->name ?? $userRecord->id }}</h3>
            <a href="{{ route('admin.user-records.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.user-records.update', $userRecord) }}" method="POST" enctype="multipart/form-data"
                id="userRecordForm">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h5>Personal Information</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $userRecord->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $userRecord->email) }}">
                                @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
                            </div>
                        </div>
                        -->
                    </div>

                    <!--
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="father_name" class="form-label">Father's Name</label>
                                <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{ old('father_name', $userRecord->father_name) }}">
                                @error('father_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="mother_name" class="form-label">Mother's Name</label>
                                <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name" value="{{ old('mother_name', $userRecord->mother_name) }}">
                                @error('mother_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
                            </div>
                        </div>
                    </div>
                    -->
                </div>

                <div class="form-section">
                    <h5>Immigration Details</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="passport" class="form-label">ID Number</label>
                                <input type="text" class="form-control @error('passport') is-invalid @enderror"
                                    id="passport" name="passport" value="{{ old('passport', $userRecord->passport) }}">
                                @error('passport')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="ircc" class="form-label">IRCC / Application Number</label>
                                <input type="text" class="form-control @error('ircc') is-invalid @enderror" id="ircc" name="ircc" value="{{ old('ircc', $userRecord->ircc) }}">
                                @error('ircc')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
                            </div>
                        </div>
                        -->
                    </div>

                    <!--
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nid_number" class="form-label">NID Number</label>
                                <input type="text" class="form-control @error('nid_number') is-invalid @enderror" id="nid_number" name="nid_number" value="{{ old('nid_number', $userRecord->nid_number) }}">
                                @error('nid_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
                            </div>
                        </div>
                    </div>
                    -->
                </div>

                <div class="form-section">
                    <h5>Profile Image</h5>

                    <div class="form-group mb-3">
                        <label for="user_image" class="form-label">User Image</label>
                        <div class="image-upload-wrapper">
                            @if ($userRecord->user_image)
                                <div class="image-preview" id="imagePreview">
                                    <img src="{{ asset($userRecord->user_image) }}" alt="Current Image"
                                        class="img-thumbnail">
                                </div>
                                <small class="form-text text-muted d-block mb-2">Current image</small>
                            @endif
                            <input type="file" class="form-control @error('user_image') is-invalid @enderror"
                                id="user_image" name="user_image" accept="image/*" onchange="previewImage(event)">
                        </div>
                        <small class="form-text text-muted">Leave empty to keep current image. Maximum file size:
                            2MB</small>
                        @error('user_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section">
                    <h5>Documents</h5>

                    @if ($userRecord->documents->count() > 0)
                        <div class="current-documents mb-4">
                            <h6>Current Documents</h6>
                            <div class="documents-grid">
                                @foreach ($userRecord->documents as $doc)
                                    <div class="document-card">
                                        @php
                                            $extension = strtolower(pathinfo($doc->document_path, PATHINFO_EXTENSION));
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                        @endphp

                                        @if ($isImage)
                                            <div class="document-image-preview">
                                                <img src="{{ asset($doc->document_path) }}"
                                                    alt="{{ $doc->document_name }}" class="document-thumbnail">
                                                <div class="image-overlay">
                                                    <i class="bi bi-image-fill"></i>
                                                </div>
                                            </div>
                                        @elseif($extension === 'pdf')
                                            <div class="document-pdf-preview"
                                                data-pdf-url="{{ asset($doc->document_path) }}"
                                                data-doc-id="{{ $doc->id }}">
                                                <canvas id="pdf-canvas-{{ $doc->id }}"
                                                    class="pdf-thumbnail-canvas"></canvas>
                                                <div class="pdf-overlay">
                                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                                    <span>PDF</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="document-icon-preview">
                                                @if (in_array($extension, ['doc', 'docx']))
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
                                            <small class="text-muted">{{ strtoupper($extension) }}</small>
                                        </div>
                                        <div class="document-actions">
                                            <a href="{{ asset($doc->document_path) }}" class="btn btn-sm btn-info"
                                                target="_blank" title="Preview">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                onclick="deleteExistingDocument('{{ route('admin.user-records.delete-document', $doc) }}', '{{ $doc->document_name }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="documents-upload-wrapper">
                        <h6>Add New Documents</h6>
                        <input type="file" id="documentFileInput" accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx"
                            style="display: none;" onchange="addDocument(event)">

                        <button type="button" class="btn btn-outline-primary mb-3"
                            onclick="document.getElementById('documentFileInput').click()">
                            <i class="bi bi-plus-circle"></i> Add More Documents
                        </button>

                        <div id="documentsList" class="documents-list"></div>
                    </div>

                    @error('documents.*')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hidden inputs for actual file upload (must be inside form) -->
                <div id="documentsInputsContainer"></div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-danger btn-lg" id="updateRecordBtn">
                        <i class="bi bi-check-circle"></i> Update Record
                    </button>
                    <a href="{{ route('admin.user-records.index') }}" class="btn btn-outline-secondary btn-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .documents-list {
            margin-top: 10px;
        }

        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
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

        .document-row {
            display: flex;
            align-items: center;
            background: #fafbfc;
            border: 1px solid #e3e6ea;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 8px;
            gap: 12px;
        }

        .document-icon {
            font-size: 2em;
            color: #adb5bd;
            flex-shrink: 0;
        }

        .document-icon-preview {
            font-size: 3.5rem;
            color: #dc3545;
            margin-bottom: 10px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .document-name {
            flex: 1;
            font-weight: 500;
            color: #333;
            word-break: break-word;
            font-size: 0.95em;
        }

        .document-size {
            color: #888;
            font-size: 0.9em;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .document-action {
            cursor: pointer;
            color: #888;
            font-size: 1.2em;
            padding: 4px;
            transition: color 0.2s;
            flex-shrink: 0;
        }

        .document-action:hover {
            color: #dc3545;
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
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-thumbnail">`;
                };
                reader.readAsDataURL(file);
            }
        }

        // Document management with "Add More" button approach
        let documentCounter = 0;
        let documentsData = [];

        function addDocument(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Check file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                event.target.value = '';
                return;
            }

            const docId = documentCounter++;
            documentsData.push({
                id: docId,
                file: file
            });

            // Add to visual list
            addDocumentToList(file, docId);

            // Create hidden file input for form submission
            const inputsContainer = document.getElementById('documentsInputsContainer');
            if (!inputsContainer) {
                console.error('documentsInputsContainer not found');
                return;
            }

            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'documents[]';
            fileInput.id = `doc-input-${docId}`;
            fileInput.style.display = 'none';

            // Transfer the file to the hidden input using DataTransfer
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;

            inputsContainer.appendChild(fileInput);

            // Reset the visible file input
            event.target.value = '';

            console.log('Document added:', file.name, 'Total documents:', documentsData.length);
        }

        function addDocumentToList(file, docId) {
            const list = document.getElementById('documentsList');
            const row = document.createElement('div');
            row.className = 'document-row';
            row.id = `doc-row-${docId}`;

            // Get file icon based on type
            let icon = 'bi-file-earmark';
            if (file.type.includes('pdf')) {
                icon = 'bi-file-earmark-pdf';
            } else if (file.type.includes('image')) {
                icon = 'bi-file-earmark-image';
            } else if (file.type.includes('word') || file.name.includes('.doc')) {
                icon = 'bi-file-earmark-word';
            } else if (file.type.includes('text') || file.name.endsWith('.txt')) {
                icon = 'bi-file-earmark-text';
            }

            // Create preview button (opens in new tab)
            const previewBtn = file.type.startsWith('image') || file.type.startsWith('text') || file.type ===
                'application/pdf' ?
                `<span class="document-action" title="Preview" onclick="previewDocument(${docId})"><i class="bi bi-eye"></i></span>` :
                `<span class="document-action" title="Preview" style="opacity:0.4;cursor:not-allowed;"><i class="bi bi-eye"></i></span>`;

            row.innerHTML = `
        <i class="bi ${icon} document-icon"></i>
        <span class="document-name">${file.name}</span>
        <span class="document-size">${(file.size / 1024).toFixed(2)} KB</span>
        ${previewBtn}
        <span class="document-action" title="Remove" onclick="removeDocument(${docId})"><i class="bi bi-trash"></i></span>
    `;
            list.appendChild(row);
        }

        function removeDocument(docId) {
            // Remove from visual list
            const row = document.getElementById(`doc-row-${docId}`);
            if (row) row.remove();

            // Remove hidden input
            const input = document.getElementById(`doc-input-${docId}`);
            if (input) input.remove();

            // Remove from data array
            documentsData = documentsData.filter(doc => doc.id !== docId);
        }

        function previewDocument(docId) {
            const docData = documentsData.find(doc => doc.id === docId);
            if (!docData) return;

            const file = docData.file;
            const url = URL.createObjectURL(file);

            // Open in new tab
            window.open(url, '_blank');

            // Clean up after a delay
            setTimeout(() => {
                URL.revokeObjectURL(url);
            }, 1000);
        }

        // Delete existing document via AJAX
        function deleteExistingDocument(url, docName) {
            if (!confirm('Delete this document: ' + docName + '?')) {
                return;
            }

            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document
                            .querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to reflect changes
                        window.location.reload();
                    } else {
                        alert('Error deleting document');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting document');
                });
        }

        // Configure PDF.js worker
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc =
                'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        }

        // Function to render PDF thumbnail
        function renderPDFThumbnail(pdfUrl, canvasId) {
            if (typeof pdfjsLib === 'undefined') return;

            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            const loadingTask = pdfjsLib.getDocument(pdfUrl);

            loadingTask.promise.then(function(pdf) {
                pdf.getPage(1).then(function(page) {
                    const scale = 0.5;
                    const viewport = page.getViewport({
                        scale: scale
                    });

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
                canvas.style.display = 'none';
            });
        }

        // Debug form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userRecordForm');
            const submitBtn = document.getElementById('updateRecordBtn');

            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitting...');
                    console.log('Documents to upload:', documentsData.length);
                    console.log('Hidden inputs:', document.getElementById('documentsInputsContainer')
                        .children.length);

                    // Allow form to submit normally
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';
                });
            }

            // Load all PDF thumbnails for existing documents
            const pdfPreviews = document.querySelectorAll('.document-pdf-preview');
            pdfPreviews.forEach(function(preview) {
                const pdfUrl = preview.getAttribute('data-pdf-url');
                const docId = preview.getAttribute('data-doc-id');
                const canvasId = 'pdf-canvas-' + docId;

                renderPDFThumbnail(pdfUrl, canvasId);
            });

            console.log('Edit page initialized');
        });
    </script>
@endsection
