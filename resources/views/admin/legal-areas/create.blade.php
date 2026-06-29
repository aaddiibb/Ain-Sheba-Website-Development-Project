@extends('admin._layout')

@section('title', 'Add Legal Area — Admin — Ain Sheba')

@section('admin-content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.legal-areas.index') }}">Legal Areas</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
</nav>

<div class="card border-0 shadow-sm" style="max-width:600px;">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-plus-circle me-2"></i>Add Legal Area
    </div>
    <div class="card-body">
        <form action="{{ route('admin.legal-areas.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="e.g. Family Law" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Bootstrap Icon Class <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text" id="icon-preview">
                        <i class="bi bi-question-circle" id="icon-display" style="font-size:1.2rem"></i>
                    </span>
                    <input type="text" name="icon" id="icon-input" value="{{ old('icon') }}"
                           class="form-control @error('icon') is-invalid @enderror"
                           placeholder="e.g. bi-house-door" required
                           oninput="document.getElementById('icon-display').className='bi '+this.value">
                    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <small class="text-muted">Use any Bootstrap Icon class name, e.g. <code>bi-briefcase</code></small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="3" placeholder="Brief description of this legal area…">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn ain-btn-accent">
                    <i class="bi bi-check-circle me-1"></i>Create Legal Area
                </button>
                <a href="{{ route('admin.legal-areas.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
