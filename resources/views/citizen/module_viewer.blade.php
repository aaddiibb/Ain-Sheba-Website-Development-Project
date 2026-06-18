@extends('layouts.app')

@section('title', Str::limit($program->title, 50) . ' — Ain Sheba')

@section('content')

<div class="ain-module-topbar sticky-top text-white">
    <div class="container-fluid d-flex align-items-center justify-content-between gap-3">
        <div class="small fw-semibold text-uppercase letter-spacing-1">Ain Sheba</div>
        <div class="flex-grow-1 text-center fw-semibold text-truncate px-3" style="max-width: 60%;">
            {{ Str::limit($program->title, 50) }}
        </div>
        <div class="d-flex align-items-center gap-3 flex-shrink-0">
            <span id="module-counter" class="small text-nowrap">{{ $completedCount }} of {{ $totalCount }} completed</span>
            <a href="{{ route('citizen.dashboard') }}" class="btn btn-sm btn-outline-light">Exit</a>
        </div>
    </div>
</div>

<div class="container-fluid mt-0">
    <div class="row g-0">
        <div class="col-md-8 px-4 py-4">
            <h2 class="fw-bold mb-3" style="color:var(--ain-primary)">{{ $module->title }}</h2>

            @if ($module->resource_url)
                @php
                    $resourceUrl = $module->resource_url;
                    $videoId = null;

                    if (str_contains($resourceUrl, 'youtube.com')) {
                        preg_match('/[?&]v=([^&]+)/', $resourceUrl, $matches);
                        $videoId = $matches[1] ?? null;
                    } elseif (str_contains($resourceUrl, 'youtu.be')) {
                        preg_match('~/([^?&/]+)~', $resourceUrl, $matches);
                        $videoId = $matches[1] ?? null;
                    }
                @endphp

                @if ($videoId)
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                @else
                    <a href="{{ $module->resource_url }}" target="_blank" rel="noopener" class="btn btn-outline-primary mb-3">
                        <i class="bi bi-link-45deg"></i> View Resource
                    </a>
                @endif
            @endif

            <div class="ain-module-content mb-3">
                {!! nl2br(e($module->content)) !!}
            </div>

            <div class="text-muted small mb-4">
                <i class="bi bi-clock me-1"></i>Estimated reading: {{ $module->duration_minutes }} min
            </div>

            @if ($registration)
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    @if (!$isCompleted)
                        <button
                            type="button"
                            id="btn-mark-complete"
                            data-module-id="{{ $module->id }}"
                            class="btn ain-btn-accent"
                        >
                            Mark as Complete
                        </button>
                    @else
                        <span id="completion-badge" class="badge bg-success py-2 px-3 d-inline-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-1"></i>Completed on {{ $moduleProgress->completed_at->format('d M Y') }}
                        </span>
                    @endif

                    @if ($module->assessment)
                        <a href="{{ route('citizen.assessment.show', $module->assessment->id) }}" class="btn btn-outline-primary">
                            Take Assessment
                        </a>
                    @endif
                </div>
            @endif

            <div class="d-flex flex-wrap gap-2">
                @if ($prevModule)
                    <a href="{{ route('citizen.module.show', [$program->slug, $prevModule->id]) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Previous
                    </a>
                @else
                    <button class="btn btn-outline-secondary" disabled>Previous</button>
                @endif

                @if ($nextModule)
                    <a href="{{ route('citizen.module.show', [$program->slug, $nextModule->id]) }}" class="btn btn-outline-primary">
                        Next <i class="bi bi-arrow-right"></i>
                    </a>
                @else
                    <button class="btn btn-outline-primary" disabled>Next</button>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <aside class="ain-module-sidebar">
                <h5 class="fw-bold mb-2">Course Contents</h5>
                <div class="text-muted small mb-3">{{ $completedCount }} / {{ $totalCount }} completed</div>

                <div class="overflow-auto pe-1" style="max-height: calc(100vh - 140px);">
                    @foreach ($modules as $m)
                        @php
                            $isCurrentModule = $m->id === $module->id;
                            $isModuleCompleted = $completedIds->contains($m->id);
                        @endphp
                        <a
                            href="{{ route('citizen.module.show', [$program->slug, $m->id]) }}"
                            class="ain-module-item {{ $isCurrentModule ? 'active' : '' }}"
                        >
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi {{ $isModuleCompleted ? 'bi-check-circle-fill text-success' : ($isCurrentModule ? 'bi-play-circle-fill text-primary' : 'bi-circle text-muted') }} ain-module-icon mt-1"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-truncate">{{ $m->title }}</div>
                                    <div class="small text-muted">
                                        {{ $m->duration_minutes }} min
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</div>

@endsection