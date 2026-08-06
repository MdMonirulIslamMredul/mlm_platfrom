<div class="search-popup-card search-popup-primary">
    <div class="search-popup-header">
        <h3 id="searchResultTitle">Search result</h3>
        <button type="button" class="search-popup-close" data-dismiss="search-popup"
            aria-label="Close search result">×</button>
    </div>

    <div class="search-popup-body">
        @if (isset($userRecord) && $userRecord)
            <div class="alert alert-success mb-3">Found a matching record for <strong>{{ $searchNumber }}</strong>.</div>
            <div class="search-popup-details">
                <div class="search-popup-field">
                    <span>Name</span>
                    <strong>{{ $userRecord->name ?? 'N/A' }}</strong>
                </div>
                <div class="search-popup-field">
                    <span>ID Number</span>
                    <strong>{{ $userRecord->passport ?? 'N/A' }}</strong>
                </div>
            </div>

            <div class="search-popup-documents">
                <h4>Documents</h4>
                @if ($userRecord->documents->count() > 0)
                    <div class="search-document-grid">
                        @foreach ($userRecord->documents as $document)
                            @php
                                $extension = strtolower(pathinfo($document->document_path, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                $documentUrl = asset($document->document_path);
                            @endphp

                            <a href="{{ $documentUrl }}" target="_blank" rel="noopener noreferrer"
                                class="search-document-card" title="Open {{ $document->document_name }}">
                                <div class="search-document-thumb">
                                    @if ($isImage)
                                        <img src="{{ $documentUrl }}" alt="{{ $document->document_name }}">
                                    @elseif ($extension === 'pdf')
                                        <div class="search-document-icon">
                                            <span>PDF</span>
                                        </div>
                                    @else
                                        <div class="search-document-icon">
                                            <span>{{ strtoupper($extension) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="search-document-meta">
                                    <strong>{{ $document->document_name }}</strong>
                                    <span>{{ strtoupper($extension) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="mb-0">No documents attached to this record.</p>
                @endif
            </div>
        @else
            <div class="alert alert-warning mb-3">No matching record was found for
                <strong>{{ $searchNumber }}</strong>.
            </div>
            <p class="mb-0">Please check the passport number and try again.</p>
        @endif
    </div>
</div>
