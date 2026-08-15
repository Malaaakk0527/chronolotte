@extends('admin.layouts.app')

@section('title', 'Produits — Chronolette Admin')

@section('content')
<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Produits ({{ $products->total() }})</h5>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher..." class="form-control form-control-sm" style="width:200px;">
                <select name="category" class="form-select form-select-sm" style="width:150px;" onchange="this.form.submit()">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @if(request('q') || request('category'))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                @endif
            </form>
            <a href="{{ route('admin.products.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg"></i> Nouveau produit</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th></th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Genre</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td><img src="{{ product_image_url($product->image) }}" class="product-thumb" alt=""></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td>{{ ucfirst($product->gender) }}</td>
                        <td>
                            {{ format_mad($product->price) }}
                            @if($product->old_price)
                                <div style="font-size:12px;color:#999;text-decoration:line-through;">{{ format_mad($product->old_price) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->stock <= 0 ? 'bg-danger' : ($product->stock <= 5 ? 'bg-warning text-dark' : 'bg-success') }}">{{ $product->stock }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm {{ $product->active ? 'btn-success' : 'btn-secondary' }}" title="Activer / désactiver">
                                    {{ $product->active ? 'Actif' : 'Inactif' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer définitivement ce produit ?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4">Aucun produit trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="card-body">{{ $products->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
