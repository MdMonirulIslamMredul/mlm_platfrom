@extends('layouts.admin')

@section('admin-title', 'Messages')

@section('admin-content')
<div class="content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-envelope"></i> Contact Messages
        </h5>
        <span class="badge bg-danger">{{ $messages->total() }} Total</span>
    </div>
    <div class="card-body">
        @if($messages->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">Status</th>
                            <th width="15%">Name</th>
                            <th width="15%">Email</th>
                            <th width="10%">Phone</th>
                            <th width="20%">Subject</th>
                            <th width="15%">Date</th>
                            <th width="20%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                        <tr class="{{ $message->is_read ? '' : 'table-light fw-bold' }}">
                            <td>
                                @if($message->is_read)
                                    <i class="bi bi-envelope-open text-muted" title="Read"></i>
                                @else
                                    <i class="bi bi-envelope-fill text-danger" title="Unread"></i>
                                @endif
                            </td>
                            <td>{{ $message->name }}</td>
                            <td>
                                <a href="mailto:{{ $message->email }}" class="text-decoration-none">
                                    {{ $message->email }}
                                </a>
                            </td>
                            <td>
                                <a href="tel:{{ $message->phone }}" class="text-decoration-none">
                                    {{ $message->phone }}
                                </a>
                            </td>
                            <td>{{ $message->subject }}</td>
                            <td>
                                <small>{{ $message->created_at->format('M d, Y') }}<br>{{ $message->created_at->format('h:i A') }}</small>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#messageModal{{ $message->id }}">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <form action="{{ route('admin.messages.toggle-read', $message) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $message->is_read ? 'btn-secondary' : 'btn-success' }}" title="{{ $message->is_read ? 'Mark as Unread' : 'Mark as Read' }}">
                                        <i class="bi bi-{{ $message->is_read ? 'envelope' : 'envelope-open' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Message Modal -->
                        <div class="modal fade" id="messageModal{{ $message->id }}" tabindex="-1" aria-labelledby="messageModalLabel{{ $message->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="messageModalLabel{{ $message->id }}">
                                            Message from {{ $message->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <strong>Name:</strong> {{ $message->name }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Email:</strong>
                                            <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Phone:</strong>
                                            <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Subject:</strong> {{ $message->subject }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Date:</strong> {{ $message->created_at->format('F d, Y h:i A') }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Message:</strong>
                                            <div class="p-3 bg-light rounded mt-2">
                                                {{ $message->message }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $messages->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">No messages yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
