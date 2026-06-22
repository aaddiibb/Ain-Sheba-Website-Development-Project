@extends('citizen._layout')

@section('title', $assessment->title . ' — Ain Sheba')

@section('citizen-content')

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ $assessment->title }}</h4>
        <p class="text-muted mb-0 small">
            <i class="bi bi-book me-1"></i>{{ $assessment->module->title }}
            &nbsp;·&nbsp;
            <a href="{{ route('citizen.module.show', [$assessment->module->program->slug, $assessment->module->id]) }}"
               class="text-decoration-none" style="color: var(--ain-primary)">Back to Module</a>
        </p>
    </div>
    <a href="{{ route('citizen.assessment.take', $assessment->id) }}" class="btn ain-btn-accent">
        <i class="bi bi-pencil-square me-1"></i>{{ $attempts->isNotEmpty() ? 'Retake Assessment' : 'Take Assessment' }}
    </a>
</div>

{{-- Info Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3 text-center">
            <small class="text-muted">Questions</small>
            <span class="fw-bold fs-4 mt-1">{{ $assessment->questions->count() }}</span>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3 text-center">
            <small class="text-muted">Passing Score</small>
            <span class="fw-bold fs-4 mt-1">{{ $assessment->passing_score }}%</span>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3 text-center">
            <small class="text-muted">Time Limit</small>
            <span class="fw-bold fs-4 mt-1">
                {{ $assessment->time_limit_minutes ? $assessment->time_limit_minutes . ' min' : '—' }}
            </span>
        </div>
    </div>
</div>

{{-- Attempt History --}}
<div class="card border-0 shadow-sm">
    <div class="card-header fw-semibold bg-white py-3">Attempt History</div>

    @if ($attempts->isEmpty())
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
            No attempts yet. Take the assessment to see your results here.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Score</th>
                        <th>Result</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attempts as $attempt)
                        <tr>
                            <td class="small">{{ $attempt->attempted_at->format('d M Y, H:i') }}</td>
                            <td class="fw-semibold">{{ $attempt->score }}%</td>
                            <td>
                                @if ($attempt->passed)
                                    <span class="badge bg-success">Passed</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('citizen.assessment.result', $attempt->id) }}"
                                   class="small text-decoration-none" style="color: var(--ain-primary)">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
