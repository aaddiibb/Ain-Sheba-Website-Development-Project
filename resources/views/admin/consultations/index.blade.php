@extends('admin._layout')

@section('title', 'Consultations — Admin — Ain Sheba')

@section('admin-content')

<div class="mb-4">
    <h4 class="mb-1 fw-bold"><i class="bi bi-calendar2-week me-2 text-primary"></i>Consultations</h4>
    <p class="text-muted mb-0 small">Browse all consultation bookings across the platform.</p>
</div>

{{-- Status Filter Tabs --}}
<ul class="nav nav-pills mb-4 gap-2">
    @foreach (['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
    <li class="nav-item">
        <a class="nav-link {{ request('status') === $val ? 'active' : '' }}"
           href="{{ route('admin.consultations.index', $val ? ['status' => $val] : []) }}">
            {{ $label }}
        </a>
    </li>
    @endforeach
</ul>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle small">
                <thead>
                    <tr>
                        <th>Citizen</th>
                        <th>Lawyer</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Status</th>
                        <th>Fee</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($consultations as $c)
                    <tr>
                        <td>
                            <a href="{{ route('admin.users.show', $c->citizen_id) }}" class="text-decoration-none fw-semibold">
                                {{ $c->citizen->name ?? '—' }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $c->lawyer_id) }}" class="text-decoration-none">
                                {{ $c->lawyer->name ?? '—' }}
                            </a>
                        </td>
                        <td class="text-muted">{{ \Carbon\Carbon::parse($c->booked_date)->format('d M Y') }}</td>
                        <td class="text-muted">{{ $c->time_slot }}</td>
                        <td>
                            @php
                                $badgeMap = [
                                    'pending'   => 'bg-warning text-dark',
                                    'confirmed' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    'cancelled' => 'bg-secondary',
                                ];
                            @endphp
                            <span class="badge {{ $badgeMap[$c->status] ?? 'bg-light text-dark' }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($c->fee)
                                <span class="text-muted">৳{{ number_format($c->fee, 0) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-muted">
                            {{ $c->citizen_notes ? Str::limit($c->citizen_notes, 60) : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No consultations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $consultations->links() }}
</div>

@endsection
