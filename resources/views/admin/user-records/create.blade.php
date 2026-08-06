@extends('layouts.admin')

@section('admin-title', 'Add User Record')

@section('admin-content')
    <div class="user-record-form-container">
        <div class="form-header">
            <h3>Add New User Record</h3>
            <a href="{{ route('admin.user-records.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.user-records.store') }}" method="POST" enctype="multipart/form-data"
                id="userRecordForm">
                @csrf

                <div class="form-section">
                    <h5>Personal Information</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
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
                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{ old('father_name') }}">
                                    @error('father_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name" value="{{ old('mother_name') }}">
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
                                    id="passport" name="passport" value="{{ old('passport') }}">
                                @error('passport')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="ircc" class="form-label">IRCC / Application Number</label>
                                    <input type="text" class="form-control @error('ircc') is-invalid @enderror" id="ircc" name="ircc" value="{{ old('ircc') }}">
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
                                    <input type="text" class="form-control @error('nid_number') is-invalid @enderror" id="nid_number" name="nid_number" value="{{ old('nid_number') }}">
                                    @error('nid_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
                                </div>
                            </div>
                        </div>
                        -->
                </div>

                {{-- <div class="form-section">
                    <h5>Profile Image</h5>

                    <div class="form-group mb-3">
                        <label for="user_image" class="form-label">User Image</label>
                        <div class="image-upload-wrapper">
                            <input type="file" class="form-control @error('user_image') is-invalid @enderror"
                                id="user_image" name="user_image" accept="image/*" onchange="previewImage(event)">
                            <div class="image-preview" id="imagePreview"></div>
                        </div>
                        <small class="form-text text-muted">Maximum file size: 2MB (JPEG, PNG, JPG, GIF)</small>
                        @error('user_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}

                <div class="form-section">
                    <h5>Documents</h5>
                    <p class="text-muted">Upload supporting documents (PDF, Images, etc.)</p>

                    <div class="documents-upload-wrapper">
                        <input type="file" id="documentFileInput" accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx"
                            style="display: none;" onchange="addDocument(event)">

                        <button type="button" class="btn btn-outline-primary mb-3"
                            onclick="document.getElementById('documentFileInput').click()">
                            <i class="bi bi-plus-circle"></i> Add More Documents
                        </button>

                        <div id="documentsList" class="documents-list"></div>

                        <!-- Hidden inputs for actual file upload -->
                        <div id="documentsInputsContainer"></div>
                    </div>

                    @error('documents.*')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="bi bi-check-circle"></i> Create Record
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
    </style>

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
            } else {
                preview.innerHTML = '';
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
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'documents[]';
            fileInput.id = `doc-input-${docId}`;
            fileInput.style.display = 'none';
            inputsContainer.appendChild(fileInput);

            // Transfer the file to the hidden input
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;

            // Reset the visible file input
            event.target.value = '';
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

            // Create download button (for preview)
            const downloadBtn = file.type.startsWith('image') || file.type.startsWith('text') || file.type ===
                'application/pdf' ?
                `<span class="document-action" title="Download/Preview" onclick="downloadDocument(${docId})"><i class="bi bi-download"></i></span>` :
                `<span class="document-action" title="Download/Preview" style="opacity:0.4;cursor:not-allowed;"><i class="bi bi-download"></i></span>`;

            row.innerHTML = `
        <i class="bi ${icon} document-icon"></i>
        <span class="document-name">${file.name}</span>
        <span class="document-size">${(file.size / 1024).toFixed(2)} KB</span>
        ${downloadBtn}
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

        function downloadDocument(docId) {
            const docData = documentsData.find(doc => doc.id === docId);
            if (!docData) return;

            const file = docData.file;
            const url = URL.createObjectURL(file);
            const a = document.createElement('a');
            a.href = url;
            a.download = file.name;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }, 100);
        }
    </script>
@endsection
