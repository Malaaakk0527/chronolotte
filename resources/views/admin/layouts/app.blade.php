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
        .sidebar { min-height: 100vh; background: #131921; width: 240px; position: fixed; top: 0; left: 0; padding: 0; z-index: 100; }
        .sidebar .brand { color: #fff; font-size: 20px; font-weight: 700; padding: 20px; border-bottom: 1px solid #232f3e; display: flex; align-items: center; gap: 10px; }
        .sidebar a.nav-link { color: #adb5bd; padding: 12px 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .sidebar a.nav-link:hover, .sidebar a.nav-link.active { color: #fff; background: #232f3e; }
        .sidebar .nav { padding: 10px 0; }
        .main { margin-left: 240px; padding: 24px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 14px 24px; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card { border: none; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card-title { font-weight: 700; }
        .stat-card .icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff; }
        .table th { background: #f8f9fa; font-size: 13px; text-transform: uppercase; letter-spacing: .4px; }
        .table td { vertical-align: middle; font-size: 14px; }
        .product-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .badge-status { font-size: 12px; }
        .pagination { --bs-pagination-padding-y: .2rem; --bs-pagination-padding-x: .55rem; --bs-pagination-font-size: .85rem; --bs-pagination-border-radius: .3rem; --bs-pagination-color: #131921; --bs-pagination-active-bg: #131921; --bs-pagination-active-border-color: #131921; }
        .pagination .page-link svg { width: 12px; height: 12px; }
        @media (max-width: 900px) { .sidebar { width: 60px; } .sidebar .brand span, .sidebar a.nav-link span { display: none; } .main { margin-left: 60px; } }
        @media (max-width: 640px) {
            .sidebar { position: static; width: 100%; min-height: auto; }
            .sidebar .brand span, .sidebar a.nav-link span { display: inline; }
            .sidebar .nav { flex-direction: row; flex-wrap: wrap; padding: 8px; gap: 4px; }
            .sidebar a.nav-link { padding: 8px 12px; font-size: 13px; }
            .main { margin-left: 0; padding: 14px; }
            .topbar { flex-wrap: wrap; gap: 10px; }
            .table-responsive { -webkit-overflow-scrolling: touch; }
        }
    </style>
    @stack('head')
</head>
<body>
<aside class="sidebar">
    <div class="brand">
        <i class="bi bi-shop"></i><span>Chronolette Admin</span>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam"></i><span>Produits</span></a>
        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="bi bi-bag-check"></i><span>Commandes</span></a>
        <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-eye"></i><span>Voir le site</span></a>
    </nav>
</aside>

<div class="main">
    <div class="topbar">
        <div>
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
@stack('scripts')
</body>
</html>
