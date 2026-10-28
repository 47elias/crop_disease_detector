<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#176b43">
    <title>@yield('title', 'Dashboard') · Maize Health</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.min.css') }}">
    <style>
        :root { --lte-sidebar-bg: #173b2c; --bs-primary: #28724b; --bs-primary-rgb: 40, 114, 75; }
        body { min-height: 100vh; }
        .app-sidebar { background-color: #173b2c !important; }
        .sidebar-brand { color: #f5faf5 !important; }
        .sidebar-menu .nav-link.active { background-color: #28724b !important; color: #fff !important; }
        .brand-mark { display: inline-grid; width: 2rem; height: 2rem; place-items: center; margin-right: .55rem; border-radius: .55rem; color: #173b2c; background: #c8e66c; font-weight: 800; }
        .content-wrapper { background: #f4f6f3; }
        .card { border: 1px solid #e1e8e1; box-shadow: 0 2px 9px rgba(23, 59, 44, .04); }
        .stat-value { font-size: 1.7rem; font-weight: 700; }
        .table td, .table th { vertical-align: middle; }
        .empty-state { padding: 2rem 1rem; color: #69776c; text-align: center; }
    </style>
    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-mini bg-body-tertiary">
<div class="app-wrapper">
    @include('layouts.partials.topbar')
    @include('layouts.partials.sidebar')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h1 class="mb-1 fs-4">@yield('page-title', 'Dashboard')</h1>
                    <p class="mb-0 text-secondary">@yield('page-description', 'Maize disease surveillance and field operations')</p>
                </div>
                <span class="badge text-bg-success">Maize programme</span>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible" role="status">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                @endif
                @yield('content')
            </div>
        </div>
    </main>
    @include('layouts.partials.footer')
</div>
<script src="{{ asset('vendor/adminlte/js/adminlte.min.js') }}" defer></script>
@stack('scripts')
</body>
</html>
