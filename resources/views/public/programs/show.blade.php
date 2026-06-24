@extends('layouts.app')

@section('title', $program->title . ' — Ain Sheba')

@section('content')

<div class="container py-4">

    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('programs.index') }}">Programs</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($program->title, 50) }}</li>
        </ol>
    </nav>

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-md-8">
            <h2 class="fw-bold mb-1">{{ $program->title }}</h2>
            <p class="text-muted small mb-3">
                By <a href="{{ route('lawyers.show', $program->lawyer->id) }}" class="text-decoration-none fw-semibold">{{ $program->lawyer->name }}</a>
                @if ($program->legalArea)
                    &nbsp;·&nbsp;<span class="badge ain-badge-area">{{ $program->legalArea->name }}</span>
                @endif
                &nbsp;·&nbsp;<span class="badge ain-badge-level text-capitalize">{{ $program->level }}</span>
            </p>

            {{-- BOOTSTRAP TABS --}}
            <ul class="nav nav-tabs mb-4" id="programTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="curriculum-tab" data-bs-toggle="tab" data-bs-target="#curriculum" type="button" role="tab">
                        Curriculum <span class="badge bg-secondary ms-1">{{ $program->modules->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lawyer-tab" data-bs-toggle="tab" data-bs-target="#lawyer" type="button" role="tab">About the Lawyer</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="feedback-tab" data-bs-toggle="tab" data-bs-target="#feedback" type="button" role="tab">
                        Feedback <span class="badge bg-secondary ms-1">{{ $program->feedback->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="programTabsContent">

                {{-- OVERVIEW TAB --}}
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="mb-4">
                        {!! nl2br(e($program->description)) !!}
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="card border-0 bg-light p-3 text-center">
                                <small class="text-muted">Level</small>
                                <span class="fw-semibold text-capitalize mt-1">{{ $program->level }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card border-0 bg-light p-3 text-center">
                                <small class="text-muted">Language</small>
                                <span class="fw-semibold mt-1">{{ $program->language }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card border-0 bg-light p-3 text-center">
                                <small class="text-muted">Legal Area</small>
                                <span class="fw-semibold mt-1">{{ $program->legalArea->name ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CURRICULUM TAB --}}
                <div class="tab-pane fade" id="curriculum" role="tabpanel">
                    @forelse ($program->modules as $index => $module)
                        <div class="d-flex align-items-start gap-3 p-3 mb-2 border rounded bg-white">
                            <span class="badge bg-secondary rounded-pill mt-1" style="min-width:28px;">{{ $index + 1 }}</span>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="fw-semibold">{{ $module->title }}</span>
                                    @if ($module->is_free)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Preview</span>
                                    @else
                                        @if (!$isRegistered)
                                            <i class="bi bi-lock text-muted" title="Register to unlock"></i>
                                        @endif
                                    @endif
                                    @if ($module->duration_minutes > 0)
                                        <span class="badge bg-light text-muted border"><i class="bi bi-clock me-1"></i>{{ $module->duration_minutes }} min</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No modules added yet.</p>
                    @endforelse
                </div>

                {{-- ABOUT THE LAWYER TAB --}}
                <div class="tab-pane fade" id="lawyer" role="tabpanel">
                    <div class="d-flex gap-4 align-items-start p-3 border rounded bg-white">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            @if ($program->lawyer->profile_picture)
                                <img src="{{ asset('uploads/' . $program->lawyer->profile_picture) }}"
                                     class="rounded-circle" style="width:80px;height:80px;object-fit:cover;" alt="{{ $program->lawyer->name }}">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:80px;height:80px;font-size:2rem;">
                                    {{ strtoupper(substr($program->lawyer->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">{{ $program->lawyer->name }}</h5>
                            <p class="text-muted mb-3">{{ $program->lawyer->bio ?? 'No bio provided.' }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('lawyers.show', $program->lawyer->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-person me-1"></i>View Full Profile
                                </a>
                                @auth
                                    @if (auth()->user()->isCitizen())
                                        <a href="{{ route('citizen.consultation.book', $program->lawyer->id) }}" class="btn ain-btn-accent btn-sm">
                                            <i class="bi bi-calendar-check me-1"></i>Book a Consultation
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn ain-btn-accent btn-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>Login to Book Consultation
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FEEDBACK TAB --}}
                <div class="tab-pane fade" id="feedback" role="tabpanel">
                    @php
                        $avgRating = $program->feedback->avg('rating') ?? 0;
                        $myFeedback = auth()->check() ? $program->feedback->firstWhere('citizen_id', auth()->id()) : null;
                    @endphp

                    {{-- Submit / Edit Feedback Form (registered citizens only) --}}
                    @if (auth()->check() && auth()->user()->isCitizen() && $isRegistered)
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">
                                    {{ $myFeedback ? 'Update Your Feedback' : 'Share Your Feedback' }}
                                </h6>

                                @if (session('success'))
                                    <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger py-2 mb-3">{{ session('error') }}</div>
                                @endif

                                <form action="{{ route('citizen.feedback.store', $program->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Rating</label>
                                        <div id="star-picker" class="d-flex gap-1 mb-1" style="cursor:pointer;font-size:1.6rem;">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <i class="bi bi-star{{ $myFeedback && $s <= $myFeedback->rating ? '-fill' : '' }} ain-star text-warning"
                                                   data-value="{{ $s }}"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" id="rating-input" name="rating" value="{{ $myFeedback->rating ?? '' }}" required>
                                        @error('rating') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Comment <span class="text-muted">(optional)</span></label>
                                        <textarea name="comment" class="form-control" rows="3" maxlength="1000"
                                                  placeholder="Share your experience…">{{ old('comment', $myFeedback->comment ?? '') }}</textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-sm ain-btn-accent">
                                            <i class="bi bi-send me-1"></i>{{ $myFeedback ? 'Update' : 'Submit' }}
                                        </button>
                                        @if ($myFeedback)
                                            <form action="{{ route('citizen.feedback.destroy', $myFeedback->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Remove your feedback?')">
                                                    <i class="bi bi-trash me-1"></i>Remove
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    @elseif (!auth()->check() || !auth()->user()->isCitizen())
                        <div class="alert alert-light border mb-4">
                            <i class="bi bi-info-circle me-1"></i>
                            <a href="{{ route('login') }}">Log in</a> and register for this program to leave feedback.
                        </div>
                    @elseif (!$isRegistered)
                        <div class="alert alert-light border mb-4">
                            <i class="bi bi-info-circle me-1"></i>
                            Register for this program to leave feedback.
                        </div>
                    @endif

                    {{-- Average Rating Summary --}}
                    @if ($program->feedback->count() > 0)
                        <div class="mb-4 p-3 border rounded bg-light text-center">
                            <div class="display-5 fw-bold text-warning">{{ number_format($avgRating, 1) }}</div>
                            <div class="text-warning mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">Average from {{ $program->feedback->count() }} review{{ $program->feedback->count() !== 1 ? 's' : '' }}</small>
                        </div>
                        @foreach ($program->feedback as $review)
                            <div class="mb-3 p-3 border rounded bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold">{{ $review->citizen->name ?? 'Anonymous' }}</span>
                                    <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                </div>
                                <div class="text-warning mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                @if ($review->comment)
                                    <p class="text-muted small mb-0">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No feedback yet. Be the first to review this program.</p>
                    @endif
                </div>

            </div>{{-- /tab-content --}}
        </div>{{-- /col-md-8 --}}

        {{-- RIGHT SIDEBAR --}}
        <div class="col-md-4">
            <div class="ain-booking-card sticky-top" style="top: 5rem;">

                {{-- Thumbnail --}}
                @if ($program->thumbnail)
                    <img src="{{ asset('uploads/' . $program->thumbnail) }}"
                         class="img-fluid rounded mb-3" alt="{{ $program->title }}">
                @else
                    <div class="ain-program-thumb ain-program-thumb-placeholder rounded mb-3 d-flex align-items-center justify-content-center">
                        <span class="display-3 fw-bold text-white">{{ strtoupper(substr($program->title, 0, 1)) }}</span>
                    </div>
                @endif

                {{-- Meta Badges --}}
                <div class="d-flex flex-wrap gap-1 mb-3">
                    @if ($program->legalArea)
                        <span class="badge ain-badge-area">{{ $program->legalArea->name }}</span>
                    @endif
                    <span class="badge ain-badge-level text-capitalize">{{ $program->level }}</span>
                    <span class="badge bg-light text-muted border"><i class="bi bi-translate me-1"></i>{{ $program->language }}</span>
                </div>

                {{-- Stats --}}
                <div class="d-flex gap-3 mb-3 text-muted small">
                    <span><i class="bi bi-people me-1"></i>{{ $registeredCount }} enrolled</span>
                    @php $avgRatingSidebar = $program->feedback->avg('rating') ?? 0; @endphp
                    @if ($avgRatingSidebar > 0)
                        <span><i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($avgRatingSidebar, 1) }}</span>
                    @else
                        <span class="text-muted">No ratings yet</span>
                    @endif
                </div>

                {{-- FREE badge --}}
                <div class="mb-3">
                    <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-gift me-1"></i>Free Program</span>
                </div>

                {{-- CTA --}}
                @if ($isRegistered)
                    <div class="alert alert-success mb-3 py-2">
                        <i class="bi bi-check-circle-fill me-2"></i><strong>You are registered</strong>
                    </div>
                    @php $programNextModule = $nextModule ?? ($registration?->nextModule()); @endphp
                    @if ($programNextModule)
                        <a href="{{ route('citizen.module.show', [$program->slug, $programNextModule->id]) }}" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-play-circle me-1"></i>Continue Learning
                        </a>
                    @else
                        @php
                            $sidebarCert = $program->certificates()->where('citizen_id', auth()->id())->first();
                        @endphp
                        @if ($sidebarCert)
                            <a href="{{ route('citizen.certificate.show', $sidebarCert->certificate_code) }}" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-award me-1"></i>View Certificate
                            </a>
                        @else
                            <span class="btn btn-outline-success w-100 mb-2 disabled">
                                <i class="bi bi-award me-1"></i>Certificate Pending
                            </span>
                        @endif
                    @endif
                @elseif (auth()->check() && auth()->user()->isCitizen())
                    <form action="{{ route('citizen.register.program', $program->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn ain-btn-accent w-100 mb-2">
                            <i class="bi bi-person-plus me-1"></i>Register — Free
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login to Register
                    </a>
                @endif

                <p class="text-muted text-center small mt-2 mb-0">
                    <i class="bi bi-shield-check me-1"></i>All programs are free. No payment required.
                </p>

            </div>
        </div>{{-- /col-md-4 --}}

    </div>{{-- /row --}}
</div>

@endsection
