@extends('layouts.app')

@section('title', 'Browse Legal Programs — Ain Sheba')

@section('content')

{{-- HERO --}}
<section class="ain-hero py-5">
    <div class="container">
        <h1 class="fw-bold mb-1" style="font-size:2.25rem"><i class="bi bi-journal-text me-2"></i>Browse Legal Programs</h1>
        <p class="mb-0">Structured legal literacy programs created by certified lawyers — all free to join.</p>
    </div>
</section>

<div class="container py-4">

    {{-- FILTER BAR --}}
    <div class="card mb-4 bg-white sticky-top" style="top:0.75rem;z-index:10">
        <div class="card-body">
            <form method="GET" action="{{ route('programs.index') }}" class="row g-2 align-items-end">
                {{-- Search --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Program title or keyword…" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                    </div>
                </div>

                {{-- Legal Area --}}
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Legal Area</label>
                    <select name="category" class="form-select">
                        <option value="">All Areas</option>
                        @foreach ($legalAreas as $area)
                            <option value="{{ $area->slug }}" {{ request('category') === $area->slug ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Level --}}
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Level</label>
                    <select name="level" class="form-select">
                        <option value="">All Levels</option>
                        <option value="basic" {{ request('level') === 'basic' ? 'selected' : '' }}>Basic</option>
                        <option value="intermediate" {{ request('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ request('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                {{-- Sort --}}
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="" {{ !request('sort') ? 'selected' : '' }}>Newest</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="level_asc" {{ request('sort') === 'level_asc' ? 'selected' : '' }}>Level (Asc)</option>
                    </select>
                </div>

                {{-- Actions --}}
                <div class="col-md-1">
                    <button type="submit" class="btn ain-btn-accent w-100">Go</button>
                </div>

                @if (request()->hasAny(['category','level','search','sort']))
                    <div class="col-12">
                        <a href="{{ route('programs.index') }}" class="btn btn-link btn-sm text-muted p-0">
                            <i class="bi bi-x-circle me-1"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- RESULTS COUNT --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0 small">{{ $programs->total() }} program{{ $programs->total() !== 1 ? 's' : '' }} found</p>
    </div>

    {{-- PROGRAM GRID --}}
    <div class="row g-4">
        @forelse ($programs as $program)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="ain-program-card">
                {{-- Thumbnail --}}
                @if ($program->thumbnail)
                    <img src="{{ asset($program->thumbnail) }}"
                         class="ain-program-thumb" alt="{{ $program->title }}">
                @else
                    <div class="ain-program-thumb ain-program-thumb-placeholder d-flex align-items-center justify-content-center">
                        <span class="display-4 fw-bold text-white">{{ strtoupper(substr($program->title, 0, 1)) }}</span>
                    </div>
                @endif

                <div class="ain-program-body">
                    <h6 class="ain-program-title mb-1">{{ $program->title }}</h6>

                    <small class="text-muted mb-2 d-block">
                        <a href="{{ route('lawyers.show', $program->lawyer->id) }}" class="text-muted text-decoration-none">
                            <i class="bi bi-person-circle me-1"></i>{{ $program->lawyer->name ?? '—' }}
                        </a>
                    </small>

                    <div class="mb-2 d-flex flex-wrap gap-1">
                        @if ($program->legalArea)
                            <span class="badge ain-badge-area">{{ $program->legalArea->name }}</span>
                        @endif
                        <span class="badge ain-badge-level text-capitalize">{{ $program->level }}</span>
                        <span class="badge ain-badge-published"><i class="bi bi-tag me-1"></i>Free</span>
                    </div>

                    <p class="ain-program-meta">{{ Str::limit($program->description, 90) }}</p>
                </div>

                <div class="ain-program-footer">
                    <small class="text-muted">
                        <i class="bi bi-people me-1"></i>{{ $program->registrations_count ?? 0 }} citizens
                    </small>
                    <a href="{{ route('programs.show', $program->slug) }}" class="btn ain-btn-accent btn-sm">View Program</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-journal-x fs-1 text-muted opacity-50"></i>
                <p class="text-muted mt-3">No programs found. Try adjusting your filters.</p>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
            </div>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if ($programs->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $programs->links() }}
        </div>
    @endif

</div>

@endsection
