<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Chronolette | Montres Originales au Maroc')</title>
    <meta name="description" content="@yield('meta_description', 'Chronolette, votre boutique de montres originales au Maroc. Modèles homme et femme 100% authentiques, garantie 2 ans et paiement à la livraison partout au Royaume.')">
    <link rel="icon" href="{{ asset('images/logo-site.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/fl-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatsome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatsome-shop.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    @stack('head')
    <style>
        :root { --bv-dark: #131921; --bv-dark2: #1a2a3a; --bv-orange: #e8a317; --bv-purple: #7b0099; }
        body { font-family: "Lato", sans-serif; margin: 0; color: #131a22; background: #fff; }
        a { color: #131a22; text-decoration: none; }
        img { max-width: 100%; height: auto; }

        .topbar { background: #000; color: #111; font-size: 13px; padding: 8px 0; text-align: center; }
        .topbar .container { display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap; }
        .topbar a { color: #111; }

        .masthead { background: #131921; color: #fff; }
        .masthead .container { max-width: 1240px; margin: 0 auto; padding: 14px 20px; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 24px; }
        .masthead .logo { justify-self: center; }
        .masthead .logo img { max-height: 110px; display: block; }
        .masthead .cart-link { justify-self: end; }
        .searchbox { display: flex; flex-direction: column; gap: 6px; max-width: 560px; justify-self: start; }
        .searchbox .search-row { display: flex; }
        .searchbox .search-input { flex: 1; min-width: 0; padding: 10px 14px; border: none; border-radius: 6px 0 0 6px; font-size: 14px; outline: none; }
        .searchbox button { background: var(--bv-orange); color: #111; border: none; padding: 0 18px; border-radius: 0 6px 6px 0; cursor: pointer; font-size: 15px; }
        .searchbox .price-filter { display: flex; align-items: center; gap: 6px; }
        .searchbox .price-filter label { color: #cbd5e1; font-size: 12px; font-weight: 600; white-space: nowrap; }
        .searchbox .price-filter input { width: 70px; padding: 5px 6px; border: none; border-radius: 5px; font-size: 13px; outline: none; background: #fff; text-align: center; -moz-appearance: textfield; }
        .searchbox .price-filter input::-webkit-outer-spin-button, .searchbox .price-filter input::-webkit-inner-spin-button { -webkit-appearance: none; }
        .searchbox .price-filter span { color: #cbd5e1; font-size: 12px; }

        .cart-link { display: flex; align-items: center; gap: 8px; color: #fff; font-size: 14px; font-weight: 600; position: relative; }
        .cart-link .cart-count { background: var(--bv-orange); color: #111; border-radius: 50%; font-size: 12px; padding: 2px 7px; font-weight: 700; }

        .widenav { background: var(--bv-dark2); }
        .widenav .container { max-width: 1240px; margin: 0 auto; display: flex; justify-content: center; gap: 4px; flex-wrap: wrap; }
        .widenav a { color: #fff; font-weight: 600; font-size: 14px; letter-spacing: .4px; padding: 12px 16px; display: inline-block; }
        .widenav a:hover, .widenav a.active { color: var(--bv-orange); }

        main { min-height: 60vh; }

        .flash { max-width: 1240px; margin: 14px auto 0; padding: 0 20px; }
        .flash .msg { padding: 12px 16px; border-radius: 6px; margin-bottom: 10px; font-size: 14px; }
        .flash .success { background: #e6f4ea; color: #137333; border: 1px solid #a8dab5; }
        .flash .error { background: #fce8e6; color: #c5221f; border: 1px solid #f5c6c2; }

        footer { background: #131A22; color: #DDD; margin-top: 60px; }
        footer .container { max-width: 1240px; margin: 0 auto; padding: 40px 20px 20px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; }
        footer h4 { color: var(--bv-orange); font-size: 14px; text-transform: uppercase; margin: 0 0 14px; }
        footer ul { list-style: none; padding: 0; margin: 0; }
        footer li { font-size: 13px; line-height: 2; }
        footer a { color: #fff; }
        footer a:hover { color: var(--bv-orange); }
        footer .copyright { border-top: 1px solid #232f3e; padding: 16px 0; text-align: center; font-size: 12px; color: #999; }

        .whatsapp-float { position: fixed; bottom: 15px; left: 15px; z-index: 9999; width: 56px; height: 56px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,.25); transition: transform .15s; }
        .whatsapp-float:hover { transform: scale(1.08); }
        .whatsapp-float svg { width: 32px; height: 32px; fill: #fff; }

        .products-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
        .product-card { background: #fff; border: 1px solid #eee; border-radius: 8px; overflow: hidden; position: relative; transition: box-shadow .2s; }
        .product-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,.1); }
        .product-card .badge { position: absolute; top: 10px; left: 10px; background: #d26e4b; color: #fff; font-size: 12px; font-weight: 700; border-radius: 50%; padding: 8px 9px; z-index: 2; }
        .product-card .img-wrap { display: block; position: relative; padding-top: 100%; overflow: hidden; background: #f7f7f7; }
        .product-card .img-wrap img { position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: opacity .3s; }
        .product-card .img-wrap .hover { opacity: 0; }
        .product-card:hover .img-wrap .hover { opacity: 1; }
        .product-card .body { padding: 14px; text-align: center; }
        .product-card .name { font-size: 14px; margin: 0 0 6px; }
        .product-card .name a:hover { color: var(--bv-purple); }
        .product-card .price .old { color: #999; text-decoration: line-through; font-size: 13px; margin-right: 8px; }
        .product-card .price .current { color: #000; font-weight: 700; font-size: 15px; }

        .section-title { text-align: center; margin: 46px 0 24px; font-size: 22px; font-weight: 700; letter-spacing: .5px; }
        .section-title .more { font-size: 13px; font-weight: 400; color: #666; margin-left: 10px; }

        .btn { display: inline-block; padding: 10px 18px; border-radius: 6px; font-weight: 700; font-size: 14px; border: none; cursor: pointer; text-align: center; }
        .btn-primary { background: var(--bv-purple); color: #fff; }
        .btn-primary:hover { background: #62007a; color: #fff; }
        .btn-dark { background: #131921; color: #fff; }
        .btn-outline { background: transparent; border: 1px solid #ccc; color: #131a22; }
        .btn-whatsapp { background: #25D366; color: #fff; }
        .btn-whatsapp:hover { background: #1fbd59; color: #fff; }

        .container { max-width: 1240px; margin: 0 auto; padding: 0 20px; }

        @media (max-width: 1024px) {
            .products-grid { grid-template-columns: repeat(3, 1fr); gap: 18px; }
            footer .container { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .masthead .container { gap: 12px; grid-template-columns: auto 1fr; }
            .masthead .logo { justify-self: start; }
            .masthead .logo img { max-height: 70px; }
            .searchbox { order: 3; grid-column: 1 / -1; justify-self: stretch; max-width: none; }
            .masthead .cart-link { margin-left: 0; }
            .topbar { font-size: 12px; }
            .topbar .container { gap: 10px; }
        }

        @media (max-width: 640px) {
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            footer .container { grid-template-columns: 1fr; gap: 24px; }
            .masthead .logo img { max-height: 60px; }
            .masthead .container { padding: 10px 14px; }
            .container { padding: 0 14px; }
            .widenav a { font-size: 13px; padding: 10px 10px; }
            .section-title { font-size: 18px; margin: 36px 0 18px; }
            .product-card .body { padding: 10px; }
            .product-card .name { font-size: 12px; }
            .product-card .price .current { font-size: 14px; }
            .whatsapp-float { width: 48px; height: 48px; bottom: 12px; left: 12px; }
            .whatsapp-float svg { width: 26px; height: 26px; }
            .searchbox button { padding: 0 12px; font-size: 14px; }
            .cart-link { font-size: 13px; }
        }

        .breadcrumb { font-size: 13px; color: #777; margin: 20px 0; }
        .breadcrumb a { color: var(--bv-purple); }
        .empty { text-align: center; padding: 60px 20px; color: #888; }
    </style>
</head>
<body>
<div class="topbar">
    <div class="container">
        <span>✓ Livraison et retours gratuits</span>
        <a href="mailto:contact@chronolette.ma">✉ contact@chronolette.ma</a>
    </div>
</div>

<div class="masthead">
    <div class="container">
        <form class="searchbox" action="{{ route('home') }}" method="GET">
            <div class="search-row">
                <input type="search" name="q" class="search-input" placeholder="Rechercher une montre…" value="{{ request('q') }}">
                <button type="submit">Rechercher</button>
            </div>
            <div class="price-filter">
                <label>Prix (Dh)</label>
                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" min="0">
                <span>–</span>
                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" min="0">
            </div>
        </form>
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('images/logo-site.png') }}" alt="CHRONOLETTE">
        </a>
        <a href="{{ route('cart.index') }}" class="cart-link">
            <i class="icon-shopping-bag" style="font-size:22px;"></i>
            Panier
            @if(cart_badge_count() > 0)
                <span class="cart-count">{{ cart_badge_count() }}</span>
            @endif
        </a>
    </div>
</div>

<nav class="widenav">
    <div class="container">
        <a href="{{ route('home') }}" class="{{ !request('sexe') && !request('categorie') ? 'active' : '' }}">Accueil</a>
        <a href="{{ route('home', ['sexe' => 'femme']) }}" class="{{ request('sexe') === 'femme' ? 'active' : '' }}">Montres Femmes</a>
        <a href="{{ route('home', ['sexe' => 'homme']) }}" class="{{ request('sexe') === 'homme' ? 'active' : '' }}">Montres Hommes</a>
        <a href="{{ whatsapp_link('Bonjour CHRONOLETTE, j\'ai une question.') }}">Contact</a>
        <a href="https://www.instagram.com/chronolette" target="_blank" rel="noopener">Instagram</a>
    </div>
</nav>

<main>
    <div class="flash">
        @if(session('success'))
            <div class="msg success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="msg error">{{ session('error') }}</div>
        @endif
    </div>
    @yield('content')
</main>

<footer>
    <div class="container">
        <div>
            <h4>Chronolette</h4>
            <p style="font-size:13px;">Votre boutique n°1 de montres 100% originales au Maroc. Garantie 2 ans, paiement à la livraison, livraison 48h.</p>
        </div>
        <div>
            <h4>Boutique</h4>
            <ul>
                <li><a href="{{ route('home', ['sexe' => 'homme']) }}">Montres Hommes</a></li>
                <li><a href="{{ route('home', ['sexe' => 'femme']) }}">Montres Femmes</a></li>
                <li><a href="{{ route('home') }}">Toutes les montres</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
            <ul>
                <li><a href="mailto:contact@chronolette.ma">contact@chronolette.ma</a></li>
                <li><a href="{{ whatsapp_link('Bonjour CHRONOLETTE, j\'ai une question.') }}">WhatsApp</a></li>
                <li><a href="https://www.instagram.com/chronolette" target="_blank" rel="noopener">Instagram</a></li>
                <li>Livraison partout au Maroc</li>
            </ul>
        </div>
        <div>
            <h4>Paiement</h4>
            <ul>
                <li>Paiement à la livraison</li>
                <li>Cash on Delivery (COD)</li>
                <li>Devis 48H partout au Royaume</li>
            </ul>
        </div>
    </div>
    <div class="copyright">© {{ date('Y') }} Chronolette.ma — Tous droits réservés</div>
</footer>

    <a href="{{ whatsapp_link('Bonjour CHRONOLETTE, je suis intéressé(e) par vos montres. Pouvez-vous m\'aider ?') }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Discuter sur WhatsApp">
    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M16.004 3.2c-7.06 0-12.8 5.74-12.8 12.8 0 2.26.6 4.47 1.73 6.41L3.2 28.8l6.55-1.72a12.74 12.74 0 0 0 6.25 1.6h.01c7.06 0 12.8-5.74 12.8-12.8 0-3.42-1.33-6.63-3.75-9.05a12.72 12.72 0 0 0-9.05-3.63zM11.6 9.8c.28 0 .56.02.8.03.26.02.52.04.76.58.3.66.98 2.3 1.06 2.46.09.17.14.37.03.6-.12.23-.17.37-.34.56-.17.2-.36.44-.51.6-.17.17-.35.35-.15.69.2.33.89 1.47 1.92 2.38 1.32 1.17 2.43 1.53 2.78 1.7.34.17.54.15.74-.08.2-.23.85-.99 1.08-1.33.22-.34.45-.28.76-.17.31.12 1.98.94 2.32 1.1.34.17.57.25.65.4.09.14.09.82-.21 1.62-.3.8-1.76 1.53-2.42 1.58-.64.06-1.29.29-4.35-.91-3.66-1.43-5.99-5.1-6.17-5.34-.18-.24-1.46-1.95-1.46-3.72 0-1.77.93-2.64 1.26-3 .34-.36.75-.45 1.03-.45z" fill="#fff"/></svg>
</a>

@stack('scripts')
</body>
</html>
