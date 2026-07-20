@extends('lawyer._layout')

@section('title', 'Lawyer Dashboard — Ain Sheba')

@section('lawyer-content')

<div class="ain-welcome-banner rounded mb-4 p-4">
    <h4 class="mb-1 fw-bold">Welcome, {{ auth()->user()->name }}!</h4>
    <p class="mb-0" style="opacity:.85">Manage your programs and consultations from here.</p>
</div>

{{-- Google Calendar Connection Card --}}
@if(auth()->user()->google_access_token)
    <div class="card ain-google-card ain-google-connected mb-4">
        <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span><i class="bi bi-google ain-google-icon me-2"></i>Google Calendar connected — confirmed consultations are added automatically.</span>
            <form method="POST" action="{{ route('lawyer.google.disconnect') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle"></i>Disconnect</button>
            </form>
        </div>
    </div>
@else
    <div class="card ain-google-card ain-google-disconnected mb-4">
        <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span><i class="bi bi-google ain-google-icon me-2"></i>Connect Google Calendar to auto-add confirmed consultations.</span>
            <a href="{{ route('lawyer.google.connect') }}" class="btn btn-primary btn-sm"><i class="bi bi-google"></i>Connect Google Calendar</a>
        </div>
    </div>
@endif

{{-- Stat Cards — Row 1 --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="ain-stat-dash ain-stat-primary">
            <div class="ain-stat-dash-label">Total Programs</div>
            <div class="ain-stat-dash-value">{{ $totalPrograms }}</div>
            <i class="bi bi-journal-text ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ain-stat-dash ain-stat-success">
            <div class="ain-stat-dash-label">Published</div>
            <div class="ain-stat-dash-value">{{ $publishedPrograms }}</div>
            <i class="bi bi-check-circle ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ain-stat-dash ain-stat-draft">
            <div class="ain-stat-dash-label">Draft</div>
            <div class="ain-stat-dash-value">{{ $draftPrograms }}</div>
            <i class="bi bi-pencil-square ain-stat-dash-icon"></i>
        </div>
    </div>
</div>

{{-- Stat Cards — Row 2 --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ain-stat-dash ain-stat-purple">
            <div class="ain-stat-dash-label">Citizens Registered</div>
            <div class="ain-stat-dash-value">{{ $totalRegistrations }}</div>
            <i class="bi bi-people ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ain-stat-dash ain-stat-primary">
            <div class="ain-stat-dash-label">Feedback Received</div>
            <div class="ain-stat-dash-value">{{ $totalFeedback }}</div>
            <i class="bi bi-chat-square-text ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ain-stat-dash ain-stat-accent">
            <div class="ain-stat-dash-label">Pending Consultations</div>
            <div class="ain-stat-dash-value">{{ $pendingConsultations }}</div>
            <i class="bi bi-calendar-check ain-stat-dash-icon"></i>
        </div>
    </div>
</div>

{{-- Recent Programs --}}
<div class="ain-section-header">
    <h5><i class="bi bi-journal-text"></i>Recent Programs</h5>
    <a href="{{ route('lawyer.programs.index') }}" class="btn btn-outline-primary btn-sm">
        View All <i class="bi bi-arrow-right"></i>
    </a>
</div>

@if ($programs->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-journal-x display-1 text-muted opacity-25 mb-3 d-block"></i>
        <h5 class="text-muted fw-semibold">No programs yet</h5>
        <p class="text-muted small mb-3">Programs you create will appear here.</p>
        <a href="{{ route('lawyer.programs.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i>Create Your First Program
        </a>
    </div>
@else
    <div class="ain-table-wrap">
        <table class="table ain-table mb-0">
            <thead>
                <tr>
                    <th style="width:60px"></th>
                    <th>Title</th>
                    <th>Legal Area</th>
                    <th>Status</th>
                    <th class="text-center">Registered</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programs as $program)
                <tr>
                    <td>
                        @if ($program->thumbnail)
                            <img src="/{{ $program->thumbnail }}" class="rounded" style="width:40px;height:40px;object-fit:cover" alt="">
                        @else
                            <div class="rounded d-flex align-items-center justify-content-center"
                                 style="width:40px;height:40px;background:var(--ain-primary)">
                                <i class="bi bi-book text-white small"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-semibold align-middle">{{ $program->title }}</td>
                    <td class="align-middle text-muted">{{ $program->legalArea->name ?? '—' }}</td>
                    <td class="align-middle">
                        <span class="ain-badge-{{ $program->status }}">{{ ucfirst($program->status) }}</span>
                    </td>
                    <td class="text-center align-middle">{{ $program->registrations_count }}</td>
                    <td class="text-end align-middle">
                        <a href="{{ route('lawyer.programs.show', $program->id) }}"
                           class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i>View</a>
                        <a href="{{ route('lawyer.programs.edit', $program->id) }}"
                           class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i>Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- Upcoming Google Calendar Events (AJAX — only shown when Calendar is connected) --}}
@if(auth()->user()->google_access_token)
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar3 me-2"></i>Upcoming Consultations <span class="text-muted fw-normal small">(from Google Calendar)</span></span>
        <button id="loadCalendarBtn" class="btn btn-sm btn-outline-primary">Load Events</button>
    </div>
    <div class="card-body" id="calendarEventsResult">
        <p class="text-muted small mb-0">Click "Load Events" to fetch from Google Calendar without reloading the page.</p>
    </div>
</div>
<script>
    // URL passed from Blade to the AJAX function (same pattern as Lab 12)
    const calendarUrl = "{{ route('lawyer.google.events') }}";
</script>
@endif

@endsection
