@extends('lawyer._layout')

@section('title', 'Manage Availability — Ain Sheba')

@section('lawyer-content')

<div class="mb-4">
    <h4 class="fw-bold mb-0">Manage Availability</h4>
    <p class="text-muted small">Set the days and times when citizens can book consultations with you.</p>
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

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">

    {{-- LEFT: Current Slots --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-week me-2 text-primary"></i>Current Availability</h6>
            </div>
            <div class="card-body">
                @if ($availability->count() > 0)
                    <div class="d-flex flex-column gap-2">
                        @foreach ($availability as $slot)
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-white">
                            <div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-2">{{ $slot->day_of_week }}</span>
                                <span class="text-muted small">
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                </span>
                            </div>
                            <form action="{{ route('lawyer.availability.destroy', $slot->id) }}" method="POST"
                                  onsubmit="return confirm('Remove this availability slot?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-50"></i>
                        No availability set yet. Add slots using the form on the right.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT: Add New Slot --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-success"></i>Add New Slot</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('lawyer.availability.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Day of Week <span class="text-danger">*</span></label>
                        <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                            <option value="">Select a day…</option>
                            @foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <option value="{{ $day }}" {{ old('day_of_week') === $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                        @error('day_of_week')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                        <input type="time"
                               name="start_time"
                               class="form-control @error('start_time') is-invalid @enderror"
                               value="{{ old('start_time') }}"
                               required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                        <input type="time"
                               name="end_time"
                               class="form-control @error('end_time') is-invalid @enderror"
                               value="{{ old('end_time') }}"
                               required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Must be after start time.</div>
                    </div>

                    <button type="submit" class="btn ain-btn-accent w-100">
                        <i class="bi bi-plus-circle me-1"></i>Add Slot
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
