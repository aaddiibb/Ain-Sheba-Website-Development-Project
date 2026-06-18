@extends('layouts.app')

@section('title', 'Find a Legal Expert — Ain Sheba')

@section('content')

{{-- HERO --}}
<section class="ain-section py-4" style="background: var(--ain-primary); color: white;">
    <div class="container">
        <h1 class="fw-bold mb-1">Find a Legal Expert</h1>
        <p class="mb-0 opacity-75">Browse our certified lawyers and book a consultation to get personalized legal guidance.</p>
    </div>
</section>

<div class="container py-4">

    {{-- SEARCH BAR --}}
    <div class="card border-0 shadow-sm mb-4 bg-white">
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

    {{-- LAWYER GRID --}}
    <div class="row g-4">
        @forelse ($lawyers as $lawyer)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">

                {{-- Avatar --}}
                @if ($lawyer->profile_picture)
                    <img src="{{ asset('uploads/' . $lawyer->profile_picture) }}"
                         class="rounded-circle mx-auto mb-3"
                         style="width:80px;height:80px;object-fit:cover;"
                         alt="{{ $lawyer->name }}">
                @else
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width:80px;height:80px;background:var(--ain-primary);font-size:2rem;">
                        {{ strtoupper(substr($lawyer->name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-1">{{ $lawyer->name }}</h5>

                {{-- Consultation fee --}}
                @if ($lawyer->consultation_fee && $lawyer->consultation_fee > 0)
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle mb-2">
                        BDT {{ number_format($lawyer->consultation_fee, 0) }} / session
                    </span>
                @else
                    <span class="badge bg-success-subtle text-success border border-success-subtle mb-2">
                        Free Consultation
                    </span>
                @endif

                {{-- Bio excerpt --}}
                <p class="text-muted small mb-3">{{ Str::limit($lawyer->bio ?? 'No bio provided.', 120) }}</p>

                {{-- Programs count --}}
                <div class="d-flex justify-content-center gap-3 mb-3 text-muted small">
                    <span><i class="bi bi-journal-bookmark me-1"></i>{{ $lawyer->programs_count }} program{{ $lawyer->programs_count !== 1 ? 's' : '' }}</span>
                </div>

                <a href="{{ route('lawyers.show', $lawyer->id) }}" class="btn ain-btn-accent btn-sm mt-auto">
                    <i class="bi bi-person me-1"></i>View Profile
                </a>

            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted opacity-50"></i>
                <p class="text-muted mt-3">No lawyers found matching your search.</p>
                <a href="{{ route('lawyers.index') }}" class="btn btn-outline-secondary btn-sm">Clear Search</a>
            </div>
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
