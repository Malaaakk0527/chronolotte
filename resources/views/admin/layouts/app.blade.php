<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration') — Chronolette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 99; display: none; }
        .sidebar-overlay.open { display: block; }
        .sidebar { min-height: 100vh; background: #131921; width: 240px; position: fixed; top: 0; left: 0; padding: 0; z-index: 100; }
        .sidebar .brand { color: #fff; font-size: 20px; font-weight: 700; padding: 20px; border-bottom: 1px solid #232f3e; display: flex; align-items: center; gap: 10px; }
        .sidebar a.nav-link { color: #adb5bd; padding: 12px 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .sidebar a.nav-link:hover, .sidebar a.nav-link.active { color: #fff; background: #232f3e; }
        .sidebar .nav { padding: 10px 0; }
        .main { margin-left: 240px; padding: 24px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 14px 24px; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .btn-burger { display: none; background: #131921; color: #fff; border: none; border-radius: 6px; padding: 8px 12px; font-size: 20px; line-height: 1; cursor: pointer; }
        .card { border: none; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card-title { font-weight: 700; }
        .stat-card .icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff; }
        .table th { background: #f8f9fa; font-size: 13px; text-transform: uppercase; letter-spacing: .4px; }
        .table td { vertical-align: middle; font-size: 14px; }
        .product-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .badge-status { font-size: 12px; }
        .pagination { --bs-pagination-padding-y: .2rem; --bs-pagination-padding-x: .55rem; --bs-pagination-font-size: .85rem; --bs-pagination-border-radius: .3rem; --bs-pagination-color: #131921; --bs-pagination-active-bg: #131921; --bs-pagination-active-border-color: #131921; }
        .pagination .page-link svg { width: 12px; height: 12px; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s ease; box-shadow: 4px 0 20px rgba(0,0,0,.3); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .btn-burger { display: inline-block; }
        }
        @media (max-width: 640px) {
            .main { padding: 14px; }
            .topbar { flex-wrap: wrap; gap: 10px; }
            .table-responsive { -webkit-overflow-scrolling: touch; }
        }
    </style>
    @stack('head')
</head>
<body>
<div class="sidebar-overlay" id="adminSidebarOverlay"></div>
<aside class="sidebar" id="adminSidebar">
    <div class="brand">
        <i class="bi bi-shop"></i><span>Chronolette Admin</span>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam"></i><span>Produits</span></a>
        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="bi bi-bag-check"></i><span>Commandes</span></a>
        <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.show') }}"><i class="bi bi-person-gear"></i><span>Mon profil</span></a>
        <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-eye"></i><span>Voir le site</span></a>
    </nav>
</aside>

<div class="main">
    <div class="topbar">
        <div>
            <button class="btn-burger" id="adminNavToggle" aria-label="Menu"><i class="bi bi-list"></i></button>
            <strong>Bonjour, {{ auth('admin')->user()->name }}</strong>
            <div style="font-size:12px;color:#888;">Panneau d'administration</div>
        </div>
        <div>
            <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm me-2"><i class="bi bi-eye"></i> Site</a>
            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn btn-dark btn-sm"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        var toggle = document.getElementById('adminNavToggle');
        var sidebar = document.getElementById('adminSidebar');
        var overlay = document.getElementById('adminSidebarOverlay');
        if (!toggle || !sidebar || !overlay) return;

        function open() { sidebar.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow = 'hidden'; }
        function close() { sidebar.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow = ''; }

        toggle.addEventListener('click', open);
        overlay.addEventListener('click', close);
        sidebar.querySelectorAll('a.nav-link').forEach(function (a) { a.addEventListener('click', close); });
    })();
</script>
@stack('scripts')
</body>
</html>
