@extends('admin.layouts.app')

@section('title', 'Tableau de bord — Chronolette Admin')

@section('content')
<div class="row g-3">
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 stat-card">
            <div class="d-flex align-items-center">
                <div class="icon me-3" style="background:#7b68ee;"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['products'] }}</div>
                    <div class="text-muted" style="font-size:13px;">Produits</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 stat-card">
            <div class="d-flex align-items-center">
                <div class="icon me-3" style="background:#0a9396;"><i class="bi bi-tags"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['categories'] }}</div>
                    <div class="text-muted" style="font-size:13px;">Catégories</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 stat-card">
            <div class="d-flex align-items-center">
                <div class="icon me-3" style="background:#1d9bf0;"><i class="bi bi-bag-check"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['orders'] }}</div>
                    <div class="text-muted" style="font-size:13px;">Commandes</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 stat-card">
            <div class="d-flex align-items-center">
                <div class="icon me-3" style="background:#e76f51;"><i class="bi bi-currency-exchange"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ format_mad($stats['revenue']) }}</div>
                    <div class="text-muted" style="font-size:13px;">Chiffre d'affaires</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 stat-card">
            <div class="d-flex align-items-center">
                <div class="icon me-3" style="background:#2a9d8f;"><i class="bi bi-calendar-week"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['orders_month'] }}</div>
                    <div class="text-muted" style="font-size:13px;">Commandes ce mois-ci</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 stat-card">
            <div class="d-flex align-items-center">
                <div class="icon me-3" style="background:#1d9bf0;"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ format_mad($stats['revenue_month']) }}</div>
                    <div class="text-muted" style="font-size:13px;">CA ce mois-ci</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 stat-card">
            <div class="d-flex align-items-center">
                <div class="icon me-3" style="background:#e76f51;"><i class="bi bi-bell"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['new_orders'] }}</div>
                    <div class="text-muted" style="font-size:13px;">Nouvelles commandes</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 stat-card">
            <div class="d-flex align-items-center">
                <div class="icon me-3" style="background:#0a9396;"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['active_products'] }}</div>
                    <div class="text-muted" style="font-size:13px;">Produits actifs</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="card-title mb-3">Top 5 produits les plus vendus</h5>
            @if($topProducts->isEmpty())
                <div class="text-muted" style="font-size:14px;">Aucune vente confirmée pour le moment.</div>
            @else
                <table class="table table-sm mb-0">
                    <thead><tr><th>Produit</th><th class="text-center">Qté</th><th class="text-end">CA</th></tr></thead>
                    <tbody>
                        @foreach($topProducts as $tp)
                            <tr>
                                <td>{{ $tp->product_name }}</td>
                                <td class="text-center">{{ $tp->qty }}</td>
                                <td class="text-end">{{ format_mad($tp->revenue) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="card-title mb-3">Ventes — 7 derniers jours</h5>
            @php $maxRevenue = max(1, $salesByDay->max('revenue')); @endphp
            <div class="d-flex align-items-end justify-content-between gap-2" style="height:160px;">
                @foreach($salesByDay as $day)
                    @php
                        $height = $day['revenue'] > 0 ? round(($day['revenue'] / $maxRevenue) * 100) : 3;
                    @endphp
                    <div style="flex:1;text-align:center;">
                        <div style="font-size:11px;color:#666;margin-bottom:4px;">{{ $day['revenue'] > 0 ? format_mad($day['revenue']) : '' }}</div>
                        <div class="mx-auto" style="width:70%;max-width:38px;height:{{ $height }}px;background:{{ $day['orders'] > 0 ? '#0a9396' : '#e9ecef' }};border-radius:4px 4px 0 0;"></div>
                        <div style="font-size:11px;color:#666;margin-top:4px;">{{ $day['label'] }}</div>
                        <div style="font-size:11px;font-weight:600;">{{ $day['orders'] }} cmd</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="card-title mb-3">Commandes par statut</h5>
            @foreach(\App\Models\Order::STATUSES as $status)
                @php
                    $count = $stats['orders_by_status'][$status] ?? 0;
                    $labels = ['nouvelle' => 'Nouvelle', 'confirmee' => 'Confirmée', 'expediee' => 'Expédiée', 'livree' => 'Livrée', 'annulee' => 'Annulée'];
                    $colors = ['nouvelle' => '#1d9bf0', 'confirmee' => '#0a9396', 'expediee' => '#7b68ee', 'livree' => '#2a9d8f', 'annulee' => '#e76f51'];
                @endphp
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:14px;">
                        <span class="badge badge-status" style="background:{{ $colors[$status] }};">{{ $labels[$status] }}</span>
                    </span>
                    <span class="fw-bold">{{ $count }}</span>
                </div>
            @endforeach
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-dark mt-2">Voir les commandes</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="card-title mb-3">Dernières commandes</h5>
            @if($latestOrders->isEmpty())
                <div class="text-muted" style="font-size:14px;">Aucune commande pour le moment.</div>
            @else
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>N°</th><th>Client</th><th>Total</th><th>Statut</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($latestOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ format_mad($order->total) }}</td>
                                <td><span class="badge badge-status" style="background:{{ $order->statusColor() }};">{{ $order->statusLabel() }}</span></td>
                                <td><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">Voir</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="card-title mb-3">Produits en stock faible</h5>
            @if($lowStock->isEmpty())
                <div class="text-muted" style="font-size:14px;">Tous les produits ont un stock suffisant.</div>
            @else
                <table class="table table-sm mb-0">
                    <thead><tr><th>Produit</th><th>Stock</th><th></th></tr></thead>
                    <tbody>
                        @foreach($lowStock as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td><span class="badge {{ $p->stock <= 0 ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $p->stock }}</span></td>
                                <td><a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-outline-dark">Modifier</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="card-title mb-3">Actions rapides</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg"></i> Ajouter un produit</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">Gérer les produits</a>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-dark">Visiter la boutique</a>
            </div>
        </div>
    </div>
</div>
@endsection
