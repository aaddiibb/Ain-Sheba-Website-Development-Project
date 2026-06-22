@extends('lawyer._layout')

@section('title', 'Edit Module — Ain Sheba')

@section('lawyer-content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('lawyer.dashboard') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('lawyer.programs.index') }}">My Programs</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('lawyer.programs.show', $program->id) }}">{{ $program->title }}</a>
        </li>
        <li class="breadcrumb-item active">Edit Module</li>
    </ol>
</nav>

<h4 class="fw-bold mb-4">Edit Module</h4>

<div class="card border-0 shadow-sm p-4">
    <form method="POST" action="{{ route('lawyer.modules.update', [$program->id, $module->id]) }}">
        @csrf
        @method('PATCH')

        {{-- Module Title --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Module Title <span class="text-danger">*</span></label>
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $module->title) }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Legal Content --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Legal Content</label>
            <textarea name="content" rows="8"
                      class="form-control @error('content') is-invalid @enderror"
                      placeholder="Explain the law or right in plain language. Be thorough — this is what citizens will read.">{{ old('content', $module->content) }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Resource URL --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Resource URL</label>
            <input type="text" name="resource_url"
                   class="form-control @error('resource_url') is-invalid @enderror"
                   value="{{ old('resource_url', $module->resource_url) }}"
                   placeholder="https://...">
            <div class="form-text">Optional: link to a YouTube video, government website, or PDF of the actual law.</div>
            @error('resource_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Duration --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Duration (minutes) <span class="text-danger">*</span></label>
            <input type="number" name="duration_minutes" min="0"
                   class="form-control @error('duration_minutes') is-invalid @enderror"
                   value="{{ old('duration_minutes', $module->duration_minutes) }}"
                   required style="max-width:180px">
            @error('duration_minutes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Free Preview --}}
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_free" id="is_free" value="1"
                       {{ old('is_free', $module->is_free) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="is_free">Free Preview</label>
            </div>
            <div class="form-text ms-4">Allow unregistered citizens to preview this module.</div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn ain-btn-accent px-4">
                <i class="bi bi-save me-1"></i>Save Changes
            </button>
            <a href="{{ route('lawyer.programs.show', $program->id) }}"
               class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>

{{-- Assessment Panel --}}
<div class="card border-0 shadow-sm mt-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Knowledge Assessment</h6>
        @if ($module->assessment)
            <div class="d-flex gap-2">
                <a href="{{ route('lawyer.assessment.edit', [$module->id, $module->assessment->id]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i>Edit Assessment
                </a>
                <form method="POST"
                      action="{{ route('lawyer.assessment.destroy', [$module->id, $module->assessment->id]) }}"
                      onsubmit="return confirm('Delete this assessment and all its questions?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('lawyer.assessment.create', $module->id) }}" class="btn btn-sm ain-btn-accent">
                <i class="bi bi-plus-lg me-1"></i>Add Assessment
            </a>
        @endif
    </div>

    @if ($module->assessment)
        <div class="d-flex flex-wrap gap-3 text-muted small">
            <span><i class="bi bi-question-circle me-1"></i>{{ $module->assessment->questions()->count() }} questions</span>
            <span><i class="bi bi-percent me-1"></i>{{ $module->assessment->passing_score }}% to pass</span>
            @if ($module->assessment->time_limit_minutes)
                <span><i class="bi bi-clock me-1"></i>{{ $module->assessment->time_limit_minutes }} min</span>
            @endif
        </div>
    @else
        <p class="text-muted small mb-0">
            No assessment attached. Citizens won't see a "Take Assessment" button on this module.
        </p>
    @endif
</div>

@endsection
