@extends('lawyer._layout')

@section('title', 'Consultations — Ain Sheba')

@section('lawyer-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Consultations</h4>
</div>

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

{{-- TABS --}}
<ul class="nav nav-tabs mb-4" id="consultTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
            Pending
            @if ($pending->count() > 0)
                <span class="badge bg-warning text-dark ms-1">{{ $pending->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="confirmed-tab" data-bs-toggle="tab" data-bs-target="#confirmed" type="button" role="tab">
            Confirmed
            @if ($confirmed->count() > 0)
                <span class="badge bg-success ms-1">{{ $confirmed->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
            Past
        </button>
    </li>
</ul>

<div class="tab-content" id="consultTabsContent">

    {{-- PENDING TAB --}}
    <div class="tab-pane fade show active" id="pending" role="tabpanel">
        @forelse ($pending as $c)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row align-items-start g-3">
                    <div class="col-md-8">
                        <h6 class="fw-bold mb-1">{{ $c->citizen->name ?? '—' }}</h6>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($c->booked_date)->format('d M Y') }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-clock me-1"></i>{{ $c->time_slot }}
                        </div>
                        @if ($c->citizen_notes)
                            <p class="fst-italic text-muted small mb-0">
                                <i class="bi bi-chat-left-text me-1"></i>"{{ $c->citizen_notes }}"
                            </p>
                        @endif
                    </div>
                    <div class="col-md-4 d-flex gap-2 justify-content-md-end align-items-start flex-wrap">
                        {{-- Confirm --}}
                        <form action="{{ route('lawyer.consultations.status', $c->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle me-1"></i>Confirm
                            </button>
                        </form>
                        {{-- Decline --}}
                        <form action="{{ route('lawyer.consultations.status', $c->id) }}" method="POST"
                              onsubmit="return confirm('Decline this consultation?')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-x-circle me-1"></i>Decline
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-5 border rounded bg-white">
            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
            No pending consultation requests.
        </div>
        @endforelse
    </div>

    {{-- CONFIRMED TAB --}}
    <div class="tab-pane fade" id="confirmed" role="tabpanel">
        @forelse ($confirmed as $c)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row align-items-start g-3">
                    <div class="col-md-7">
                        <h6 class="fw-bold mb-1">{{ $c->citizen->name ?? '—' }}</h6>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($c->booked_date)->format('d M Y') }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-clock me-1"></i>{{ $c->time_slot }}
                        </div>
                        @if ($c->citizen_notes)
                            <p class="fst-italic text-muted small mb-0">"{{ $c->citizen_notes }}"</p>
                        @endif
                    </div>
                    <div class="col-md-5">
                        {{-- Mark Completed --}}
                        <form action="{{ route('lawyer.consultations.status', $c->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <textarea name="lawyer_response" class="form-control form-control-sm mb-2" rows="2"
                                      placeholder="Add a response or notes (optional)"></textarea>
                            <button type="submit" class="btn ain-btn-accent btn-sm w-100">
                                <i class="bi bi-check2-all me-1"></i>Mark Completed
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-5 border rounded bg-white">
            <i class="bi bi-calendar-check fs-2 d-block mb-2 opacity-50"></i>
            No confirmed consultations.
        </div>
        @endforelse
    </div>

    {{-- PAST TAB --}}
    <div class="tab-pane fade" id="past" role="tabpanel">
        @if ($past->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Citizen</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($past as $c)
                    <tr>
                        <td class="fw-semibold">{{ $c->citizen->name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($c->booked_date)->format('d M Y') }}<br>
                            <small class="text-muted">{{ $c->time_slot }}</small>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($c->status) {
                                    'completed' => 'bg-success-subtle text-success border border-success-subtle',
                                    'cancelled' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    default     => 'bg-secondary-subtle text-secondary border',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} text-capitalize">{{ $c->status }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ Str::limit($c->lawyer_response ?? '—', 80) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $past->links() }}
        </div>
        @else
        <div class="text-center text-muted py-5 border rounded bg-white">
            <p class="mb-0">No past consultations.</p>
        </div>
        @endif
    </div>

</div>{{-- /tab-content --}}

@endsection
