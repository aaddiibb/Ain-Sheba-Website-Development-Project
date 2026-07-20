@extends('admin._layout')

@section('title', $user->name . ' — Admin — Ain Sheba')

@section('admin-content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">{{ $user->name }}</li>
    </ol>
</nav>

{{-- User Info Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex gap-4 align-items-start flex-wrap">
            <div class="flex-shrink-0">
                @if ($user->profile_picture)
                    <img src="/{{ $user->profile_picture }}" class="rounded-circle"
                         style="width:90px;height:90px;object-fit:cover;" alt="{{ $user->name }}">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                         style="width:90px;height:90px;font-size:2rem;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <span class="badge {{ $user->isLawyer() ? 'bg-warning text-dark' : 'bg-primary' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if ($user->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>
                <div class="text-muted small mb-1"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</div>
                @if ($user->phone)
                    <div class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>{{ $user->phone }}</div>
                @endif
                @if ($user->bio)
                    <div class="text-muted small mb-2"><i class="bi bi-info-circle me-1"></i>{{ $user->bio }}</div>
                @endif
                <div class="text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>Joined {{ $user->created_at->format('d F Y') }}
                </div>
            </div>
            <div>
                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                        <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }} me-1"></i>
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline ms-1"
                      onsubmit="return confirm('Delete this user? All their data will be lost.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Lawyer: Programs Table --}}
@if ($user->isLawyer() && isset($user->programs))
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-journal-text me-2"></i>Programs ({{ $user->programs->count() }})
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 small align-middle">
            <thead class="table-light">
                <tr><th>Title</th><th>Status</th><th>Registrations</th><th>Created</th></tr>
            </thead>
            <tbody>
                @forelse ($user->programs as $prog)
                <tr>
                    <td class="fw-semibold">
                        <a href="{{ route('admin.programs.show', $prog->id) }}" class="text-decoration-none">
                            {{ $prog->title }}
                        </a>
                    </td>
                    <td>
                        @if ($prog->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @elseif ($prog->status === 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @else
                            <span class="badge bg-warning text-dark">Archived</span>
                        @endif
                    </td>
                    <td>{{ $prog->registrations_count }}</td>
                    <td class="text-muted">{{ $prog->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No programs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Citizen: Registrations Table --}}
@if ($user->isCitizen() && isset($user->registrations))
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-journal-bookmark me-2"></i>Program Registrations ({{ $user->registrations->count() }})
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 small align-middle">
            <thead class="table-light">
                <tr><th>Program</th><th>Registered</th><th>Completed</th></tr>
            </thead>
            <tbody>
                @forelse ($user->registrations as $reg)
                <tr>
                    <td class="fw-semibold">{{ $reg->program->title ?? '—' }}</td>
                    <td class="text-muted">{{ $reg->created_at->format('d M Y') }}</td>
                    <td>
                        @if ($reg->completed_at)
                            <span class="badge bg-success">{{ \Carbon\Carbon::parse($reg->completed_at)->format('d M Y') }}</span>
                        @else
                            <span class="badge bg-secondary">In Progress</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-3">No registrations.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
