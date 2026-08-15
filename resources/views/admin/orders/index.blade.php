@extends('admin.layouts.app')

@section('title', 'Commandes — Chronolette Admin')

@php
    $labels = ['nouvelle' => 'Nouvelle', 'confirmee' => 'Confirmée', 'expediee' => 'Expédiée', 'livree' => 'Livrée', 'annulee' => 'Annulée'];
@endphp

@section('content')
<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Commandes ({{ $orders->total() }})</h5>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <select name="status" class="form-select form-select-sm" style="width:160px;" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $labels[$status] }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="N°, nom, téléphone, ville..." class="form-control form-control-sm" style="width:220px;">
                <button class="btn btn-sm btn-dark">Filtrer</button>
                @if(request('status') || request('search'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                @endif
            </form>
            <a href="{{ route('admin.orders.export', request()->only(['status', 'search'])) }}" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-arrow-down"></i> Export CSV</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Articles</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ $order->city }}</td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td><strong>{{ format_mad($order->total) }}</strong></td>
                        <td style="font-size:13px;color:#666;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width:130px;font-size:12px;" onchange="this.form.submit()">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $labels[$status] }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-eye"></i></a>
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette commande ?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4">Aucune commande trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="card-body">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
