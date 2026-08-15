@extends('layouts.app')

@section('title', $product->name.' — Chronolette')
@section('meta_description', Str::limit($product->description ?? $product->name, 160))

@section('content')
<div class="container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Accueil</a> /
        @if($product->category)
            <a href="{{ route('home', ['categorie' => $product->category->slug]) }}">{{ $product->category->name }}</a> /
        @endif
        {{ $product->name }}
    </div>

    <div style="display:grid;grid-template-columns:1.1fr 1fr;gap:40px;" class="product-layout">
        <div>
            @php $images = $product->images->pluck('image')->prepend($product->image)->filter()->unique()->values(); @endphp
            <div style="border:1px solid #eee;border-radius:10px;overflow:hidden;">
                <img id="main-img" src="{{ product_image_url($product->image) }}" alt="{{ $product->name }}" style="width:100%;display:block;">
            </div>
            @if($images->count() > 1)
                <div style="display:flex;gap:10px;margin-top:12px;flex-wrap:wrap;">
                    @foreach($images as $img)
                        <div style="width:70px;height:70px;border:2px solid #ddd;border-radius:8px;overflow:hidden;cursor:pointer;"
                             onclick="document.getElementById('main-img').src='{{ product_image_url($img) }}';">
                            <img src="{{ product_image_url($img) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h1 style="font-size:24px;margin:0 0 12px;color:#232f3e;">{{ $product->name }}</h1>
            @if($product->category)
                <div style="font-size:13px;color:#777;margin-bottom:14px;">Catégorie : <a href="{{ route('home', ['categorie' => $product->category->slug]) }}" style="color:var(--bv-purple);">{{ $product->category->name }}</a></div>
            @endif

            <div style="font-size:30px;font-weight:700;margin:10px 0;" class="prod-price">
                {{ format_mad($product->price) }}
                @if($product->old_price)
                    <span style="color:#999;font-size:18px;text-decoration:line-through;font-weight:400;margin-left:10px;">{{ format_mad($product->old_price) }}</span>
                    <span style="background:#d26e4b;color:#fff;font-size:13px;border-radius:4px;padding:3px 8px;margin-left:10px;">-{{ $product->discountPercent() }}%</span>
                @endif
            </div>

            <div style="font-size:14px;margin-bottom:18px;">
                @if($product->inStock())
                    <span style="color:#137333;">✓ En stock ({{ $product->stock }} disponibles)</span>
                @else
                    <span style="color:#c5221f;">✗ Rupture de stock</span>
                @endif
            </div>

            @if($product->description)
                <div style="font-size:15px;line-height:1.7;color:#333;margin-bottom:22px;">{{ $product->description }}</div>
            @endif

            <div style="display:flex;gap:12px;align-items:center;margin-bottom:22px;flex-wrap:wrap;" class="prod-actions">
                @if($product->inStock())
                    <form action="{{ route('cart.add') }}" method="POST" style="display:flex;gap:10px;flex-wrap:wrap;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" style="width:70px;padding:10px;border:1px solid #ccc;border-radius:6px;font-size:15px;">
                        <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                    </form>
                @endif
            </div>

            @php
                $waMessage = "Bonjour CHRONOLETTE, je suis intéressé(e) par : {$product->name} (".format_mad($product->price)."). Pouvez-vous m'aider ?";
            @endphp
            <a href="{{ whatsapp_link($waMessage) }}" target="_blank" rel="noopener" class="btn btn-whatsapp">Commander via WhatsApp</a>

            <div style="margin-top:26px;background:#f7f7f7;border-radius:8px;padding:16px;font-size:14px;line-height:2;color:#333;">
                <strong>Paiement à la livraison</strong> partout au Maroc
                <br>✓ Livraison 48h — Garantie 2 ans — 100% authentique
            </div>
        </div>
    </div>

    @if($related->isNotEmpty())
        <h2 class="section-title">Vous aimerez aussi</h2>
        <div class="products-grid">
            @foreach($related as $p)
                @include('partials.product-card', ['product' => $p])
            @endforeach
        </div>
    @endif
</div>

<style>
    @media (max-width: 900px) { .product-layout { grid-template-columns: 1fr !important; gap: 24px !important; } }
    @media (max-width: 640px) {
        .prod-price { font-size: 24px !important; }
        .prod-actions form { width: 100%; }
        .prod-actions form input[type="number"] { flex: 1; }
        .prod-actions .btn-primary, .btn-whatsapp { width: 100%; }
    }
</style>
@endsection
