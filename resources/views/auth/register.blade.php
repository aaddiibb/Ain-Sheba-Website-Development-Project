@extends('layouts.app')

@section('title', 'Register — Ain Sheba')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 450px;">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4 text-center fw-bold">Create Account</h2>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block">I am a…</label>
                            @error('role')
                                <div class="text-danger small mb-2">{{ $message }}</div>
                            @enderror
                            <div class="row g-2">
                                <div class="col-6">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        id="role_citizen"
                                        name="role"
                                        value="citizen"
                                        autocomplete="off"
                                        {{ (old('role', request('as')) !== 'lawyer') ? 'checked' : '' }}
                                    >
                                    <label for="role_citizen" class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-1">
                                        <i class="bi bi-person fs-3"></i>
                                        <span>Citizen</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        id="role_lawyer"
                                        name="role"
                                        value="lawyer"
                                        autocomplete="off"
                                        {{ (old('role', request('as')) === 'lawyer') ? 'checked' : '' }}
                                    >
                                    <label for="role_lawyer" class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-1">
                                        <i class="bi bi-briefcase fs-3"></i>
                                        <span>Lawyer</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn ain-btn-accent">Register</button>
                        </div>

                        <p class="text-center mb-0">
                            Already have an account? <a href="{{ route('login') }}">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
