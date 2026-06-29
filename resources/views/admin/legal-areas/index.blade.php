@extends('admin._layout')

@section('title', 'Legal Areas — Admin — Ain Sheba')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Legal Areas</h4>
    <a href="{{ route('admin.legal-areas.create') }}" class="btn ain-btn-accent">
        <i class="bi bi-plus-circle me-1"></i>Add Legal Area
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:60px;">Icon</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Programs</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($areas as $area)
                <tr>
                    <td class="text-center">
                        <i class="bi {{ $area->icon }}" style="font-size:1.4rem;color:var(--ain-primary)"></i>
                    </td>
                    <td class="fw-semibold">{{ $area->name }}</td>
                    <td><code class="small">{{ $area->slug }}</code></td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $area->programs_count }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.legal-areas.edit', $area->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.legal-areas.destroy', $area->id) }}" method="POST"
                                  onsubmit="return confirm('Delete \'{{ addslashes($area->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No legal areas yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
