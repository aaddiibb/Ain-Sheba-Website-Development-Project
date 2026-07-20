@extends('admin._layout')

@section('title', 'Programs — Admin — Ain Sheba')

@section('admin-content')

<div class="mb-4">
    <h4 class="mb-1 fw-bold"><i class="bi bi-journal-text me-2 text-primary"></i>Programs</h4>
    <p class="text-muted mb-0 small">Review and manage all legal literacy programs on the platform.</p>
</div>

{{-- Status Tabs --}}
<ul class="nav nav-pills mb-4 gap-2">
    @foreach ([''=>'All','published'=>'Published','draft'=>'Draft','archived'=>'Archived'] as $val => $label)
    <li class="nav-item">
        <a class="nav-link {{ request('status') === $val ? 'active' : '' }}"
           href="{{ route('admin.programs.index', $val ? ['status' => $val] : []) }}">
            {{ $label }}
        </a>
    </li>
    @endforeach
</ul>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle small">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Lawyer</th>
                        <th>Legal Area</th>
                        <th>Citizens</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programs as $prog)
                    <tr>
                        <td class="fw-semibold">
                            <a href="{{ route('admin.programs.show', $prog->id) }}" class="text-decoration-none">
                                {{ Str::limit($prog->title, 40) }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $prog->lawyer->name ?? '—' }}</td>
                        <td>{{ $prog->legalArea->name ?? '—' }}</td>
                        <td>{{ $prog->registrations_count }}</td>
                        <td>
                            <form action="{{ route('admin.programs.status', $prog->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm"
                                        style="min-width:110px;" onchange="this.form.submit()">
                                    <option value="draft"     {{ $prog->status === 'draft'     ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $prog->status === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived"  {{ $prog->status === 'archived'  ? 'selected' : '' }}>Archived</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.programs.show', $prog->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.programs.destroy', $prog->id) }}" method="POST"
                                      onsubmit="return confirm('Delete \'{{ addslashes(Str::limit($prog->title,30)) }}\'?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No programs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $programs->links() }}
</div>

@endsection
