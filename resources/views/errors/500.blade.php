@extends('layouts.app')

@section('title', '500 — Server Error — Ain Sheba')

@section('content')
<div class="container py-5 text-center" style="min-height:60vh;display:flex;align-items:center;justify-content:center;">
    <div>
        <i class="bi bi-exclamation-triangle" style="font-size:5rem;color:var(--ain-accent);opacity:.5"></i>
        <h1 class="fw-bold mt-3" style="font-size:4rem;color:var(--ain-primary)">500</h1>
        <h4 class="fw-semibold mb-2">Something Went Wrong</h4>
        <p class="text-muted mb-4">An unexpected error occurred. Please try again later.</p>
        <a href="{{ route('home') }}" class="btn ain-btn-accent px-4">
            <i class="bi bi-house me-2"></i>Back to Home
        </a>
    </div>
</div>
@endsection
