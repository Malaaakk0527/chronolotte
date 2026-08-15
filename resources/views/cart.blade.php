@extends('layouts.app')

@section('title', 'Mon panier — Chronolette')

@section('content')
<div class="container">
    <h1 style="font-size:26px;color:#232f3e;margin:24px 0;">Mon Panier</h1>

    @if($items->isEmpty())
        <div class="empty">
            <p style="font-size:18px;">Votre panier est vide.</p>
            <a href="{{ route('home') }}" class="btn btn-dark">Découvrir nos montres</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:30px;" class="cart-layout">
            <div>
                @foreach($items as $item)
                    @php $p = $item['product']; @endphp
                    <div style="display:flex;gap:18px;border:1px solid #eee;border-radius:10px;padding:16px;margin-bottom:14px;align-items:center;" class="cart-row">
                        <a href="{{ route('product.show', $p->slug) }}" style="flex-shrink:0;width:90px;height:90px;border-radius:8px;overflow:hidden;">
                            <img src="{{ product_image_url($p->image) }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover;">
                        </a>
                        <div style="flex:1;">
                            <a href="{{ route('product.show', $p->slug) }}" style="font-weight:600;">{{ $p->name }}</a>
                            <div style="font-size:14px;color:#666;margin-top:4px;">{{ format_mad($p->price) }} × {{ $item['quantity'] }} = <strong>{{ format_mad($item['line_total']) }}</strong></div>
                        </div>
                        <form action="{{ route('cart.update') }}" method="POST" style="display:flex;gap:8px;align-items:center;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="99" style="width:60px;padding:8px;border:1px solid #ccc;border-radius:6px;">
                            <button class="btn btn-outline" style="padding:8px 12px;">OK</button>
                        </form>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <button class="btn btn-outline" style="padding:8px 12px;color:#c5221f;">✕</button>
                        </form>
                    </div>
                @endforeach
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline">Vider le panier</button>
                </form>
            </div>

            <div style="border:1px solid #eee;border-radius:10px;padding:20px;align-self:start;" class="cart-summary">
                <h3 style="margin:0 0 16px;">Résumé</h3>
                <div style="display:flex;justify-content:space-between;font-size:15px;margin-bottom:10px;">
                    <span>Articles</span><span>{{ $items->sum('quantity') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:700;border-top:1px solid #eee;padding-top:12px;">
                    <span>Total</span><span>{{ format_mad($total) }}</span>
                </div>
                <div style="font-size:13px;color:#666;margin-top:8px;">Paiement à la livraison</div>
                <a href="{{ route('order.checkout') }}" class="btn btn-primary" style="width:100%;margin-top:16px;">Passer la commande</a>
                <a href="{{ route('home') }}" style="display:block;text-align:center;margin-top:12px;font-size:14px;color:var(--bv-purple);">Continuer mes achats</a>
            </div>
        </div>
    @endif
</div>

<style>
    @media (max-width: 900px) { .cart-layout { grid-template-columns: 1fr !important; } .cart-row { flex-wrap: wrap; } }
    @media (max-width: 640px) {
        .cart-row { gap: 12px; }
        .cart-row > a { width: 72px !important; height: 72px !important; }
        .cart-summary { padding: 16px !important; }
    }
</style>
@endsection
