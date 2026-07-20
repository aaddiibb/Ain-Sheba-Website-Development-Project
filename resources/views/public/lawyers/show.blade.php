@extends('layouts.app')

@section('title', $lawyer->name . ' — Legal Profile | Ain Sheba')

@section('content')

{{-- HERO SECTION --}}
<section class="ain-lawyer-hero">
    <div class="container">
        <div class="row align-items-center g-4">

            {{-- Left: Avatar + Name + Bio --}}
            <div class="col-md-8">
                <div class="d-flex align-items-start gap-4">
                    {{-- Avatar --}}
                    @if ($lawyer->profile_picture)
                        <img src="{{ asset($lawyer->profile_picture) }}"
                             class="ain-lawyer-avatar-lg flex-shrink-0"
                             alt="{{ $lawyer->name }}">
                    @else
                        <div class="ain-lawyer-avatar-placeholder-lg flex-shrink-0">
                            {{ strtoupper(substr($lawyer->name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h1 class="fw-bold mb-1">{{ $lawyer->name }}</h1>
                        <p class="mb-3 opacity-90">{{ $lawyer->bio ?? 'Legal expert dedicated to citizen empowerment.' }}</p>

                        {{-- Legal Area badges --}}
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($legalAreas as $area)
                                <span class="ain-availability-badge">{{ $area->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Fee badge --}}
                <div class="mt-4">
                    @if ($lawyer->consultation_fee && $lawyer->consultation_fee > 0)
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                            <i class="bi bi-currency-exchange me-1"></i>BDT {{ number_format($lawyer->consultation_fee, 0) }} per session
                        </span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-gift me-1"></i>Free Consultation
                        </span>
                    @endif
                </div>
            </div>

            {{-- Right: Stats --}}
            <div class="col-md-4">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="p-3 rounded" style="background:rgba(255,255,255,.15);">
                            <div class="fs-3 fw-bold">{{ $programs->count() }}</div>
                            <small class="opacity-75">Programs</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded" style="background:rgba(255,255,255,.15);">
                            <div class="fs-3 fw-bold">{{ $totalRegistrations }}</div>
                            <small class="opacity-75">Citizens Trained</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded" style="background:rgba(255,255,255,.15);">
                            <div class="fs-3 fw-bold">
                                @if ($avgRating)
                                    {{ number_format($avgRating, 1) }}
                                @else
                                    —
                                @endif
                            </div>
                            <small class="opacity-75">Avg Rating</small>
                        </div>
                    </div>
                </div>
                @if ($avgRating)
                    <div class="text-center mt-2 text-warning">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>

{{-- BODY --}}
<div class="container py-5">
    <div class="row g-5">

        {{-- LEFT COLUMN --}}
        <div class="col-md-8">

            {{-- Programs Section --}}
            <h4 class="fw-bold mb-4">Legal Programs by {{ $lawyer->name }}</h4>

            @if ($programs->count() > 0)
                <div class="row g-4 mb-5">
                    @foreach ($programs as $program)
                    <div class="col-md-6">
                        <div class="ain-program-card">
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
                                <div class="mb-2 d-flex flex-wrap gap-1">
                                    @if ($program->legalArea)
                                        <span class="badge ain-badge-area">{{ $program->legalArea->name }}</span>
                                    @endif
                                    <span class="badge ain-badge-level text-capitalize">{{ $program->level }}</span>
                                </div>
                            </div>
                            <div class="ain-program-footer">
                                <small class="text-muted"><i class="bi bi-people me-1"></i>{{ $program->registrations_count }} citizens</small>
                                <a href="{{ route('programs.show', $program->slug) }}" class="btn ain-btn-accent btn-sm">View Program</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 border rounded text-center text-muted mb-5">
                    <i class="bi bi-journal-x fs-3 d-block mb-2 opacity-50"></i>
                    No published programs yet.
                </div>
            @endif

            {{-- Availability Schedule --}}
            <h4 class="fw-bold mb-3"><i class="bi bi-calendar-week me-2 text-primary"></i>Availability Schedule</h4>
            @php
                $orderedDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                $availByDay  = $availability->groupBy('day_of_week');
            @endphp

            @if ($availability->count() > 0)
                {{-- Weekly overview grid --}}
                <div class="ain-week-grid mb-3">
                    @foreach ($orderedDays as $day)
                        <div class="ain-week-day {{ $availByDay->has($day) ? 'ain-week-day-available' : '' }}">
                            <span class="ain-week-day-name">{{ substr($day, 0, 3) }}</span>
                            <i class="bi {{ $availByDay->has($day) ? 'bi-check-circle-fill' : 'bi-dash-circle' }} ain-week-day-icon"></i>
                        </div>
                    @endforeach
                </div>

                {{-- Detailed time slots --}}
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($orderedDays as $day)
                        @if ($availByDay->has($day))
                            @foreach ($availByDay[$day] as $slot)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="font-size:.9rem;">
                                    <strong>{{ $day }}</strong>&nbsp;&nbsp;
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                </span>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-muted">No availability set by this lawyer yet.</p>
            @endif

        </div>{{-- /col-md-8 --}}

        {{-- RIGHT COLUMN — Sticky Booking Card --}}
        <div class="col-md-4">
            <div class="ain-booking-card sticky-top" style="top: 5rem;">
                <h5 class="fw-bold mb-3">Book a Consultation</h5>

                {{-- Fee --}}
                <div class="mb-3">
                    @if ($lawyer->consultation_fee && $lawyer->consultation_fee > 0)
                        <div class="fs-4 fw-bold text-primary">BDT {{ number_format($lawyer->consultation_fee, 0) }}</div>
                        <small class="text-muted">per session</small>
                    @else
                        <div class="fs-4 fw-bold text-success">Free</div>
                        <small class="text-muted">No charge for this consultation</small>
                    @endif
                </div>

                <hr>

                @auth
                    @if (auth()->user()->isCitizen())
                        <a href="{{ route('citizen.consultation.book', $lawyer->id) }}" class="btn ain-btn-accent w-100 mb-2">
                            <i class="bi bi-calendar-check me-1"></i>Book Now
                        </a>
                    @elseif (auth()->user()->isLawyer() || auth()->user()->isAdmin())
                        <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i>Consultation booking is for citizens only.</p>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login to Book
                    </a>
                @endauth

                @if ($lawyer->phone)
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-telephone me-1"></i>{{ $lawyer->phone }}
                    </p>
                @endif
            </div>
        </div>{{-- /col-md-4 --}}

    </div>
</div>

@endsection
