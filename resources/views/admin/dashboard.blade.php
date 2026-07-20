@extends('admin._layout')

@section('title', 'Admin Dashboard — Ain Sheba')

@section('admin-content')

<div class="ain-welcome-banner rounded mb-4 p-4">
    <h4 class="mb-1 fw-bold">Admin Dashboard</h4>
    <p class="mb-0" style="opacity:.85">Platform overview and management tools.</p>
</div>

{{-- Row 1: Users & Programs --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-primary">
            <div class="ain-stat-dash-label">Citizens</div>
            <div class="ain-stat-dash-value">{{ $totalCitizens }}</div>
            <i class="bi bi-people ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-accent">
            <div class="ain-stat-dash-label">Lawyers</div>
            <div class="ain-stat-dash-value">{{ $totalLawyers }}</div>
            <i class="bi bi-person-badge ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-success">
            <div class="ain-stat-dash-label">Programs</div>
            <div class="ain-stat-dash-value">{{ $totalPrograms }}</div>
            <i class="bi bi-journal-text ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-purple">
            <div class="ain-stat-dash-label">Published</div>
            <div class="ain-stat-dash-value">{{ $publishedPrograms }}</div>
            <i class="bi bi-broadcast ain-stat-dash-icon"></i>
        </div>
    </div>
</div>

{{-- Row 2: Activity --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-primary">
            <div class="ain-stat-dash-label">Registrations</div>
            <div class="ain-stat-dash-value">{{ $totalRegistrations }}</div>
            <i class="bi bi-person-check ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-accent">
            <div class="ain-stat-dash-label">Certificates</div>
            <div class="ain-stat-dash-value">{{ $totalCertificates }}</div>
            <i class="bi bi-award ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash ain-stat-success">
            <div class="ain-stat-dash-label">Consultations</div>
            <div class="ain-stat-dash-value">{{ $totalConsultations }}</div>
            <i class="bi bi-calendar2-check ain-stat-dash-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ain-stat-dash" style="background:linear-gradient(135deg,#dc3545 0%,#c82333 100%);color:#fff;position:relative;overflow:hidden;">
            <div class="ain-stat-dash-label">Pending Consult.</div>
            <div class="ain-stat-dash-value">{{ $pendingConsultations }}</div>
            <i class="bi bi-hourglass-split ain-stat-dash-icon"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Users --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-plus me-2 text-primary"></i>Recent Users</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 small">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers as $u)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="text-decoration-none fw-semibold">
                                        {{ $u->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge {{ $u->isLawyer() ? 'ain-badge-pending' : 'ain-badge-confirmed' }}">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $u->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-muted text-center py-3">No users yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Programs --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-journal-plus me-2 text-success"></i>Recent Programs</span>
                <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 small">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Lawyer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPrograms as $prog)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.programs.show', $prog->id) }}" class="text-decoration-none fw-semibold">
                                        {{ Str::limit($prog->title, 30) }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $prog->lawyer->name ?? '—' }}</td>
                                <td>
                                    @if ($prog->status === 'published')
                                        <span class="badge ain-badge-published">Published</span>
                                    @elseif ($prog->status === 'draft')
                                        <span class="badge ain-badge-draft">Draft</span>
                                    @else
                                        <span class="badge ain-badge-archived">Archived</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-muted text-center py-3">No programs yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
