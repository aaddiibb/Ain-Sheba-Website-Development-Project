<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ain Sheba')</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom styles -->
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg ain-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="bi bi-balance-scale fs-4"></i> Ain Sheba
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-3 text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/programs') }}">Programs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/lawyers') }}">Lawyers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">Login</a>
                        <a href="{{ route('register') }}" class="btn ain-btn-accent btn-sm px-3">Register</a>
                    @else
                        <!-- Notification Bell -->
                        <a href="#" class="btn btn-link text-white position-relative p-1" title="Notifications">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">
                                0
                            </span>
                        </a>

                        <!-- User Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                {{ Str::limit(auth()->user()->name, 18) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    @if (auth()->user()->role === 'citizen')
                                        <a class="dropdown-item" href="{{ route('citizen.dashboard') }}">
                                            <i class="bi bi-person me-2"></i>My Dashboard
                                        </a>
                                    @elseif (auth()->user()->role === 'lawyer')
                                        <a class="dropdown-item" href="{{ route('lawyer.dashboard') }}">
                                            <i class="bi bi-person me-2"></i>My Dashboard
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-person me-2"></i>My Dashboard
                                        </a>
                                    @endif
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Page content -->
    <main>
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="ain-footer pt-5 pb-3 mt-auto">
        <div class="container">
            <div class="row g-4 mb-4">
                <!-- Column 1: Brand & Mission -->
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-balance-scale fs-4 text-warning"></i>
                        <span class="fw-bold fs-5 text-white">Ain Sheba</span>
                    </div>
                    <p class="ain-footer-text small">To empower every citizen with accessible legal knowledge and awareness of their fundamental rights.</p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-md-4">
                    <h6 class="text-white fw-semibold mb-3 text-uppercase letter-spacing-1">Quick Links</h6>
                    <ul class="list-unstyled ain-footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ url('/programs') }}">Programs</a></li>
                        <li><a href="{{ url('/lawyers') }}">Lawyers</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="col-md-4">
                    <h6 class="text-white fw-semibold mb-3 text-uppercase">Contact</h6>
                    <ul class="list-unstyled ain-footer-text small">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2 text-warning"></i>Dhaka, Bangladesh</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2 text-warning"></i>support@ainsheba.bd</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2 text-warning"></i>+880 1700-000000</li>
                        <li><i class="bi bi-clock me-2 text-warning"></i>Sun–Thu: 9 AM – 5 PM</li>
                    </ul>
                </div>
            </div>

            <hr class="ain-footer-divider">
            <div class="text-center ain-footer-text small pt-2">
                &copy; {{ date('Y') }} Ain Sheba. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SortableJS 1.15.2 (used Day 7 drag-to-reorder) -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <!-- App JS -->
    <script src="/js/app.js"></script>
    <!-- Page-specific scripts -->
    @stack('scripts')

</body>
</html>
