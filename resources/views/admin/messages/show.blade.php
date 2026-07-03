@extends('admin._layout')

@section('title', 'Message from ' . $message->name . ' — Admin — Ain Sheba')

@section('admin-content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">Messages</a></li>
        <li class="breadcrumb-item active">From {{ $message->name }}</li>
    </ol>
</nav>

<div class="card border-0 shadow-sm" style="max-width:700px;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-envelope me-2"></i>Message Detail</span>
        <small class="text-muted">{{ $message->created_at->format('d F Y, H:i') }}</small>
    </div>
    <div class="card-body">
        <table class="table table-borderless mb-3">
            <tbody>
                <tr>
                    <th style="width:100px;" class="text-muted fw-normal">From</th>
                    <td class="fw-semibold">{{ $message->name }}</td>
                </tr>
                <tr>
                    <th class="text-muted fw-normal">Email</th>
                    <td>
                        <a href="mailto:{{ $message->email }}" class="text-decoration-none">{{ $message->email }}</a>
                    </td>
                </tr>
                <tr>
                    <th class="text-muted fw-normal">Subject</th>
                    <td class="fw-semibold">{{ $message->subject }}</td>
                </tr>
            </tbody>
        </table>

        <hr>

        <div class="p-3 bg-light rounded" style="white-space:pre-wrap;line-height:1.8;">{{ $message->message }}</div>
    </div>
    <div class="card-footer bg-white">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Messages
        </a>
        <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}"
           class="btn btn-sm ain-btn-accent ms-2">
            <i class="bi bi-reply me-1"></i>Reply via Email
        </a>
    </div>
</div>

@endsection
