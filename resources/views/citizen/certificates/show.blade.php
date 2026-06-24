@extends('layouts.app')

@section('title', 'Certificate — ' . $certificate->program->title . ' — Ain Sheba')

@push('styles')
<style>
    @media print {
        nav, footer, .d-print-none { display: none !important; }
        body { background: white !important; }
        .ain-certificate-wrapper { box-shadow: none !important; }
    }
</style>
@endpush

@section('content')
<div class="container py-5">

    <div class="ain-certificate-wrapper shadow-lg">
        <div class="ain-certificate-inner">

            {{-- Wordmark --}}
            <div class="mb-3">
                <i class="bi bi-balance-scale" style="font-size:2.5rem;color:var(--ain-primary)"></i>
                <div class="fw-bold mt-1" style="font-size:1.1rem;letter-spacing:.15em;color:var(--ain-primary);text-transform:uppercase">Ain Sheba</div>
                <div class="text-muted small">Legal Awareness Platform</div>
            </div>

            <hr style="border-color:var(--ain-accent);border-width:2px;width:120px;margin:1rem auto;">

            {{-- Title --}}
            <h2 style="letter-spacing:.2em;text-transform:uppercase;color:var(--ain-primary);font-weight:700;font-size:1.3rem;" class="mb-4">
                Legal Literacy Certificate
            </h2>

            <p class="text-muted mb-1" style="font-size:1rem;">This certifies that</p>

            {{-- Citizen Name --}}
            <div class="ain-certificate-name my-3">{{ $certificate->citizen->name }}</div>

            <p class="text-muted mb-2" style="font-size:1rem;">has successfully completed the legal awareness program</p>

            {{-- Program Title --}}
            <div style="font-size:1.5rem;font-style:italic;color:var(--ain-dark);font-weight:600;" class="mb-4">
                "{{ $certificate->program->title }}"
            </div>

            <hr style="border-color:var(--ain-accent);border-width:1px;width:200px;margin:1rem auto;">

            {{-- Issue Date --}}
            <p class="mb-1 text-muted small">Issued on</p>
            <p class="fw-semibold mb-4">{{ $certificate->issued_at->format('d F Y') }}</p>

            {{-- Signature --}}
            <div class="mt-2 pt-3" style="border-top:1px solid #dee2e6;display:inline-block;padding:0 3rem;">
                <div class="fw-semibold" style="color:var(--ain-primary)">Authorized by Ain Sheba</div>
                <div class="text-muted small">Legal Awareness Platform</div>
            </div>

            {{-- Certificate Code --}}
            <div class="mt-4 pt-3">
                <code class="text-muted small d-block">Certificate Code: {{ $certificate->certificate_code }}</code>
                <small class="text-muted">Verify at: <span class="font-monospace">/verify/{{ $certificate->certificate_code }}</span></small>
            </div>

        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="d-print-none text-center mt-4 d-flex gap-3 justify-content-center flex-wrap">
        <a href="{{ route('citizen.certificate.download', $certificate->certificate_code) }}" class="btn ain-btn-accent">
            <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
        </a>
        <a href="{{ route('certificate.verify', $certificate->certificate_code) }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-patch-check me-1"></i>Verify Online
        </a>
        <a href="{{ route('citizen.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>

</div>
@endsection
