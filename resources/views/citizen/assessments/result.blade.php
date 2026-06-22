@extends('citizen._layout')

@section('title', 'Assessment Result — Ain Sheba')

@section('citizen-content')

@php
    $module  = $attempt->assessment->module;
    $program = $module->program;
@endphp

{{-- Result Banner --}}
@if ($attempt->passed)
    <div class="rounded p-5 text-white text-center mb-4" style="background: var(--ain-success);">
        <i class="bi bi-check-circle-fill d-block mb-2" style="font-size: 3rem;"></i>
        <h2 class="fw-bold mb-1">Assessment Passed!</h2>
        <p class="fs-3 fw-semibold mb-1">{{ $attempt->score }}%</p>
        <small class="opacity-75">Required: {{ $attempt->assessment->passing_score }}%</small>
    </div>
@else
    <div class="rounded p-5 text-white text-center mb-4" style="background: var(--ain-danger);">
        <i class="bi bi-x-circle-fill d-block mb-2" style="font-size: 3rem;"></i>
        <h2 class="fw-bold mb-1">Not Passed</h2>
        <p class="fs-3 fw-semibold mb-1">{{ $attempt->score }}%</p>
        <small class="opacity-75">Required: {{ $attempt->assessment->passing_score }}%</small>
    </div>
@endif

{{-- Details --}}
<div class="card border-0 shadow-sm p-4 mb-4">
    <h6 class="fw-semibold mb-3">Details</h6>
    <dl class="row mb-0">
        <dt class="col-sm-4 text-muted fw-normal">Assessment</dt>
        <dd class="col-sm-8 fw-semibold">{{ $attempt->assessment->title }}</dd>

        <dt class="col-sm-4 text-muted fw-normal">Program</dt>
        <dd class="col-sm-8">{{ $program->title }}</dd>

        <dt class="col-sm-4 text-muted fw-normal">Module</dt>
        <dd class="col-sm-8">{{ $module->title }}</dd>

        <dt class="col-sm-4 text-muted fw-normal">Attempted</dt>
        <dd class="col-sm-8">{{ $attempt->attempted_at->format('d M Y, H:i') }}</dd>

        <dt class="col-sm-4 text-muted fw-normal">Time Taken</dt>
        <dd class="col-sm-8 text-muted">N/A</dd>
    </dl>
</div>

{{-- Actions --}}
<div class="d-flex flex-wrap gap-2">
    <a href="{{ route('citizen.module.show', [$program->slug, $module->id]) }}"
       class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-1"></i>Back to Module
    </a>
    <a href="{{ route('citizen.assessment.show', $attempt->assessment->id) }}"
       class="btn ain-btn-accent">
        <i class="bi bi-arrow-repeat me-1"></i>{{ $attempt->passed ? 'View All Attempts' : 'Retake Assessment' }}
    </a>
</div>

@endsection
