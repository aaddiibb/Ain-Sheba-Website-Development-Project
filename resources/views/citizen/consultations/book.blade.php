@extends('citizen._layout')

@section('title', 'Book a Consultation — ' . $lawyer->name . ' | Ain Sheba')

@section('citizen-content')

<div class="mb-4">
    <h4 class="fw-bold mb-0">Book a Consultation</h4>
    <p class="text-muted small">Select a date and time to request a session with {{ $lawyer->name }}.</p>
</div>

{{-- FLASH MESSAGES --}}
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

    {{-- LEFT: Lawyer Profile Card --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">

                {{-- Avatar --}}
                @if ($lawyer->profile_picture)
                    <img src="{{ asset('uploads/' . $lawyer->profile_picture) }}"
                         class="rounded-circle mb-3"
                         style="width:90px;height:90px;object-fit:cover;"
                         alt="{{ $lawyer->name }}">
                @else
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width:90px;height:90px;background:var(--ain-primary);font-size:2.2rem;">
                        {{ strtoupper(substr($lawyer->name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-1">{{ $lawyer->name }}</h5>
                <p class="text-muted small mb-3">{{ Str::limit($lawyer->bio ?? 'No bio provided.', 120) }}</p>

                {{-- Fee --}}
                @if ($lawyer->consultation_fee && $lawyer->consultation_fee > 0)
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle mb-3">
                        BDT {{ number_format($lawyer->consultation_fee, 0) }} / session
                    </span>
                @else
                    <span class="badge bg-success-subtle text-success border border-success-subtle mb-3">
                        Free Consultation
                    </span>
                @endif

                {{-- Availability display --}}
                <hr>
                <p class="fw-semibold small mb-2 text-start">Available On:</p>
                @php
                    $orderedDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                @endphp
                @if ($availability->count() > 0)
                    <div class="d-flex flex-column gap-1 text-start">
                        @foreach ($orderedDays as $day)
                            @if ($availability->has($day))
                                @foreach ($availability[$day] as $slot)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $day }}</span>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                        </small>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0">No schedule set yet.</p>
                @endif

            </div>
        </div>
    </div>

    {{-- RIGHT: Booking Form --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Select Date & Time</h5>

                <form action="{{ route('citizen.consultation.store', $lawyer->id) }}" method="POST">
                    @csrf

                    {{-- Date Picker --}}
                    <div class="mb-3">
                        <label for="booking-date" class="form-label fw-semibold">Preferred Date <span class="text-danger">*</span></label>
                        <input type="date"
                               id="booking-date"
                               name="booked_date"
                               class="form-control @error('booked_date') is-invalid @enderror"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               value="{{ old('booked_date') }}"
                               required>
                        @error('booked_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Time Slot Dropdown --}}
                    <div class="mb-3">
                        <label for="time-slot-select" class="form-label fw-semibold">Time Slot <span class="text-danger">*</span></label>
                        <select id="time-slot-select"
                                name="time_slot"
                                class="form-select @error('time_slot') is-invalid @enderror"
                                required>
                            <option value="">Select a date first</option>
                            @if (old('time_slot'))
                                <option value="{{ old('time_slot') }}" selected>{{ old('time_slot') }}</option>
                            @endif
                        </select>
                        @error('time_slot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Citizen Notes --}}
                    <div class="mb-3">
                        <label for="citizen-notes" class="form-label fw-semibold">Legal Issue (optional)</label>
                        <textarea id="citizen-notes"
                                  name="citizen_notes"
                                  class="form-control @error('citizen_notes') is-invalid @enderror"
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Briefly describe your legal issue — optional">{{ old('citizen_notes') }}</textarea>
                        @error('citizen_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fee Display --}}
                    <div class="alert alert-light border mb-3">
                        <i class="bi bi-info-circle me-1 text-primary"></i>
                        Consultation fee:
                        @if ($lawyer->consultation_fee && $lawyer->consultation_fee > 0)
                            <strong>BDT {{ number_format($lawyer->consultation_fee, 0) }}</strong>
                        @else
                            <strong class="text-success">Free</strong>
                        @endif
                    </div>

                    <button type="submit" class="btn ain-btn-accent w-100">
                        <i class="bi bi-calendar-check me-1"></i>Request Booking
                    </button>

                    <p class="text-muted text-center small mt-3 mb-0">
                        <i class="bi bi-shield-exclamation me-1"></i>This is a demo booking. No real payment is processed.
                    </p>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Pass data to JS --}}
<script>
    const lawyerAvailability = @json($availability);
    const bookedSlots = @json($bookedSlots);
</script>

@endsection
