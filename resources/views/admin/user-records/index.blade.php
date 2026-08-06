@extends('layouts.admin')

@section('admin-title', 'User Records')

@section('admin-content')
<div class="user-records-container">
    <div class="records-header">
        <h3>User Records Management</h3>
        <a href="{{ route('admin.user-records.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-circle"></i> Add New Record
        </a>
    </div>

    <div class="records-table-wrapper">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <!-- <th>Image</th> -->
                    <th>Name</th>
                    <!-- <th>Email</th> -->
                    <th>ID Number</th>
                    <th>Documents</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userRecords as $record)
                    <tr>
                        <td>{{ $record->id }}</td>
                        <!-- <td>
                            @if($record->user_image)
                                <img src="{{ asset($record->user_image) }}" alt="User Image" class="record-thumbnail">
                            @else
                                <div class="record-thumbnail-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td> -->
                        <td>{{ $record->name ?? 'N/A' }}</td>
                        <!-- <td>{{ $record->email ?? 'N/A' }}</td> -->
                        <td>{{ $record->passport ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $record->documents->count() }} files</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.user-records.show', $record) }}" class="btn btn-sm btn-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.user-records.edit', $record) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.user-records.destroy', $record) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>No user records found</p>
                                <a href="{{ route('admin.user-records.create') }}" class="btn btn-danger btn-sm">
                                    Create First Record
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($userRecords->hasPages())
        <div class="pagination-wrapper">
            {{ $userRecords->links() }}
        </div>
    @endif
</div>
@endsection
