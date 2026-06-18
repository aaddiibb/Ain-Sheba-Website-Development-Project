@extends('citizen._layout')

@section('title', 'Dashboard — Ain Sheba')

@section('citizen-content')

{{-- Welcome Banner --}}
<div class="ain-welcome-banner rounded mb-4 p-4">
    <h4 class="mb-1 fw-bold">Welcome, {{ auth()->user()->name }}!</h4>
    <p class="mb-0" style="opacity:.85">Know your rights, protect your future.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-primary">
            <div class="ain-stat-dash-label">Registered</div>
            <div class="ain-stat-dash-value">{{ $totalEnrolled }}</div>
            <i class="bi bi-journal-text ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-accent">
            <div class="ain-stat-dash-label">In Progress</div>
            <div class="ain-stat-dash-value">{{ $inProgress }}</div>
            <i class="bi bi-hourglass-split ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-success">
            <div class="ain-stat-dash-label">Completed</div>
            <div class="ain-stat-dash-value">{{ $completed }}</div>
            <i class="bi bi-check-circle ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-purple">
            <div class="ain-stat-dash-label">Certificates</div>
            <div class="ain-stat-dash-value">{{ $totalCertificates }}</div>
            <i class="bi bi-award ain-stat-dash-icon"></i>
        </div>
    </div>
</div>

{{-- Continue Learning --}}
@if ($continuePrograms->isNotEmpty())
<div class="mb-4">
    <h5 class="fw-semibold mb-3">Continue Learning</h5>
    <div class="row g-3">
        @foreach ($continuePrograms as $reg)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm ain-program-card">
                @if ($reg->program->thumbnail)
                    <img src="/{{ $reg->program->thumbnail }}" class="card-img-top ain-program-thumb" alt="{{ $reg->program->title }}">
                @else
                    <div class="ain-program-thumb-placeholder d-flex align-items-center justify-content-center">
                        <i class="bi bi-book text-white" style="font-size:2.5rem"></i>
                    </div>
                @endif
                <div class="card-body">
                    <span class="badge ain-badge-area mb-1">{{ $reg->program->legalArea->name ?? '—' }}</span>
                    <h6 class="card-title fw-semibold">{{ $reg->program->title }}</h6>
                    <p class="card-text small text-muted mb-3">
                        <i class="bi bi-person-badge me-1"></i>{{ $reg->program->lawyer->name ?? '—' }}
                    </p>
                    @php $nextModule = $reg->nextModule(); @endphp
                    @if ($nextModule)
                        <a href="{{ route('citizen.module.show', [$reg->program->slug, $nextModule->id]) }}" class="btn btn-sm ain-btn-accent w-100">Continue</a>
                    @else
                        <a href="#" class="btn btn-sm btn-outline-success w-100">View Certificate</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Upcoming Consultations --}}
@if ($upcomingConsultations->isNotEmpty())
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-semibold mb-0">Upcoming Consultations</h5>
        <a href="{{ route('citizen.consultations.index') }}" class="small text-decoration-none" style="color:var(--ain-primary)">
            View All <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card border-0 shadow-sm">
        <ul class="list-group list-group-flush">
            @foreach ($upcomingConsultations as $c)
            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="fw-semibold">{{ $c->lawyer->name ?? '—' }}</div>
                    <small class="text-muted">
                        <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($c->booked_date)->format('d M Y') }}
                        &nbsp;|&nbsp;{{ $c->time_slot }}
                    </small>
                </div>
                @if ($c->status === 'confirmed')
                    <span class="badge bg-success">Confirmed</span>
                @else
                    <span class="badge bg-warning text-dark">Pending</span>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

{{-- Recommended Programs --}}
@if ($recommended->isNotEmpty())
<div class="mb-2">
    <h5 class="fw-semibold mb-3">Recommended Programs</h5>
    <div class="row g-3">
        @foreach ($recommended as $program)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm ain-program-card">
                @if ($program->thumbnail)
                    <img src="/{{ $program->thumbnail }}" class="card-img-top ain-program-thumb" alt="{{ $program->title }}">
                @else
                    <div class="ain-program-thumb-placeholder d-flex align-items-center justify-content-center">
                        <i class="bi bi-book text-white" style="font-size:2.5rem"></i>
                    </div>
                @endif
                <div class="card-body">
                    <span class="badge ain-badge-area mb-1">{{ $program->legalArea->name ?? '—' }}</span>
                    <h6 class="card-title fw-semibold">{{ $program->title }}</h6>
                    <p class="card-text small text-muted mb-3">
                        <i class="bi bi-person-badge me-1"></i>{{ $program->lawyer->name ?? '—' }}
                    </p>
                    <a href="{{ route('programs.show', $program->slug) }}" class="btn btn-sm btn-outline-primary w-100">View Program</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if ($recommended->isEmpty() && $continuePrograms->isEmpty() && $upcomingConsultations->isEmpty())
<div class="text-center py-5 text-muted">
    <i class="bi bi-journals" style="font-size:3rem"></i>
        <p class="mt-3">Your dashboard is empty. <a href="{{ route('programs.index') }}">Browse programs</a> to get started.</p>
</div>
@endif

@endsection
