@extends('layouts.app')

@section('title', 'Login — Ain Sheba')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 450px;">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4 text-center fw-bold"><i class="bi bi-box-arrow-in-right me-2 text-primary"></i>Login</h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="bi bi-envelope me-1 text-primary"></i>Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                autofocus
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label"><i class="bi bi-key me-1 text-primary"></i>Password</label>
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

                        <div class="mb-3 form-check">
                            <input type="checkbox" id="remember" name="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Remember me</label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn ain-btn-accent"><i class="bi bi-box-arrow-in-right"></i>Login</button>
                        </div>

                        <p class="text-center mb-0">
                            Don't have an account? <a href="{{ route('register') }}">Register here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
