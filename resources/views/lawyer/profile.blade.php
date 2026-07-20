@extends('lawyer._layout')

@section('title', 'My Profile — Ain Sheba')

@section('lawyer-content')

<h4 class="fw-bold mb-4">My Profile</h4>

<div class="card border-0 shadow-sm p-4">
    <form method="POST" action="{{ route('lawyer.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Profile Picture --}}
        <div class="mb-4 text-center">
            @if ($user->profile_picture)
                <img id="profile-pic-preview" src="/{{ $user->profile_picture }}"
                     class="ain-avatar mb-3" style="width:90px;height:90px" alt="Profile Picture">
            @else
                <img id="profile-pic-preview"
                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=c9a84c&color=fff&size=90"
                     class="ain-avatar mb-3" style="width:90px;height:90px" alt="Avatar">
            @endif
            <div>
                <label for="profile-pic-input" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-camera me-1"></i>Change Photo
                </label>
                <input type="file" id="profile-pic-input" name="profile_picture" class="d-none" accept="image/*">
            </div>
            @error('profile_picture')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Name --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email (readonly) --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
        </div>

        {{-- Phone --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Phone</label>
            <input type="text" name="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $user->phone) }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Consultation Fee --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Consultation Fee (BDT)</label>
            <input type="number" name="consultation_fee" min="0" step="0.01"
                   class="form-control @error('consultation_fee') is-invalid @enderror"
                   value="{{ old('consultation_fee', $user->consultation_fee) }}">
            <div class="form-text">Fee per session in BDT — set 0 for free consultation.</div>
            @error('consultation_fee')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Bio --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Bio</label>
            <div class="form-text mb-1">This appears on your public lawyer profile — make it informative.</div>
            <textarea name="bio" rows="5"
                      class="form-control @error('bio') is-invalid @enderror"
                      maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn ain-btn-accent px-4">
            <i class="bi bi-save me-1"></i>Save Changes
        </button>
    </form>
</div>

@push('scripts')
<script>
    initImagePreview('profile-pic-input', 'profile-pic-preview');
</script>
@endpush

@endsection
