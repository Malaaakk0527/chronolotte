@php
    $p = $product;
    $discount = $p->discountPercent();
    $image = $p->image;
    $hover = $p->hover_image ?? $image;
@endphp
<div class="product-card">
    @if($discount)
        <span class="badge">-{{ $discount }}%</span>
    @endif
    <a href="{{ route('product.show', $p->slug) }}" class="img-wrap">
        <img src="{{ product_image_url($image) }}" alt="{{ $p->name }}" loading="lazy">
        @if($hover && $hover !== $image)
            <img src="{{ product_image_url($hover) }}" alt="{{ $p->name }}" class="hover" loading="lazy">
        @endif
    </a>
    <div class="body">
        <p class="name"><a href="{{ route('product.show', $p->slug) }}">{{ $p->name }}</a></p>
        <div class="price">
            @if($p->old_price)
                <span class="old">{{ format_mad($p->old_price) }}</span>
            @endif
            <span class="current">{{ format_mad($p->price) }}</span>
        </div>
        @if(! $p->inStock())
            <div style="color:#c5221f;font-size:12px;margin-top:6px;">Rupture de stock</div>
        @endif
    </div>
</div>
