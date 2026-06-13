@extends('citizen._layout')

@section('title', 'Change Password — Ain Sheba')

@section('citizen-content')

<h4 class="fw-bold mb-4">Change Password</h4>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm p-4" style="max-width:480px">
    <form method="POST" action="{{ route('citizen.password.update') }}">
        @csrf
        @method('PATCH')

        {{-- Current Password --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
            <input type="password" name="current_password"
                   class="form-control @error('current_password') is-invalid @enderror"
                   autocomplete="current-password" required>
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- New Password --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
            <input type="password" name="new_password"
                   class="form-control @error('new_password') is-invalid @enderror"
                   autocomplete="new-password" required minlength="8">
            @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm New Password --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
            <input type="password" name="new_password_confirmation"
                   class="form-control @error('new_password_confirmation') is-invalid @enderror"
                   autocomplete="new-password" required>
            @error('new_password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn ain-btn-accent px-4">
            <i class="bi bi-shield-lock me-1"></i>Update Password
        </button>
    </form>
</div>

@endsection
