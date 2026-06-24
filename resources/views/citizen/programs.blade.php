@extends('citizen._layout')

@section('title', 'My Programs — Ain Sheba')

@section('citizen-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">My Programs</h4>
</div>

{{-- Filter Tabs --}}
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}"
           href="{{ route('citizen.programs') }}">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'in-progress' ? 'active' : '' }}"
           href="{{ route('citizen.programs', ['status' => 'in-progress']) }}">In Progress</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'completed' ? 'active' : '' }}"
           href="{{ route('citizen.programs', ['status' => 'completed']) }}">Completed</a>
    </li>
</ul>

@if ($registrations->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-journal-x" style="font-size:3rem"></i>
        <p class="mt-3">No programs found for this filter.</p>
        <a href="{{ route('citizen.programs') }}" class="btn btn-sm btn-outline-secondary">View All</a>
    </div>
@else
    <div class="row g-3">
        @foreach ($registrations as $reg)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm ain-program-card">
                @if ($reg->program->thumbnail)
                    <img src="/{{ $reg->program->thumbnail }}" class="card-img-top ain-program-thumb" alt="{{ $reg->program->title }}">
                @else
                    <div class="ain-program-thumb-placeholder d-flex align-items-center justify-content-center">
                        <i class="bi bi-book text-white" style="font-size:2.5rem"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <span class="badge ain-badge-area mb-1">{{ $reg->program->legalArea->name ?? '—' }}</span>
                    <h6 class="card-title fw-semibold flex-grow-1">{{ $reg->program->title }}</h6>
                    <p class="card-text small text-muted">
                        <i class="bi bi-person-badge me-1"></i>{{ $reg->program->lawyer->name ?? '—' }}
                    </p>

                    @if ($reg->completed_at)
                        @php $cert = $reg->program->certificates->first(); @endphp
                        <div class="d-flex gap-2 flex-column">
                            <span class="badge bg-success py-2">
                                <i class="bi bi-check-circle me-1"></i>Completed
                            </span>
                            @if ($cert)
                                <a href="{{ route('citizen.certificate.show', $cert->certificate_code) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-award me-1"></i>View Certificate
                                </a>
                            @endif
                        </div>
                    @else
                        @php $nextModule = $reg->nextModule(); @endphp
                        @if ($nextModule)
                            <a href="{{ route('citizen.module.show', [$reg->program->slug, $nextModule->id]) }}" class="btn btn-sm ain-btn-accent">Continue Learning</a>
                        @else
                            <span class="btn btn-sm btn-outline-secondary disabled">All modules done</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $registrations->appends(request()->query())->links() }}
    </div>
@endif

@endsection
