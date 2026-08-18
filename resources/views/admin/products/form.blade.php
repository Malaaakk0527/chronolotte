@extends('admin.layouts.app')

@section('title', ($product->exists ? 'Modifier' : 'Nouveau').' produit — Chronolette Admin')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">{{ $product->exists ? 'Modifier le produit' : 'Nouveau produit' }}</h5>

        <form action="{{ $product->exists ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($product->exists)
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Nom du produit *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="category_id" id="category_id" class="form-select" onchange="toggleNewCategory(this)">
                                <option value="">Aucune</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                                <option value="new">➕ Créer une nouvelle catégorie…</option>
                            </select>
                            <div id="new_category_wrap" style="display:none;margin-top:8px;">
                                <input type="text" name="new_category" id="new_category" class="form-control" placeholder="Nom de la nouvelle catégorie (ex. Seiko)" value="{{ old('new_category') }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Genre *</label>
                            <select name="gender" class="form-select" required>
                                <option value="homme" {{ old('gender', $product->gender) === 'homme' ? 'selected' : '' }}>Homme</option>
                                <option value="femme" {{ old('gender', $product->gender) === 'femme' ? 'selected' : '' }}>Femme</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Prix (MAD) *</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ancien prix (MAD)</label>
                            <input type="number" step="0.01" name="old_price" class="form-control" value="{{ old('old_price', $product->old_price) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock *</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? 10) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Image principale</label>
                        @if($product->image)
                            <div class="mb-2"><img src="{{ product_image_url($product->image) }}" class="product-thumb" style="width:100px;height:100px;" alt=""></div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image au survol</label>
                        @if($product->hover_image)
                            <div class="mb-2"><img src="{{ product_image_url($product->hover_image) }}" class="product-thumb" style="width:100px;height:100px;" alt=""></div>
                        @endif
                        <input type="file" name="hover_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Galerie d'images</label>
                        @if($product->exists && $product->images->isNotEmpty())
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:12px;">
                                @foreach($product->images as $img)
                                    @php
                                        $isMain = $product->image === $img->image;
                                        $isHover = $product->hover_image === $img->image;
                                    @endphp
                                    <div style="border:1px solid #dee2e6;border-radius:8px;overflow:hidden;position:relative;background:#f8f9fa;">
                                        <img src="{{ product_image_url($img->image) }}" style="width:100%;height:80px;object-fit:cover;" alt="">
                                        <div style="padding:6px 8px;">
                                            <div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:6px;">
                                                <span style="font-size:10px;padding:2px 6px;border-radius:10px;{{ $isMain ? 'background:#232f3e;color:#fff;' : 'background:#e9ecef;color:#666;' }}">Principale</span>
                                                <span style="font-size:10px;padding:2px 6px;border-radius:10px;{{ $isHover ? 'background:#232f3e;color:#fff;' : 'background:#e9ecef;color:#666;' }}">Survol</span>
                                            </div>
                                            <div style="display:flex;gap:4px;">
                                                <button type="button" class="btn btn-sm btn-outline-dark w-100" style="font-size:11px;padding:3px 6px;" onclick="postAction('{{ route('admin.products.setMainImage', [$product->id, $img->id]) }}', '{{ csrf_token() }}')">Principale</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary w-100" style="font-size:11px;padding:3px 6px;" onclick="postAction('{{ route('admin.products.setHoverImage', [$product->id, $img->id]) }}', '{{ csrf_token() }}')">Survol</button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm p-0" style="position:absolute;top:4px;right:4px;width:20px;height:20px;line-height:20px;font-size:11px;border-radius:50%;" onclick="deleteAction('{{ route('admin.products.destroyImage', $img->id) }}', '{{ csrf_token() }}')">✕</button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted" style="font-size:13px;margin-bottom:10px;">Aucune image en galerie.</div>
                        @endif
                        <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Toutes les images de la galerie apparaissent comme vignettes sur la fiche produit. Utilisez « Principale » / « Survol » pour choisir l'image affichée.</small>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="active" {{ old('active', $product->exists ? $product->active : true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Produit actif (visible en boutique)</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark px-4">Enregistrer</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function postAction(url, token) {
        var f = document.createElement('form');
        f.method = 'POST';
        f.action = url;
        f.innerHTML = '<input type="hidden" name="_token" value="' + token + '"><input type="hidden" name="_method" value="POST">';
        document.body.appendChild(f);
        f.submit();
    }
    function deleteAction(url, token) {
        var f = document.createElement('form');
        f.method = 'POST';
        f.action = url;
        f.innerHTML = '<input type="hidden" name="_token" value="' + token + '"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(f);
        f.submit();
    }
    function toggleNewCategory(select) {
        var wrap = document.getElementById('new_category_wrap');
        var input = document.getElementById('new_category');
        if (select.value === 'new') {
            wrap.style.display = 'block';
            input.focus();
        } else {
            wrap.style.display = 'none';
            input.value = '';
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        var select = document.getElementById('category_id');
        var saved = document.querySelector('input[name="new_category"]');
        if (saved && saved.value.trim() !== '') {
            select.value = 'new';
            document.getElementById('new_category_wrap').style.display = 'block';
        }
    });
</script>
@endpush
