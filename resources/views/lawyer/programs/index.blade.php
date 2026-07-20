@extends('lawyer._layout')

@section('title', 'My Programs — Ain Sheba')

@section('lawyer-content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-1 fw-bold"><i class="bi bi-journal-text me-2 text-primary"></i>My Programs</h4>
        <p class="text-muted mb-0 small">Manage the legal literacy programs you've created.</p>
    </div>
    <a href="{{ route('lawyer.programs.create') }}" class="btn ain-btn-accent">
        <i class="bi bi-plus-lg"></i>New Program
    </a>
</div>

{{-- Filter Tabs --}}
<ul class="nav nav-pills mb-4 gap-2">
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
    <div class="text-center py-5">
        <i class="bi bi-journal-x display-1 text-muted opacity-25 mb-3 d-block"></i>
        <h5 class="text-muted fw-semibold">No programs found</h5>
        <p class="text-muted small mb-3">Programs you create will appear here.</p>
        <a href="{{ route('lawyer.programs.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i>Create One Now
        </a>
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
                    <td class="text-center align-middle">
                        <span class="badge ain-badge-confirmed"><i class="bi bi-people me-1"></i>{{ $program->registrations_count }}</span>
                    </td>
                    <td class="align-middle">
                        <span class="badge ain-badge-{{ $program->status }}">{{ ucfirst($program->status) }}</span>
                    </td>
                    <td class="text-end align-middle">
                        <a href="{{ route('lawyer.programs.show', $program->id) }}"
                           class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i>View</a>
                        <a href="{{ route('lawyer.programs.edit', $program->id) }}"
                           class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-pencil"></i>Edit</a>

                        <form method="POST" action="{{ route('lawyer.programs.destroy', $program->id) }}"
                              class="d-inline"
                              onsubmit="return confirm('Delete \'{{ addslashes($program->title) }}\'? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i>Delete</button>
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
