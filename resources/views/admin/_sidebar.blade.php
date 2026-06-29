<div class="ain-citizen-sidebar">
    {{-- Avatar & Identity --}}
    <div class="text-center mb-4">
        @if ($user->profile_picture)
            <img src="/{{ $user->profile_picture }}" alt="{{ $user->name }}" class="ain-avatar mb-2">
        @else
            <div class="ain-avatar-placeholder mb-2 mx-auto justify-content-center">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
        @endif
        <div class="fw-semibold" style="color: var(--ain-dark)">{{ $user->name }}</div>
        <span class="badge" style="background:var(--ain-primary);color:#fff;font-size:.7rem">Admin</span>
    </div>

    {{-- Navigation --}}
    <nav class="ain-sidebar-nav">
        <a href="{{ route('admin.dashboard') }}"
           class="ain-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="ain-sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>

        <a href="{{ route('admin.programs.index') }}"
           class="ain-sidebar-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Programs
        </a>

        <a href="{{ route('admin.consultations.index') }}"
           class="ain-sidebar-link {{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar2-week"></i> Consultations
        </a>

        <a href="{{ route('admin.legal-areas.index') }}"
           class="ain-sidebar-link {{ request()->routeIs('admin.legal-areas.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Legal Areas
        </a>

        <a href="#"
           class="ain-sidebar-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i> Messages <span class="badge bg-secondary ms-1" style="font-size:.65rem">Day 13</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="ain-sidebar-link ain-sidebar-logout w-100 text-start border-0 bg-transparent">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </nav>
</div>
