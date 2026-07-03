@extends('layouts.app')

@section('title', '404 — Page Not Found — Ain Sheba')

@section('content')
<div class="container py-5 text-center" style="min-height:60vh;display:flex;align-items:center;justify-content:center;">
    <div>
        <i class="bi bi-search" style="font-size:5rem;color:var(--ain-primary);opacity:.3"></i>
        <h1 class="fw-bold mt-3" style="font-size:4rem;color:var(--ain-primary)">404</h1>
        <h4 class="fw-semibold mb-2">Page Not Found</h4>
        <p class="text-muted mb-4">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ route('home') }}" class="btn ain-btn-accent px-4">
            <i class="bi bi-house me-2"></i>Back to Home
        </a>
    </div>
</div>
@endsection
