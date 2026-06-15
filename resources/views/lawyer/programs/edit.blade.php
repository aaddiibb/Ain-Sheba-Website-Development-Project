@extends('lawyer._layout')

@section('title', 'Edit Program — Ain Sheba')

@section('lawyer-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Edit Program</h4>
    <a href="{{ route('lawyer.programs.show', $program->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card border-0 shadow-sm p-4">
    <form method="POST" action="{{ route('lawyer.programs.update', $program->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $program->title) }}" required>
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
                    <option value="{{ $area->id }}"
                        {{ old('legal_area_id', $program->legal_area_id) == $area->id ? 'selected' : '' }}>
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
                      required>{{ old('description', $program->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-3">
            {{-- Level --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                    <option value="basic"        {{ old('level', $program->level) === 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="intermediate" {{ old('level', $program->level) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced"     {{ old('level', $program->level) === 'advanced' ? 'selected' : '' }}>Advanced</option>
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
                       value="{{ old('language', $program->language) }}" required>
                @error('language')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="draft"     {{ old('status', $program->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $program->status) === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived"  {{ old('status', $program->status) === 'archived' ? 'selected' : '' }}>Archived</option>
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
                @if ($program->thumbnail)
                    <img id="program-thumb-preview" src="/{{ $program->thumbnail }}"
                         class="rounded" style="width:120px;height:80px;object-fit:cover">
                @else
                    <img id="program-thumb-preview" src="" alt="Preview"
                         class="rounded" style="width:120px;height:80px;object-fit:cover;display:none">
                @endif
            </div>
            <input type="file" id="program-thumb-input" name="thumbnail"
                   class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
            <div class="form-text">Leave blank to keep current thumbnail.</div>
            @error('thumbnail')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn ain-btn-accent px-4">
            <i class="bi bi-save me-1"></i>Save Changes
        </button>
    </form>
</div>

@push('scripts')
<script>
    initImagePreview('program-thumb-input', 'program-thumb-preview');
</script>
@endpush

@endsection
