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
        <span class="badge" style="background:var(--ain-primary);font-size:.7rem">Citizen</span>
    </div>

    {{-- Navigation --}}
    <nav class="ain-sidebar-nav">
        <a href="{{ route('citizen.dashboard') }}"
           class="ain-sidebar-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Dashboard
        </a>

        <a href="{{ route('citizen.programs') }}"
           class="ain-sidebar-link {{ request()->routeIs('citizen.programs') ? 'active' : '' }}">
            <i class="bi bi-journal-check"></i> My Programs
        </a>

        <a href="#"
           class="ain-sidebar-link {{ request()->routeIs('citizen.consultations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> My Consultations
        </a>

        <a href="{{ route('citizen.profile') }}"
           class="ain-sidebar-link {{ request()->routeIs('citizen.profile') ? 'active' : '' }}">
            <i class="bi bi-person"></i> Profile
        </a>

        <a href="{{ route('citizen.password') }}"
           class="ain-sidebar-link {{ request()->routeIs('citizen.password') ? 'active' : '' }}">
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
