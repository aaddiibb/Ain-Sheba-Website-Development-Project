@extends('layouts.app')

@section('title', 'Find a Legal Expert — Ain Sheba')

@section('content')

{{-- HERO --}}
<section class="ain-hero py-5">
    <div class="container">
        <h1 class="fw-bold mb-1" style="font-size:2.25rem"><i class="bi bi-person-badge me-2"></i>Find a Legal Expert</h1>
        <p class="mb-0">Browse our certified lawyers and book a consultation to get personalized legal guidance.</p>
    </div>
</section>

<div class="container py-4">

    {{-- SEARCH BAR --}}
    <div class="card mb-4 bg-white">
        <div class="card-body">
            <form method="GET" action="{{ route('lawyers.index') }}" class="row g-2 align-items-end">
                <div class="col-md-9">
                    <label class="form-label small fw-semibold text-muted">Search by name or bio</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Lawyer name or specialization…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn ain-btn-accent w-100">Search</button>
                </div>
                @if (request('search'))
                    <div class="col-md-1">
                        <a href="{{ route('lawyers.index') }}" class="btn btn-outline-secondary w-100" title="Clear">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <p class="text-muted small mb-3">{{ $lawyers->total() }} lawyer{{ $lawyers->total() !== 1 ? 's' : '' }} found</p>

    {{-- LAWYER LIST — horizontal cards --}}
    <div class="d-flex flex-column gap-3">
        @forelse ($lawyers as $lawyer)
        <div class="card">
            <div class="card-body d-flex align-items-center gap-4 flex-wrap">

                {{-- Avatar --}}
                @if ($lawyer->profile_picture)
                    <img src="{{ asset($lawyer->profile_picture) }}"
                         class="rounded-circle flex-shrink-0"
                         style="width:80px;height:80px;object-fit:cover;border:3px solid var(--ain-accent);"
                         alt="{{ $lawyer->name }}">
                @else
                    <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width:80px;height:80px;background:linear-gradient(135deg, var(--ain-primary), var(--ain-primary-light));font-size:2rem;border:3px solid var(--ain-accent);">
                        {{ strtoupper(substr($lawyer->name, 0, 1)) }}
                    </div>
                @endif

                {{-- Info --}}
                <div class="flex-grow-1" style="min-width:220px">
                    <h5 class="fw-bold mb-1">{{ $lawyer->name }}</h5>
                    <p class="text-muted small mb-2">{{ Str::limit($lawyer->bio ?? 'No bio provided.', 120) }}</p>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @if ($lawyer->consultation_fee && $lawyer->consultation_fee > 0)
                            <span class="badge ain-badge-pending"><i class="bi bi-currency-exchange me-1"></i>BDT {{ number_format($lawyer->consultation_fee, 0) }} / session</span>
                        @else
                            <span class="badge ain-badge-published"><i class="bi bi-gift me-1"></i>Free Consultation</span>
                        @endif
                        <span class="badge ain-badge-area"><i class="bi bi-journal-bookmark me-1"></i>{{ $lawyer->programs_count }} program{{ $lawyer->programs_count !== 1 ? 's' : '' }}</span>
                    </div>
                </div>

                {{-- Book button --}}
                <a href="{{ route('lawyers.show', $lawyer->id) }}" class="btn ain-btn-accent flex-shrink-0">
                    <i class="bi bi-person"></i>View Profile
                </a>

            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-people display-1 text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="text-muted fw-semibold">No lawyers found</h5>
            <p class="text-muted small mb-3">Try adjusting your search.</p>
            <a href="{{ route('lawyers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle"></i>Clear Search</a>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if ($lawyers->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $lawyers->links() }}
        </div>
    @endif

</div>

@endsection
