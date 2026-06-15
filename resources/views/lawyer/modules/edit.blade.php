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

@endsection
