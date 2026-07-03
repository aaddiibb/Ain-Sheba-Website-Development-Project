@extends('admin._layout')

@section('title', 'Messages — Admin — Ain Sheba')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Contact Messages</h4>
    <span class="badge bg-secondary">{{ $messages->total() }} total</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Received</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $msg)
                    <tr>
                        <td class="fw-semibold">{{ $msg->name }}</td>
                        <td class="text-muted small">{{ $msg->email }}</td>
                        <td>{{ Str::limit($msg->subject, 50) }}</td>
                        <td class="text-muted small">{{ $msg->created_at->format('d M Y, H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No messages yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $messages->links() }}
</div>

@endsection
