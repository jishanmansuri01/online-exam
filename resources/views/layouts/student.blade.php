<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            background: #1a6b3c;
            width: 250px;
            position: fixed;
            top: 0; left: 0;
            padding-top: 20px;
            z-index: 100;
        }
        .sidebar .brand {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 700;
            padding: 10px 20px 20px;
            border-bottom: 1px solid #2d8c54;
            display: block;
        }
        .sidebar .nav-link {
            color: #b7d9c5;
            padding: 12px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: #2d8c54;
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .topbar {
            background: #fff;
            padding: 15px 25px;
            border-bottom: 1px solid #e0e0e0;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .btn { border-radius: 8px; }
        .table th { font-weight: 600; font-size: 0.875rem; color: #555; }
    </style>
    @stack('styles')
</head>
<body>

<div class="sidebar">
    <a href="{{ route('student.dashboard') }}" class="brand">
        <i class="bi bi-mortarboard-fill me-2"></i>{{ config('app.name') }}
    </a>
    <nav class="mt-3">
        <a href="{{ route('student.dashboard') }}"
           class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('student.exams.index') }}"
           class="nav-link {{ request()->routeIs('student.exams.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Exams
        </a>
        <a href="{{ route('student.results.index') }}"
           class="nav-link {{ request()->routeIs('student.results.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> My Results
        </a>
    </nav>
</div>

<div class="main-content">
    <div class="topbar">
        <h5 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h5>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
@stack('scripts')
</body>
</html>