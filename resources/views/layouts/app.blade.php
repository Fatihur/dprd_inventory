<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Inventaris') - DPRD Kabupaten Sumbawa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #3c4b64;
            --sidebar-hover: #321fdb;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.1);
            text-align: center;
        }
        .sidebar-header h4 {
            color: #fff;
            margin: 0;
            font-size: 1rem;
        }
        .sidebar-header small {
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
        }
        .sidebar-menu {
            padding: 15px 0;
        }
        .sidebar-menu .nav-label {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 15px 20px 5px;
            letter-spacing: 1px;
        }
        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: var(--sidebar-hover);
        }
        .sidebar-menu .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .top-navbar {
            background: #fff;
            padding: 15px 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-wrapper {
            padding: 25px;
        }
        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
        }
        .stat-card .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-radius: 8px;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            font-weight: 600;
        }
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge {
            font-weight: 500;
            padding: 5px 10px;
        }
        .btn {
            border-radius: 6px;
            font-weight: 500;
        }
        .form-control, .form-select {
            border-radius: 6px;
            border-color: #dee2e6;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--sidebar-hover);
            box-shadow: 0 0 0 0.2rem rgba(50, 31, 219, 0.15);
        }
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
            color: #333;
        }
        .hamburger-btn:hover {
            color: var(--sidebar-hover);
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .sidebar-overlay.show {
            display: block;
        }
        @media (max-width: 991px) {
            .hamburger-btn {
                display: block;
            }
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="bi bi-building"></i> DPRD Sumbawa</h4>
            <small>Sistem Inventaris Barang</small>
        </div>
        <nav class="sidebar-menu">
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            @if(auth()->user()->isAdmin())
            <div class="nav-label">Master Data</div>
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Pengguna
            </a>
            <a href="{{ route('barang.index') }}" class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Data Barang
            </a>
            @endif

            @if(auth()->user()->isOperator())
            <div class="nav-label">Transaksi</div>
            <a href="{{ route('peminjaman.index') }}" class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-up-right-square"></i> Peminjaman
            </a>
            <a href="{{ route('pengembalian.index') }}" class="nav-link {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-down-left-square"></i> Pengembalian
            </a>
            @endif

            @if(auth()->user()->isKabagUmum())
            <div class="nav-label">Persetujuan</div>
            <a href="{{ route('persetujuan.index') }}" class="nav-link {{ request()->routeIs('persetujuan.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i> Persetujuan Peminjaman
            </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isKabagUmum())
            <div class="nav-label">Laporan</div>
            <a href="{{ route('laporan.peminjaman') }}" class="nav-link {{ request()->routeIs('laporan.peminjaman*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Laporan Peminjaman
            </a>
            <a href="{{ route('laporan.pengembalian') }}" class="nav-link {{ request()->routeIs('laporan.pengembalian*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-check"></i> Laporan Pengembalian
            </a>
            <a href="{{ route('laporan.stok') }}" class="nav-link {{ request()->routeIs('laporan.stok*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Laporan Stok
            </a>
            @endif
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="hamburger-btn" id="hamburgerBtn" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifikasi Dropdown -->
                <div class="dropdown me-5">
                    <a href="#" class="position-relative text-dark" data-bs-toggle="dropdown" id="notifDropdown">
                        <i class="bi bi-bell" style="font-size: 1.3rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="notifBadge">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow" style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Notifikasi</span>
                            <a href="{{ route('notifikasi.index') }}" class="text-decoration-none small">Lihat Semua</a>
                        </div>
                        <div id="notifList">
                            <div class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">{{ auth()->user()->name }}</div>
                            <small class="text-muted">{{ auth()->user()->role_name }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{ route('notifikasi.index') }}" class="dropdown-item">
                                <i class="bi bi-bell me-2"></i> Notifikasi
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
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
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        hamburgerBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Load notifikasi
        function loadNotifikasi() {
            fetch('{{ route("notifikasi.unread") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notifBadge');
                    const list = document.getElementById('notifList');
                    
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }

                    if (data.notifikasi.length > 0) {
                        list.innerHTML = data.notifikasi.map(n => `
                            <a href="/notifikasi/${n.id}/baca" class="dropdown-item py-2 border-bottom" onclick="event.preventDefault(); document.getElementById('notif-form-${n.id}').submit();">
                                <div class="d-flex align-items-start">
                                    <i class="bi ${n.icon} me-2 mt-1"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small">${n.judul}</div>
                                        <div class="text-muted small text-truncate" style="max-width: 250px;">${n.pesan}</div>
                                        <div class="text-muted smaller">${n.waktu}</div>
                                    </div>
                                </div>
                            </a>
                            <form id="notif-form-${n.id}" action="/notifikasi/${n.id}/baca" method="POST" class="d-none">
                                @csrf
                            </form>
                        `).join('');
                    } else {
                        list.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-bell-slash"></i><p class="mb-0 small">Tidak ada notifikasi baru</p></div>';
                    }
                });
        }

        // Load saat halaman ready dan setiap 30 detik
        document.addEventListener('DOMContentLoaded', loadNotifikasi);
        setInterval(loadNotifikasi, 30000);

        // Load saat dropdown dibuka
        document.getElementById('notifDropdown').addEventListener('click', loadNotifikasi);
    </script>
    @stack('scripts')
</body>
</html>
