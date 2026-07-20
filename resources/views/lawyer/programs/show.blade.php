@extends('lawyer._layout')

@section('title', '{{ $program->title }} — Ain Sheba')

@section('lawyer-content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('lawyer.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('lawyer.programs.index') }}">Programs</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($program->title, 40) }}</li>
    </ol>
</nav>

{{-- Program Header --}}
<div class="card mb-4">
    <div class="row g-0">
        <div class="col-md-3">
            @if ($program->thumbnail)
                <img src="/{{ $program->thumbnail }}" class="img-fluid rounded-start h-100"
                     style="object-fit:cover;min-height:180px" alt="{{ $program->title }}">
            @else
                <div class="d-flex align-items-center justify-content-center rounded-start h-100"
                     style="background:linear-gradient(135deg, var(--ain-primary) 0%, var(--ain-primary-light) 100%);min-height:180px">
                    <i class="bi bi-book text-white" style="font-size:3rem;opacity:.9"></i>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="badge ain-badge-area">{{ $program->legalArea->name ?? '—' }}</span>
                    <span class="badge ain-badge-{{ $program->status }}">{{ ucfirst($program->status) }}</span>
                </div>
                <h4 class="card-title fw-bold">{{ $program->title }}</h4>
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    <span class="badge ain-badge-confirmed"><i class="bi bi-people me-1"></i>{{ $program->registrations_count }} Citizens</span>
                    @if ($program->feedback_avg_rating)
                        <span class="badge ain-badge-published"><i class="bi bi-star-fill me-1"></i>{{ number_format($program->feedback_avg_rating, 1) }} Avg Rating</span>
                    @else
                        <span class="badge ain-badge-draft"><i class="bi bi-star me-1"></i>No feedback yet</span>
                    @endif
                    <span class="badge ain-badge-pending"><i class="bi bi-collection me-1"></i>{{ $program->modules->count() }} Modules</span>
                    <span class="badge ain-badge-area"><i class="bi bi-bar-chart-steps me-1"></i>{{ ucfirst($program->level) }}</span>
                </div>
                <div class="mt-3">
                    <a href="{{ route('lawyer.programs.edit', $program->id) }}"
                       class="btn btn-sm ain-btn-accent">
                        <i class="bi bi-pencil"></i>Edit Program
                    </a>
                    <a href="{{ route('lawyer.programs.index') }}"
                       class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left"></i>All Programs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Description --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-semibold mb-2"><i class="bi bi-file-text me-1 text-primary"></i>Description</h6>
        <p class="text-muted mb-0" style="white-space:pre-line">{{ $program->description }}</p>
    </div>
</div>

{{-- Modules Panel --}}
<div class="card">
    <div class="card-body">
        <div class="ain-section-header">
            <h5><i class="bi bi-collection"></i>Modules ({{ $program->modules->count() }})</h5>
            <a href="{{ route('lawyer.modules.create', $program->id) }}"
               class="btn btn-sm ain-btn-accent">
                <i class="bi bi-plus-lg"></i>Add Module
            </a>
        </div>

        @if ($program->modules->isEmpty())
            <p class="text-muted mb-0">No modules yet. Add your first module above.</p>
        @else
            <ul id="modules-sortable" class="list-unstyled mb-2">
                @foreach ($program->modules as $module)
                <li data-id="{{ $module->id }}">
                    <i class="bi bi-grip-vertical ain-drag-handle"></i>

                    <div class="flex-grow-1">
                        <span class="fw-semibold">{{ $module->title }}</span>
                        <div class="d-flex gap-2 mt-1 flex-wrap">
                            <span class="badge bg-secondary">{{ $module->duration_minutes }} min</span>
                            @if ($module->is_free)
                                <span class="badge bg-success">Free Preview</span>
                            @else
                                <span class="badge bg-light text-secondary border">Paid</span>
                            @endif
                            @if ($module->assessment)
                                <span class="badge bg-info text-white">
                                    <i class="bi bi-clipboard-check me-1"></i>Has Assessment
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="actions">
                        <a href="{{ route('lawyer.modules.edit', [$program->id, $module->id]) }}"
                           class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i>Edit</a>

                        <form method="POST"
                              action="{{ route('lawyer.modules.destroy', [$program->id, $module->id]) }}"
                              class="d-inline"
                              onsubmit="return confirm('Delete module \'{{ addslashes($module->title) }}\'?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i>Delete</button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
            <small class="text-muted">Drag rows to reorder. Order saves automatically.</small>
        @endif
    </div>
</div>

@push('scripts')
<script>
    initModuleSorting({{ $program->id }}, '{{ csrf_token() }}');
</script>
@endpush

@endsection
