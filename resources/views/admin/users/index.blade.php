@extends('admin._layout')

@section('title', 'Users — Admin — Ain Sheba')

@section('admin-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Users</h4>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Search by name or email…">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="citizen" {{ request('role') === 'citizen' ? 'selected' : '' }}>Citizens</option>
                    <option value="lawyer"  {{ request('role') === 'lawyer'  ? 'selected' : '' }}>Lawyers</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Role Tabs --}}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ !request('role') ? 'active' : '' }}"
           href="{{ route('admin.users.index', array_merge(request()->except('role','page'), [])) }}">
            All
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('role') === 'citizen' ? 'active' : '' }}"
           href="{{ route('admin.users.index', array_merge(request()->except('role','page'), ['role' => 'citizen'])) }}">
            Citizens
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('role') === 'lawyer' ? 'active' : '' }}"
           href="{{ route('admin.users.index', array_merge(request()->except('role','page'), ['role' => 'lawyer'])) }}">
            Lawyers
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Active</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                    <tr>
                        <td class="fw-semibold">{{ $u->name }}</td>
                        <td class="text-muted small">{{ $u->email }}</td>
                        <td>
                            <span class="badge {{ $u->isLawyer() ? 'bg-warning text-dark' : 'bg-primary' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>
                            @if ($u->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.users.show', $u->id) }}"
                                   class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            title="{{ $u->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi bi-{{ $u->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ addslashes($u->name) }}? This cannot be undone.')">
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
                        <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>

@endsection
