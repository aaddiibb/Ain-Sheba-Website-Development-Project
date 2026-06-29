@extends('admin._layout')

@section('title', $program->title . ' — Admin — Ain Sheba')

@section('admin-content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">Programs</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($program->title, 40) }}</li>
    </ol>
</nav>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">

    {{-- Left: Details --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-1">{{ $program->title }}</h5>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @if ($program->legalArea)
                        <span class="badge ain-badge-area">{{ $program->legalArea->name }}</span>
                    @endif
                    <span class="badge ain-badge-level text-capitalize">{{ $program->level }}</span>
                    <span class="badge bg-light text-muted border"><i class="bi bi-translate me-1"></i>{{ $program->language }}</span>
                </div>
                <p class="text-muted small mb-3">{{ $program->description }}</p>

                <div class="row g-3 text-center">
                    <div class="col-3">
                        <div class="card border-0 bg-light p-2">
                            <div class="fw-bold">{{ $program->modules->count() }}</div>
                            <small class="text-muted">Modules</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card border-0 bg-light p-2">
                            <div class="fw-bold">{{ $program->registrations_count }}</div>
                            <small class="text-muted">Citizens</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card border-0 bg-light p-2">
                            <div class="fw-bold">{{ $program->feedback_avg_rating ? number_format($program->feedback_avg_rating, 1) : '—' }}</div>
                            <small class="text-muted">Avg Rating</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card border-0 bg-light p-2">
                            <div class="fw-bold">{{ $program->created_at->format('M Y') }}</div>
                            <small class="text-muted">Created</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modules list --}}
        @if ($program->modules->isNotEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-collection me-2"></i>Modules
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($program->modules as $idx => $mod)
                <li class="list-group-item d-flex align-items-center gap-2 small">
                    <span class="badge bg-secondary" style="min-width:24px;">{{ $idx + 1 }}</span>
                    <span class="flex-grow-1">{{ $mod->title }}</span>
                    @if ($mod->is_free)
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Preview</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    {{-- Right: Status + Actions --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Change Status</h6>
                <form action="{{ route('admin.programs.status', $program->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        <option value="draft"     {{ $program->status === 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ $program->status === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived"  {{ $program->status === 'archived'  ? 'selected' : '' }}>Archived</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">Lawyer</h6>
                <div class="fw-semibold">{{ $program->lawyer->name ?? '—' }}</div>
                <div class="text-muted small">{{ $program->lawyer->email ?? '' }}</div>
                @if ($program->lawyer)
                    <a href="{{ route('admin.users.show', $program->lawyer->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                        View Profile
                    </a>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST"
              onsubmit="return confirm('Permanently delete this program?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-trash me-1"></i>Delete Program
            </button>
        </form>
    </div>

</div>

@endsection
