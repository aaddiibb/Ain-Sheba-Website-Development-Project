@extends('lawyer._layout')

@section('title', 'Create Program — Ain Sheba')

@section('lawyer-content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Create Program</h4>
    <a href="{{ route('lawyer.programs.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>Back
    </a>
</div>

<div class="card p-4">
    <form method="POST" action="{{ route('lawyer.programs.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Legal Area --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Legal Area <span class="text-danger">*</span></label>
            <select name="legal_area_id" class="form-select @error('legal_area_id') is-invalid @enderror" required>
                <option value="">— Select Legal Area —</option>
                @foreach ($legalAreas as $area)
                    <option value="{{ $area->id }}" {{ old('legal_area_id') == $area->id ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
            @error('legal_area_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
            <textarea name="description" rows="5"
                      class="form-control @error('description') is-invalid @enderror"
                      required>{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-3">
            {{-- Level --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                    <option value="">— Select —</option>
                    <option value="basic"        {{ old('level') === 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced"     {{ old('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
                @error('level')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Language --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">Language <span class="text-danger">*</span></label>
                <input type="text" name="language"
                       class="form-control @error('language') is-invalid @enderror"
                       value="{{ old('language', 'Bengali') }}" required>
                @error('language')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="draft"      {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published"  {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Thumbnail --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Thumbnail</label>
            <div class="mb-2">
                <img id="program-thumb-preview" src="" alt="Preview"
                     class="rounded" style="width:120px;height:80px;object-fit:cover;display:none">
            </div>
            <input type="file" id="program-thumb-input" name="thumbnail"
                   class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
            @error('thumbnail')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn ain-btn-accent px-4">
            <i class="bi bi-save me-1"></i>Create Program
        </button>
    </form>
</div>

@push('scripts')
<script>
    initImagePreview('program-thumb-input', 'program-thumb-preview');
</script>
@endpush

@endsection
