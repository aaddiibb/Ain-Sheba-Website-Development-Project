@extends('layouts.app')

@section('title', $assessment->title . ' — Ain Sheba')

@section('content')

{{-- Top bar --}}
<div class="ain-module-topbar sticky-top text-white">
    <div class="container-fluid d-flex align-items-center justify-content-between gap-3">
        <div class="small fw-semibold text-uppercase">Ain Sheba</div>
        <div class="flex-grow-1 text-center fw-semibold text-truncate px-3" style="max-width: 60%;">
            {{ Str::limit($assessment->title, 50) }}
        </div>
        <div class="d-flex align-items-center gap-3 flex-shrink-0">
            @if ($assessment->time_limit_minutes)
                <span id="assessment-timer" class="small text-nowrap fw-semibold">
                    {{ str_pad($assessment->time_limit_minutes, 2, '0', STR_PAD_LEFT) }}:00
                </span>
            @endif
            <a href="{{ route('citizen.assessment.show', $assessment->id) }}"
               class="btn btn-sm btn-outline-light">Cancel</a>
        </div>
    </div>
</div>

{{-- Questions --}}
<div class="container py-4" style="max-width: 720px;">

    <form method="POST"
          action="{{ route('citizen.assessment.submit', $assessment->id) }}"
          id="assessment-form">
        @csrf

        @foreach ($assessment->questions as $index => $question)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                <div class="d-flex align-items-start gap-3 mb-3">
                    <span class="badge bg-secondary rounded-pill flex-shrink-0 mt-1" style="min-width: 28px;">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-grow-1">
                        <p class="fw-bold mb-2" style="font-size: 1.05rem;">{{ $question->question }}</p>
                        <span class="badge bg-light text-muted border small">
                            @if ($question->type === 'single') Single Choice
                            @elseif ($question->type === 'multiple') Multiple Choice
                            @else True or False
                            @endif
                        </span>
                    </div>
                </div>

                <div class="ps-4 ms-2">
                    @foreach ($question->options as $option)
                        <div class="form-check mb-2">
                            @if ($question->type === 'multiple')
                                <input class="form-check-input" type="checkbox"
                                       name="answers[{{ $question->id }}][]"
                                       value="{{ $option->id }}"
                                       id="opt-{{ $option->id }}">
                            @else
                                <input class="form-check-input" type="radio"
                                       name="answers[{{ $question->id }}][]"
                                       value="{{ $option->id }}"
                                       id="opt-{{ $option->id }}">
                            @endif
                            <label class="form-check-label" for="opt-{{ $option->id }}">
                                {{ $option->option_text }}
                            </label>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        @endforeach

        <div class="d-grid mb-4">
            <button type="submit" class="btn ain-btn-accent btn-lg">
                <i class="bi bi-send me-2"></i>Submit Assessment
            </button>
        </div>
    </form>

</div>

@endsection

@push('scripts')
@if ($assessment->time_limit_minutes)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initAssessmentTimer({{ $assessment->time_limit_minutes * 60 }});
    });
</script>
@endif
@endpush
