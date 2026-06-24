@extends('layouts.app')

@section('title', 'Certificate Verification — Ain Sheba')

@section('content')
<div class="container py-5" style="max-width:680px;">

    <div class="text-center mb-4">
        <i class="bi bi-balance-scale" style="font-size:2.5rem;color:var(--ain-primary)"></i>
        <h3 class="fw-bold mt-2">Certificate Verification</h3>
        <p class="text-muted">Ain Sheba — Legal Awareness Platform</p>
    </div>

    @if ($certificate)
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-5">
                <i class="bi bi-patch-check-fill text-success" style="font-size:4rem;"></i>
                <h4 class="fw-bold text-success mt-3">Certificate Verified</h4>
                <p class="text-muted mb-4">This is an authentic certificate issued by Ain Sheba.</p>

                <div class="table-responsive">
                    <table class="table table-borderless text-start">
                        <tbody>
                            <tr>
                                <td class="text-muted fw-semibold" style="width:140px;">Issued to</td>
                                <td class="fw-semibold">{{ $certificate->citizen->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Program</td>
                                <td>{{ $certificate->program->title }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Issued on</td>
                                <td>{{ $certificate->issued_at->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">Certificate Code</td>
                                <td><code>{{ $certificate->certificate_code }}</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('programs.show', $certificate->program->slug) }}" class="btn btn-outline-primary btn-sm mt-2">
                    <i class="bi bi-journal-text me-1"></i>View Program
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-5">
                <i class="bi bi-x-circle text-danger" style="font-size:4rem;"></i>
                <h4 class="fw-bold text-danger mt-3">Certificate Not Found</h4>
                <p class="text-muted mb-1">The code <code>{{ $code }}</code> does not match any certificate in our records.</p>
                <p class="text-muted small">Please double-check the code and try again. If you believe this is an error, contact support.</p>
            </div>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-house me-1"></i>Return to Ain Sheba
        </a>
    </div>

</div>
@endsection
