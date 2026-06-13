@extends('layouts.app')

@section('title', 'About — Ain Sheba')

@section('content')

<section class="ain-section">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold">About Ain Sheba</h1>
            <p class="lead text-muted">Legal literacy for every citizen of Bangladesh.</p>
        </div>

        <div class="row g-5 align-items-center mb-5">
            <div class="col-md-6">
                <h2 class="fw-bold mb-3"><i class="bi bi-bullseye me-2 text-warning"></i>Our Mission</h2>
                <p class="fs-5 text-muted">To empower every citizen with accessible legal knowledge and awareness of their fundamental rights.</p>
            </div>
            <div class="col-md-6">
                <h2 class="fw-bold mb-3"><i class="bi bi-eye me-2 text-warning"></i>Our Vision</h2>
                <p class="fs-5 text-muted">A Bangladesh where every citizen understands their legal rights and can seek justice confidently.</p>
            </div>
        </div>

        <h2 class="fw-bold text-center mb-4">Our Values</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="ain-feature-card card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-people ain-feature-icon mb-3"></i>
                    <h5 class="fw-semibold mb-2">Accessibility</h5>
                    <p class="text-muted small mb-0">Legal knowledge should be available to everyone, regardless of background or income.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ain-feature-card card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-check-circle ain-feature-icon mb-3"></i>
                    <h5 class="fw-semibold mb-2">Accuracy</h5>
                    <p class="text-muted small mb-0">Every piece of legal information is verified by certified legal professionals.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ain-feature-card card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-lightning ain-feature-icon mb-3"></i>
                    <h5 class="fw-semibold mb-2">Empowerment</h5>
                    <p class="text-muted small mb-0">We believe informed citizens are empowered citizens who can advocate for their rights.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
