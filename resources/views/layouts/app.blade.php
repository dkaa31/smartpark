<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #1e3a8a; --sidebar-w: 220px; }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-w); height: 100vh; position: fixed;
            top: 0; left: 0; background: #fff;
            border-right: 1px solid #e5e7eb; z-index: 100;
            display: flex; flex-direction: column;
        }
        .sidebar-brand {
            padding: 20px 16px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .sidebar-brand .brand-title { font-size: 1rem; font-weight: 700; color: var(--primary); }
        .sidebar-brand .brand-sub   { font-size: 0.65rem; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section-label {
            font-size: 0.65rem; font-weight: 600; color: #9ca3af;
            text-transform: uppercase; letter-spacing: 1px;
            padding: 8px 16px 4px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 16px; color: #374151; text-decoration: none;
            font-size: 0.875rem; border-radius: 6px; margin: 1px 8px;
            transition: all .15s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: #eff6ff; color: var(--primary); font-weight: 600;
        }
        .sidebar-link i { font-size: 1rem; width: 18px; text-align: center; }
        .sidebar-footer { padding: 12px 8px; border-top: 1px solid #e5e7eb; }

        /* Topbar */
        #topbar {
            position: fixed; top: 0; left: var(--sidebar-w);
            right: 0; height: 56px; background: #fff;
            border-bottom: 1px solid #e5e7eb; z-index: 99;
            display: flex; align-items: center;
            padding: 0 24px; justify-content: space-between;
        }
        .breadcrumb-text { font-size: 0.875rem; color: #6b7280; }
        .breadcrumb-text span { color: #111827; font-weight: 600; }
        .user-info { display: flex; align-items: center; gap: 10px; font-size: 0.875rem; }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--primary); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.8rem;
        }

        /* Main content */
        #main { margin-left: var(--sidebar-w); padding-top: 56px; min-height: 100vh; }
        .page-content { padding: 28px 28px; }

        /* Cards */
        .card { border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
        .card-header { background: #fff; border-bottom: 1px solid #e5e7eb; font-weight: 600; padding: 14px 20px; }

        /* Buttons */
        .btn-primary { background: var(--primary); border-color: var(--primary); border-radius: 6px; }
        .btn-primary:hover { background: #1e40af; border-color: #1e40af; }
        .btn-sm { border-radius: 5px; }

        /* Table */
        .table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
        .table td { vertical-align: middle; font-size: 0.875rem; }

        /* Badge */
        .badge-aktif   { background: #dcfce7; color: #166534; }
        .badge-nonaktif{ background: #fee2e2; color: #991b1b; }
        .badge-masuk   { background: #dbeafe; color: #1e40af; }
        .badge-keluar  { background: #f3f4f6; color: #374151; }

        /* Alert */
        .alert { border-radius: 8px; font-size: 0.875rem; }

        /* Stat card */
        .stat-card { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; padding: 20px 24px; }
        .stat-label { font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: #111827; }
        .stat-icon  { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<div id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('image/logo.png') }}" alt="Logo" style="width:32px;height:32px;object-fit:contain;border-radius:8px;">
            <div>
                <div class="brand-title">SmartPark</div>
                <div class="brand-sub">Sistem Parkir</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @yield('sidebar-menu')
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start" style="color:#ef4444;">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- Topbar --}}
<div id="topbar">
    <div class="breadcrumb-text">
        @yield('breadcrumb', '<span>Dashboard</span>')
    </div>
    <div class="user-info">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}</div>
        <div>
            <div style="font-weight:600;color:#111827;line-height:1.2;">{{ auth()->user()->nama_lengkap }}</div>
            <div style="font-size:0.75rem;color:#6b7280;text-transform:capitalize;">{{ auth()->user()->role }}</div>
        </div>
    </div>
</div>

{{-- Main --}}
<div id="main">
    <div class="page-content">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
