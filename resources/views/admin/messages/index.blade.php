@extends('admin._layout')

@section('title', 'Messages — Admin — Ain Sheba')

@section('admin-content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-1 fw-bold"><i class="bi bi-envelope me-2 text-primary"></i>Contact Messages</h4>
        <p class="text-muted mb-0 small">Messages submitted through the public contact form.</p>
    </div>
    <span class="badge ain-badge-confirmed">{{ $messages->total() }} total</span>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
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
