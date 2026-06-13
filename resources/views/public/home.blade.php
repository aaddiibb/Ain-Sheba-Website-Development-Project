@extends('layouts.app')

@section('title', 'Ain Sheba — Know Your Rights')

@section('content')

{{-- HERO SECTION --}}
<section class="ain-section ain-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold mb-3">Know Your Rights.<br>Empower Yourself.</h1>
                <p class="lead mb-4">Ain Sheba connects citizens with certified legal experts for accessible, structured legal literacy training.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ url('/programs') }}" class="btn ain-btn-accent btn-lg px-4">Browse Programs</a>
                    <a href="{{ url('/lawyers') }}" class="btn btn-outline-light btn-lg px-4">Find a Lawyer</a>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <img src="/images/hero.svg" alt="Scales of Justice" class="img-fluid" style="max-height:280px;">
            </div>
        </div>
    </div>
</section>

{{-- STATS STRIP --}}
<section class="ain-stats-strip py-5 bg-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="ain-stat-card p-3">
                    <div class="ain-counter display-5 fw-bold" data-target="{{ $totalPrograms }}">0</div>
                    <div class="text-muted mt-1">Legal Programs</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="ain-stat-card p-3">
                    <div class="ain-counter display-5 fw-bold" data-target="{{ $totalCitizens }}">0</div>
                    <div class="text-muted mt-1">Trained Citizens</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="ain-stat-card p-3">
                    <div class="ain-counter display-5 fw-bold" data-target="{{ $totalLawyers }}">0</div>
                    <div class="text-muted mt-1">Certified Lawyers</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="ain-stat-card p-3">
                    <div class="ain-counter display-5 fw-bold" data-target="6">0</div>
                    <div class="text-muted mt-1">Legal Areas</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- LEGAL AREAS GRID --}}
<section class="ain-section bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Explore Legal Areas</h2>
            <p class="text-muted">Choose a category to find relevant programs and lawyers.</p>
        </div>
        <div class="row g-4">
            @foreach ($legalAreas as $area)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ url('/programs?category=' . $area->slug) }}" class="text-decoration-none">
                    <div class="ain-area-card card h-100 border-0 shadow-sm p-3 text-center">
                        <i class="bi {{ $area->icon }} ain-area-icon mb-3"></i>
                        <h6 class="fw-semibold mb-1">{{ $area->name }}</h6>
                        <p class="text-muted small mb-0">{{ Str::limit($area->description, 70) }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FEATURED PROGRAMS --}}
<section class="ain-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Featured Programs</h2>
            <p class="text-muted">Hand-picked legal literacy programs from certified lawyers.</p>
        </div>
        <div class="row g-4">
            @forelse ($programs as $program)
            <div class="col-md-6 col-lg-4">
                <div class="ain-program-card card h-100 border-0 shadow-sm">
                    {{-- Thumbnail --}}
                    @if ($program->thumbnail)
                        <img src="{{ asset('uploads/' . $program->thumbnail) }}" class="card-img-top ain-program-thumb" alt="{{ $program->title }}">
                    @else
                        <div class="ain-program-thumb ain-program-thumb-placeholder d-flex align-items-center justify-content-center">
                            <span class="display-4 fw-bold text-white">{{ strtoupper(substr($program->title, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-semibold mb-1">{{ $program->title }}</h6>
                        <small class="text-muted mb-2">
                            <i class="bi bi-person-circle me-1"></i>{{ $program->lawyer->name ?? '—' }}
                        </small>
                        <div class="mb-3 d-flex flex-wrap gap-1">
                            <span class="badge ain-badge-level text-capitalize">{{ $program->level }}</span>
                            @if ($program->legalArea)
                                <span class="badge ain-badge-area">{{ $program->legalArea->name }}</span>
                            @endif
                        </div>
                        <a href="{{ url('/programs/' . $program->slug) }}" class="btn ain-btn-accent btn-sm mt-auto">View Program</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-4">
                No published programs yet. Check back soon.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- WHY AIN SHEBA --}}
<section class="ain-section bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Ain Sheba?</h2>
            <p class="text-muted">Built to make legal education accessible to every Bangladeshi citizen.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="ain-feature-card card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-award ain-feature-icon mb-3"></i>
                    <h5 class="fw-semibold mb-2">Certified Legal Experts</h5>
                    <p class="text-muted small mb-0">All programs are created and verified by licensed legal professionals.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ain-feature-card card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-journal-bookmark ain-feature-icon mb-3"></i>
                    <h5 class="fw-semibold mb-2">Structured Legal Training</h5>
                    <p class="text-muted small mb-0">Step-by-step modules with assessments to build real legal understanding.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ain-feature-card card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-patch-check ain-feature-icon mb-3"></i>
                    <h5 class="fw-semibold mb-2">Verified Literacy Certificates</h5>
                    <p class="text-muted small mb-0">Earn verifiable certificates upon completing legal literacy programs.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA BANNER --}}
<section class="ain-cta-banner">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to know your rights?</h2>
        <p class="lead mb-4 opacity-75">Join thousands of citizens taking control of their legal awareness.</p>
        <a href="{{ route('register') }}" class="btn ain-btn-accent btn-lg px-5">Register as Citizen</a>
    </div>
</section>

@endsection
