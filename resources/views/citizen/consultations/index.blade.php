@extends('citizen._layout')

@section('title', 'My Consultations — Ain Sheba')

@section('citizen-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">My Consultations</h4>
    <a href="{{ route('lawyers.index') }}" class="btn ain-btn-accent btn-sm">
        <i class="bi bi-calendar-plus me-1"></i>Book New Consultation
    </a>
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

{{-- UPCOMING BOOKINGS --}}
<h5 class="fw-semibold mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Upcoming Bookings</h5>

@if ($upcoming->count() > 0)
    <div class="row g-3 mb-5">
        @foreach ($upcoming as $c)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold mb-0">
                                <a href="{{ route('lawyers.show', $c->lawyer->id) }}" class="text-decoration-none">
                                    {{ $c->lawyer->name ?? '—' }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($c->booked_date)->format('d M Y') }}
                                &nbsp;·&nbsp;
                                <i class="bi bi-clock me-1"></i>{{ $c->time_slot }}
                            </small>
                        </div>
                        @php
                            $badgeClass = match($c->status) {
                                'pending'   => 'bg-warning-subtle text-warning border border-warning-subtle',
                                'confirmed' => 'bg-success-subtle text-success border border-success-subtle',
                                default     => 'bg-secondary-subtle text-secondary border',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} text-capitalize">{{ $c->status }}</span>
                    </div>

                    @if ($c->citizen_notes)
                        <p class="text-muted small mb-2 fst-italic">"{{ Str::limit($c->citizen_notes, 100) }}"</p>
                    @endif

                    @if ($c->fee > 0)
                        <small class="text-muted"><i class="bi bi-currency-exchange me-1"></i>BDT {{ number_format($c->fee, 0) }}</small>
                    @else
                        <small class="text-success"><i class="bi bi-gift me-1"></i>Free</small>
                    @endif

                    @if ($c->status === 'pending')
                        <div class="mt-3">
                            <form action="{{ route('citizen.consultation.cancel', $c->id) }}" method="POST"
                                  onsubmit="return confirm('Cancel this consultation?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center text-muted py-4 mb-5 border rounded bg-white">
        <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-50"></i>
        No upcoming consultations.
        <div class="mt-2">
            <a href="{{ route('lawyers.index') }}" class="btn ain-btn-accent btn-sm">Find a Lawyer</a>
        </div>
    </div>
@endif

{{-- PAST CONSULTATIONS --}}
<h5 class="fw-semibold mb-3"><i class="bi bi-clock-history me-2 text-muted"></i>Past Consultations</h5>

@if ($past->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Lawyer</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                    <th>Fee</th>
                    <th>Lawyer's Response</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($past as $c)
                <tr>
                    <td>
                        <a href="{{ route('lawyers.show', $c->lawyer->id) }}" class="text-decoration-none fw-semibold">
                            {{ $c->lawyer->name ?? '—' }}
                        </a>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($c->booked_date)->format('d M Y') }}</td>
                    <td>{{ $c->time_slot }}</td>
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
                        @if ($c->fee > 0)
                            BDT {{ number_format($c->fee, 0) }}
                        @else
                            <span class="text-success">Free</span>
                        @endif
                    </td>
                    <td>
                        @if ($c->lawyer_response)
                            <span class="text-muted small" title="{{ $c->lawyer_response }}">
                                {{ Str::limit($c->lawyer_response, 60) }}
                            </span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
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
    <div class="text-center text-muted py-4 border rounded bg-white">
        <p class="mb-0">No past consultations.</p>
    </div>
@endif

@endsection
