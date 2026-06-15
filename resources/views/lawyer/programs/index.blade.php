@extends('lawyer._layout')

@section('title', 'My Programs — Ain Sheba')

@section('lawyer-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">My Programs</h4>
    <a href="{{ route('lawyer.programs.create') }}" class="btn ain-btn-accent">
        <i class="bi bi-plus-lg me-1"></i>New Program
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter Tabs --}}
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}"
           href="{{ route('lawyer.programs.index') }}">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'published' ? 'active' : '' }}"
           href="{{ route('lawyer.programs.index', ['status' => 'published']) }}">Published</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'draft' ? 'active' : '' }}"
           href="{{ route('lawyer.programs.index', ['status' => 'draft']) }}">Draft</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'archived' ? 'active' : '' }}"
           href="{{ route('lawyer.programs.index', ['status' => 'archived']) }}">Archived</a>
    </li>
</ul>

@if ($programs->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-journal-x" style="font-size:3rem"></i>
        <p class="mt-3">No programs found. <a href="{{ route('lawyer.programs.create') }}">Create one now.</a></p>
    </div>
@else
    <div class="ain-table-wrap">
        <table class="table ain-table mb-0">
            <thead>
                <tr>
                    <th style="width:60px"></th>
                    <th>Title</th>
                    <th>Legal Area</th>
                    <th class="text-center">Citizens</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programs as $program)
                <tr>
                    <td>
                        @if ($program->thumbnail)
                            <img src="/{{ $program->thumbnail }}" class="rounded"
                                 style="width:40px;height:40px;object-fit:cover" alt="">
                        @else
                            <div class="rounded d-flex align-items-center justify-content-center"
                                 style="width:40px;height:40px;background:var(--ain-primary)">
                                <i class="bi bi-book text-white small"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-semibold align-middle">{{ $program->title }}</td>
                    <td class="align-middle text-muted small">{{ $program->legalArea->name ?? '—' }}</td>
                    <td class="text-center align-middle">{{ $program->registrations_count }}</td>
                    <td class="align-middle">
                        <span class="ain-badge-{{ $program->status }}">{{ ucfirst($program->status) }}</span>
                    </td>
                    <td class="text-end align-middle">
                        <a href="{{ route('lawyer.programs.show', $program->id) }}"
                           class="btn btn-sm btn-outline-primary me-1">View</a>
                        <a href="{{ route('lawyer.programs.edit', $program->id) }}"
                           class="btn btn-sm btn-outline-secondary me-1">Edit</a>

                        <form method="POST" action="{{ route('lawyer.programs.destroy', $program->id) }}"
                              class="d-inline"
                              onsubmit="return confirm('Delete \'{{ addslashes($program->title) }}\'? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $programs->appends(request()->query())->links() }}
    </div>
@endif

@endsection
