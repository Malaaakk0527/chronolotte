@extends('admin.layouts.app')

@section('title', 'Commande '.$order->order_number.' — Chronolette Admin')

@php $labels = ['nouvelle' => 'Nouvelle', 'confirmee' => 'Confirmée', 'expediee' => 'Expédiée', 'livree' => 'Livrée', 'annulee' => 'Annulée']; @endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0">Commande {{ $order->order_number }}</h4>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left"></i> Retour</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card p-3 mb-3">
            <h5 class="card-title">Articles</h5>
            <table class="table mb-0">
                <thead>
                    <tr><th>Produit</th><th>Prix</th><th>Qté</th><th>Sous-total</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <a href="{{ route('admin.products.edit', $item->product_id) }}" class="text-decoration-none">{{ $item->product_name }}</a>
                                @if($item->product?->image)
                                    <div><img src="{{ product_image_url($item->product->image) }}" class="product-thumb mt-1" alt=""></div>
                                @endif
                            </td>
                            <td>{{ format_mad($item->price) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><strong>{{ format_mad($item->price * $item->quantity) }}</strong></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total</td>
                        <td><strong>{{ format_mad($order->total) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-3 mb-3">
            <h5 class="card-title">Statut</h5>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <select name="status" class="form-select mb-2" onchange="this.form.submit()">
                    @foreach(\App\Models\Order::STATUSES as $status)
                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $labels[$status] }}</option>
                    @endforeach
                </select>
            </form>
            <span class="badge badge-status" style="background:{{ $order->statusColor() }};">{{ $order->statusLabel() }}</span>
            <div style="font-size:12px;color:#888;margin-top:8px;">Créée le {{ $order->created_at->format('d/m/Y à H:i') }}</div>
        </div>

        <div class="card p-3 mb-3">
            <h5 class="card-title">Client</h5>
            <div style="font-size:14px;line-height:1.9;">
                <div><i class="bi bi-person me-2"></i>{{ $order->customer_name }}</div>
                <div><i class="bi bi-telephone me-2"></i>{{ $order->phone }}</div>
                <div><i class="bi bi-geo-alt me-2"></i>{{ $order->city }}</div>
                <div><i class="bi bi-house me-2"></i>{{ $order->address }}</div>
                @if($order->note)
                    <div class="mt-2 p-2 rounded" style="background:#fff8e6;font-size:13px;"><i class="bi bi-chat-left-text me-1"></i>{{ $order->note }}</div>
                @endif
            </div>
        </div>

        @php
            $waMsg = "Bonjour {$order->customer_name}, votre commande {$order->order_number} ({$labels[$order->status]}) d'un montant de ".format_mad($order->total)." est confirmée. Merci de votre confiance !";
        @endphp
        <a href="{{ whatsapp_link($waMsg) }}" target="_blank" rel="noopener" class="btn btn-success w-100 mb-3"><i class="bi bi-whatsapp"></i> Contacter le client (WhatsApp)</a>

        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette commande ?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Supprimer la commande</button>
        </form>
    </div>
</div>
@endsection
