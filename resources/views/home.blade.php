@extends('layouts.app')

@section('title', $search ? "Recherche : $search — Chronolette" : (request('min_price') || request('max_price') ? 'Montres filtrées par prix — Chronolette' : 'Chronolette | Montres Originales au Maroc – Homme & Femme'))

@section('content')
<div class="container">
    @if($search || request('min_price') || request('max_price'))
        <div class="breadcrumb">
            @if($search)
                Résultats pour « {{ $search }} »
            @elseif(request('min_price') && request('max_price'))
                Montres entre {{ request('min_price') }} Dh et {{ request('max_price') }} Dh
            @elseif(request('min_price'))
                Montres à partir de {{ request('min_price') }} Dh
            @elseif(request('max_price'))
                Montres jusqu'à {{ request('max_price') }} Dh
            @endif
            <a class="more" href="{{ route('home') }}">Voir tout</a>
        </div>
    @else
        <div style="margin:24px 0 0; text-align:center;">
            <img src="{{ asset('images/montres-premium-maroc-homme-femme.webp.png') }}" alt="Montres premium au Maroc — Chronolette" style="display:block; margin:0 auto; max-width:100%; height:auto;">
        </div>
    @endif

    @if(request('sexe') || request('categorie'))
        @php $sectionProducts = request('sexe') ? (request('sexe') === 'femme' ? $women : $men) : collect(); @endphp
        @if(request('sexe'))
            <h2 class="section-title">
                Montres {{ request('sexe') === 'femme' ? 'Femmes' : 'Hommes' }}
                <a class="more" href="{{ route('home') }}">Voir tout</a>
            </h2>
            @if($sectionProducts->isEmpty())
                <div class="empty">Aucune montre trouvée pour le moment.</div>
            @else
                <div class="products-grid">
                    @foreach($sectionProducts as $p)
                        @include('partials.product-card', ['product' => $p])
                    @endforeach
                </div>
            @endif
        @endif
        @if(request('categorie'))
            @php
                $cat = \App\Models\Category::where('slug', request('categorie'))->first();
                $catProducts = $cat ? $cat->products()->where('active', true)->get() : collect();
            @endphp
            <h2 class="section-title">
                {{ $cat->name ?? 'Catégorie' }}
                <a class="more" href="{{ route('home') }}">Voir tout</a>
            </h2>
            @if($catProducts->isEmpty())
                <div class="empty">Aucun produit dans cette catégorie.</div>
            @else
                <div class="products-grid">
                    @foreach($catProducts as $p)
                        @include('partials.product-card', ['product' => $p])
                    @endforeach
                </div>
            @endif
        @endif
    @else
        <h2 class="section-title">
            Montres Hommes
            <a class="more" href="{{ route('home', ['sexe' => 'homme']) }}">Voir Plus <i class="icon-angle-right"></i></a>
        </h2>
        @if($men->isEmpty())
            <div class="empty">Aucune montre homme disponible pour le moment.</div>
        @else
            <div class="products-grid">
                @foreach($men as $p)
                    @include('partials.product-card', ['product' => $p])
                @endforeach
            </div>
        @endif

        <h2 class="section-title">
            Montres Femmes
            <a class="more" href="{{ route('home', ['sexe' => 'femme']) }}">Voir Plus <i class="icon-angle-right"></i></a>
        </h2>
        @if($women->isEmpty())
            <div class="empty">Aucune montre femme disponible pour le moment.</div>
        @else
            <div class="products-grid">
                @foreach($women as $p)
                    @include('partials.product-card', ['product' => $p])
                @endforeach
            </div>
        @endif
    @endif
</div>
@endsection
