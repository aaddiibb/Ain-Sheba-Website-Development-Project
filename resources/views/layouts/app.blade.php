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

    <!-- Navigation placeholder (replaced Day 4) -->
    <nav class="navbar navbar-expand-lg ain-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">Ain Sheba</a>
        </div>
    </nav>

    <!-- Page content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer placeholder (replaced Day 4) -->
    <footer class="ain-footer py-4 mt-auto">
        <div class="container text-center">
            <small>&copy; {{ date('Y') }} Ain Sheba. All rights reserved.</small>
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
