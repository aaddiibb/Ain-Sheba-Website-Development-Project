@extends('admin._layout')

@section('title', 'Edit ' . $area->name . ' — Admin — Ain Sheba')

@section('admin-content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.legal-areas.index') }}">Legal Areas</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>

<div class="card border-0 shadow-sm" style="max-width:600px;">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-pencil me-2"></i>Edit: {{ $area->name }}
    </div>
    <div class="card-body">
        <form action="{{ route('admin.legal-areas.update', $area->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $area->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Bootstrap Icon Class <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi {{ old('icon', $area->icon) }}" id="icon-display" style="font-size:1.2rem"></i>
                    </span>
                    <input type="text" name="icon" id="icon-input" value="{{ old('icon', $area->icon) }}"
                           class="form-control @error('icon') is-invalid @enderror" required
                           oninput="document.getElementById('icon-display').className='bi '+this.value">
                    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="3">{{ old('description', $area->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn ain-btn-accent">
                    <i class="bi bi-check-circle me-1"></i>Update
                </button>
                <a href="{{ route('admin.legal-areas.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
