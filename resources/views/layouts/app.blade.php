<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Si Pintar') }}</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>        .logo-text-si { color: #22c55e; font-weight: 900; }
        .logo-text-pintar { color: #1B6B3A; font-weight: 900; }
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
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem;">
                <a href="{{ route('dashboard') }}" class="sidebar-logo" style="margin-bottom: 0;">
                    <div style="background: white; padding: 6px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid var(--border-light);">
                        <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 28px; width: 28px; object-fit: contain;">
                    </div>
                    <span style="font-size: 1.25rem; letter-spacing: -0.04em;">
                        <span class="logo-text-si">SI</span> <span class="logo-text-pintar">PINTAR</span>
                    </span>
                </a>
                <button type="button" class="sidebar-toggle" onclick="toggleSidebar()" style="background: var(--primary-light); border: none; width: 36px; height: 36px; border-radius: 10px; align-items: center; justify-content: center; cursor: pointer; color: var(--primary); flex-shrink: 0;">
                    <i data-lucide="x" size="18"></i>
                </button>
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

                <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                    <i data-lucide="school" size="18"></i>
                    <span>Data Kelas</span>
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
                    <span>Riwayat Catatan</span>
                </a>

                <a href="{{ route('records.recap') }}" class="nav-link {{ request()->routeIs('records.recap') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3" size="18"></i>
                    <span>Rekap Kolektif</span>
                </a>

                @can('admin')
                <div class="nav-section-title">Admin</div>
                @php $pendingUserCount = App\Models\User::where('role', 'guru')->where('is_approved', false)->count(); @endphp
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i data-lucide="user-cog" size="18"></i>
                    <span>Kelola Guru</span>
                    @if($pendingUserCount > 0)
                    <span style="background: var(--secondary); color: white; font-size: 0.625rem; font-weight: 800; padding: 2px 7px; border-radius: 99px; margin-left: auto;">{{ $pendingUserCount }}</span>
                    @endif
                </a>
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
                <div class="user-avatar" style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1B6B3A&color=fff&bold=true&size=80'); background-size: cover;"></div>
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

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="main-header">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button type="button" class="mobile-menu-toggle btn btn-outline" onclick="toggleSidebar()">
                        <i data-lucide="menu"></i>
                    </button>
                    <div>
                        <h1 class="page-title">@yield('header_title', 'Dashboard')</h1>
                        <div class="header-meta" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 4px;">
                            <span class="motto-tag">Sehat Karakternya, Pintar Orangnya</span>
                            <span class="header-separator" style="color: var(--text-muted); opacity: 0.3;">|</span>
                            <span class="header-subtitle">@yield('header_subtitle', 'Sistem Pembinaan Integritas Terpadu')</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    @yield('header_actions')

                    @unless(request()->routeIs('classes.*'))
                    <a href="{{ route('records.create') }}" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">
                        <i data-lucide="plus-circle" size="18"></i>
                        <span>Input Cepat</span>
                    </a>
                    @endunless
                </div>
            </header>

            <div class="content-area">
                @if(session('success'))
                <div style="background: var(--success-light); color: var(--success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid rgba(0, 210, 106, 0.1); animation: fadeInUp 0.4s ease-out;">
                    <i data-lucide="check-circle" size="18"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div style="background: #fef2f2; color: #ef4444; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid rgba(239, 68, 68, 0.1); animation: fadeInUp 0.4s ease-out;">
                    <i data-lucide="alert-circle" size="18"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif
                
                @isset($slot) {{ $slot }} @else @yield('content') @endisset
            </div>
        </main>
    </div>


    <script>
        lucide.createIcons();
        
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            
            // Toggle icon
            const menuBtn = document.querySelector('.mobile-menu-toggle i');
            if (sidebar.classList.contains('open')) {
                menuBtn.setAttribute('data-lucide', 'x');
            } else {
                menuBtn.setAttribute('data-lucide', 'menu');
            }
            lucide.createIcons();
        }
    </script>
    @stack('scripts')
</body>
</html>
