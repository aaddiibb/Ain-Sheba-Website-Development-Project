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
        <span class="badge" style="background:var(--ain-accent);color:#fff;font-size:.7rem">Lawyer</span>
    </div>

    {{-- Navigation --}}
    <nav class="ain-sidebar-nav">
        <a href="{{ route('lawyer.dashboard') }}"
           class="ain-sidebar-link {{ request()->routeIs('lawyer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('lawyer.programs.index') }}"
           class="ain-sidebar-link {{ request()->routeIs('lawyer.programs.index') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> My Programs
        </a>

        <a href="{{ route('lawyer.programs.create') }}"
           class="ain-sidebar-link {{ request()->routeIs('lawyer.programs.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Create Program
        </a>

        <a href="#"
           class="ain-sidebar-link {{ request()->routeIs('lawyer.consultations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar2-week"></i> Consultations
        </a>

        <a href="#"
           class="ain-sidebar-link {{ request()->routeIs('lawyer.availability.*') ? 'active' : '' }}">
            <i class="bi bi-clock"></i> My Availability
        </a>

        <a href="{{ route('lawyer.profile') }}"
           class="ain-sidebar-link {{ request()->routeIs('lawyer.profile') ? 'active' : '' }}">
            <i class="bi bi-person"></i> Profile
        </a>

        <a href="{{ route('lawyer.password') }}"
           class="ain-sidebar-link {{ request()->routeIs('lawyer.password') ? 'active' : '' }}">
            <i class="bi bi-key"></i> Change Password
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="ain-sidebar-link ain-sidebar-logout w-100 text-start border-0 bg-transparent">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </nav>
</div>
