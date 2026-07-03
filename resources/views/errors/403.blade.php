@extends('layouts.app')

@section('title', '403 — Access Denied — Ain Sheba')

@section('content')
<div class="container py-5 text-center" style="min-height:60vh;display:flex;align-items:center;justify-content:center;">
    <div>
        <i class="bi bi-shield-x" style="font-size:5rem;color:#dc3545;opacity:.4"></i>
        <h1 class="fw-bold mt-3" style="font-size:4rem;color:#dc3545">403</h1>
        <h4 class="fw-semibold mb-2">Access Denied</h4>
        <p class="text-muted mb-4">You don't have permission to view this page.</p>
        <a href="{{ route('home') }}" class="btn ain-btn-accent px-4">
            <i class="bi bi-house me-2"></i>Back to Home
        </a>
    </div>
</div>
@endsection
