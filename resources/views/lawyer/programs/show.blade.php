@extends('lawyer._layout')

@section('title', '{{ $program->title }} — Ain Sheba')

@section('lawyer-content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Program Header --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="row g-0">
        <div class="col-md-3">
            @if ($program->thumbnail)
                <img src="/{{ $program->thumbnail }}" class="img-fluid rounded-start h-100"
                     style="object-fit:cover;min-height:180px" alt="{{ $program->title }}">
            @else
                <div class="d-flex align-items-center justify-content-center rounded-start h-100"
                     style="background:var(--ain-primary);min-height:180px">
                    <i class="bi bi-book text-white" style="font-size:3rem"></i>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="badge ain-badge-area">{{ $program->legalArea->name ?? '—' }}</span>
                    <span class="ain-badge-{{ $program->status }}">{{ ucfirst($program->status) }}</span>
                </div>
                <h4 class="card-title fw-bold">{{ $program->title }}</h4>
                <div class="d-flex gap-4 mt-3 flex-wrap">
                    <div class="text-center">
                        <div class="fw-bold fs-5">{{ $program->registrations_count }}</div>
                        <small class="text-muted">Citizens</small>
                    </div>
                    <div class="text-center">
                        @if ($program->feedback_avg_rating)
                            <div class="fw-bold fs-5">{{ number_format($program->feedback_avg_rating, 1) }}</div>
                            <small class="text-muted">Avg Rating</small>
                        @else
                            <div class="fw-bold fs-5 text-muted">—</div>
                            <small class="text-muted">No feedback yet</small>
                        @endif
                    </div>
                    <div class="text-center">
                        <div class="fw-bold fs-5">{{ $program->modules->count() }}</div>
                        <small class="text-muted">Modules</small>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold fs-5">{{ ucfirst($program->level) }}</div>
                        <small class="text-muted">Level</small>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('lawyer.programs.edit', $program->id) }}"
                       class="btn btn-sm ain-btn-accent">
                        <i class="bi bi-pencil me-1"></i>Edit Program
                    </a>
                    <a href="{{ route('lawyer.programs.index') }}"
                       class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-1"></i>All Programs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Description --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-semibold mb-2">Description</h6>
        <p class="text-muted mb-0" style="white-space:pre-line">{{ $program->description }}</p>
    </div>
</div>

{{-- Modules Panel — placeholder for Day 7 --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Modules</h6>
        <div id="modules-placeholder" class="alert alert-info mb-0">
            Modules will be added on Day 7.
        </div>
    </div>
</div>

@endsection
