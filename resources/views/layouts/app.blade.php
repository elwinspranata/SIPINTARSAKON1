<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Si Pintar') }}</title>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .logo-text-si { color: #00D26A; font-weight: 900; }
        .logo-text-pintar { color: #2B5EA7; font-weight: 900; }
        
        .notification-badge-pulse {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid white;
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 77, 77, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(255, 77, 77, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 77, 77, 0); }
        }

        .motto-tag {
            background: var(--success-light);
            color: var(--success);
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 800;
            font-style: italic;
            border: 1px solid rgba(0, 210, 106, 0.1);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div style="padding: 0.5rem;">
                <a href="{{ route('dashboard') }}" class="sidebar-logo">
                    <div style="background: white; padding: 6px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid var(--border-light);">
                        <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 28px; width: 28px; object-fit: contain;">
                    </div>
                    <span style="font-size: 1.25rem; letter-spacing: -0.04em;">
                        <span class="logo-text-si">SI</span> <span class="logo-text-pintar">PINTAR</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-title">Menu Utama</div>
                
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-grid" size="18"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i data-lucide="users" size="18"></i>
                    <span>Data Siswa</span>
                </a>
                
                <a href="{{ route('records.create', ['type' => 'violation']) }}" class="nav-link {{ request()->is('records/create*') && request('type', 'violation') == 'violation' ? 'active' : '' }}">
                    <i data-lucide="stethoscope" size="18"></i>
                    <span>Rekam Penyakit</span>
                </a>
                
                <a href="{{ route('records.create', ['type' => 'achievement']) }}" class="nav-link {{ request()->is('records/create*') && request('type') == 'achievement' ? 'active' : '' }}">
                    <i data-lucide="pill" size="18"></i>
                    <span>Berikan Vitamin</span>
                </a>
                
                <a href="{{ route('records.index') }}" class="nav-link {{ request()->routeIs('records.index') ? 'active' : '' }}">
                    <i data-lucide="file-text" size="18"></i>
                    <span>Laporan</span>
                </a>

                @can('admin')
                <div class="nav-section-title">Admin</div>
                <a href="{{ route('admin.violation-types.index') }}" class="nav-link {{ request()->routeIs('admin.violation-types.*') ? 'active' : '' }}">
                    <i data-lucide="shield-alert" size="18"></i>
                    <span>Kelola Pelanggaran</span>
                </a>
                <a href="{{ route('admin.vitamin-types.index') }}" class="nav-link {{ request()->routeIs('admin.vitamin-types.*') ? 'active' : '' }}">
                    <i data-lucide="sparkles" size="18"></i>
                    <span>Kelola Vitamin</span>
                </a>
                @endcan
            </nav>

            <div class="user-profile-mini">
                <div class="user-avatar" style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2B5EA7&color=fff&bold=true&size=80'); background-size: cover;"></div>
                <div class="user-info-text">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role ?? 'Guru') }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="button" onclick="document.getElementById('logout-form').submit()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0.5rem; transition: color 0.2s;" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'">
                        <i data-lucide="log-out" size="18"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="main-header">
                <div>
                    <h1 class="page-title">@yield('header_title', 'Dashboard')</h1>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 4px;">
                        <span class="motto-tag">Sehat Karakternya, Pintar Orangnya</span>
                        <span style="color: var(--text-muted); opacity: 0.3;">|</span>
                        <span class="header-subtitle">@yield('header_subtitle', 'Sistem Pembinaan Integritas Terpadu')</span>
                    </div>
                </div>
                <div class="header-actions">
                    @yield('header_actions')
                    <div class="notification-trigger" style="position: relative;">
                        <i data-lucide="bell" size="18"></i>
                        <span class="notification-badge-pulse"></span>
                    </div>
                    <a href="{{ route('records.create') }}" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">
                        <i data-lucide="plus-circle" size="18"></i>
                        <span>Input Cepat</span>
                    </a>
                </div>
            </header>

            <div class="content-area">
                @if(session('success'))
                <div style="background: var(--success-light); color: var(--success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid rgba(0, 210, 106, 0.1); animation: fadeInUp 0.4s ease-out;">
                    <i data-lucide="check-circle" size="18"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif
                
                @isset($slot) {{ $slot }} @else @yield('content') @endisset
            </div>
        </main>
    </div>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('students.index') }}" class="mobile-nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i data-lucide="users"></i>
            <span>Siswa</span>
        </a>
        <a href="{{ route('records.create') }}" class="mobile-nav-link">
            <div style="background: var(--primary); color: white; width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-top: -32px; box-shadow: 0 8px 20px rgba(43, 94, 167, 0.4); border: 3px solid white;">
                <i data-lucide="plus"></i>
            </div>
        </a>
        <a href="{{ route('records.index') }}" class="mobile-nav-link {{ request()->routeIs('records.index') ? 'active' : '' }}">
            <i data-lucide="clipboard-list"></i>
            <span>Laporan</span>
        </a>
        <a href="#" onclick="document.getElementById('logout-form').submit()" class="mobile-nav-link">
            <i data-lucide="log-out"></i>
            <span>Keluar</span>
        </a>
    </nav>

    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>
