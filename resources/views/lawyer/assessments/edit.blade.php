@extends('lawyer._layout')

@section('title', 'Edit Assessment — Ain Sheba')

@section('lawyer-content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('lawyer.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lawyer.programs.index') }}">My Programs</a></li>
        <li class="breadcrumb-item">
            <a href="{{ route('lawyer.programs.show', $module->program->id) }}">{{ Str::limit($module->program->title, 30) }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('lawyer.modules.edit', [$module->program_id, $module->id]) }}">{{ Str::limit($module->title, 30) }}</a>
        </li>
        <li class="breadcrumb-item active">Edit Assessment</li>
    </ol>
</nav>

<h4 class="fw-bold mb-4">Edit Assessment</h4>

@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('lawyer.assessment.update', [$module->id, $assessment->id]) }}">
    @csrf
    @method('PATCH')

    {{-- Assessment Details --}}
    <div class="card border-0 shadow-sm p-4 mb-4">
        <h6 class="fw-bold mb-3">Assessment Details</h6>

        <div class="mb-3">
            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $assessment->title) }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Passing Score (%) <span class="text-danger">*</span></label>
                <input type="number" name="passing_score"
                       class="form-control @error('passing_score') is-invalid @enderror"
                       min="0" max="100" value="{{ old('passing_score', $assessment->passing_score) }}" required>
                <div class="form-text">e.g. 70 = citizen needs 70% to pass</div>
                @error('passing_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Time Limit (minutes)</label>
                <input type="number" name="time_limit_minutes"
                       class="form-control @error('time_limit_minutes') is-invalid @enderror"
                       min="1" value="{{ old('time_limit_minutes', $assessment->time_limit_minutes) }}"
                       placeholder="Leave blank for unlimited">
                @error('time_limit_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- Questions Builder --}}
    <div class="card border-0 shadow-sm p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Questions</h6>
            <button type="button" id="add-question-btn" class="btn btn-sm ain-btn-accent">
                <i class="bi bi-plus-lg me-1"></i>Add Question
            </button>
        </div>

        @error('questions')
            <div class="alert alert-warning py-2 small mb-3">{{ $message }}</div>
        @enderror

        <div id="questions-list"></div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn ain-btn-accent px-4">
            <i class="bi bi-save me-1"></i>Save Changes
        </button>
        <a href="{{ route('lawyer.modules.edit', [$module->program_id, $module->id]) }}"
           class="btn btn-outline-secondary px-4">Cancel</a>
    </div>
</form>

@endsection

@push('scripts')
<script>
    var existingAssessment = @json($assessment);
    document.addEventListener('DOMContentLoaded', function () {
        initAssessmentBuilder();
    });
</script>
@endpush
