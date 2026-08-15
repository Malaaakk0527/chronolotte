<?php

use App\Models\Product;

if (! function_exists('format_mad')) {
    function format_mad(float|int|null $amount): string
    {
        if ($amount === null) {
            return '';
        }
        return number_format((float) $amount, 0, ',', ' ').' Dh';
    }
}

if (! function_exists('whatsapp_link')) {
    function whatsapp_link(string $message, string $number = '212631724118'): string
    {
        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}

if (! function_exists('admin_whatsapp_number')) {
    function admin_whatsapp_number(): string
    {
        return '212631724118';
    }
}

if (! function_exists('wa_order_message')) {
    function wa_order_message(\App\Models\Order $order): string
    {
        $lines = [];
        $lines[] = '🛍️ *NOUVELLE COMMANDE — CHRONOLETTE*';
        $lines[] = 'N° : '.$order->order_number;
        $lines[] = 'Client : '.$order->customer_name;
        $lines[] = 'Tél : '.$order->phone;
        $lines[] = 'Ville : '.$order->city;
        $lines[] = 'Adresse : '.$order->address;
        foreach ($order->items as $item) {
            $lines[] = '• '.$item->product_name.' ×'.$item->quantity.' = '.format_mad($item->price * $item->quantity);
        }
        $lines[] = '💰 *TOTAL : '.format_mad($order->total).'*';

        return implode("\n", $lines);
    }
}

if (! function_exists('cart_badge_count')) {
    function cart_badge_count(): int
    {
        return app(\App\Services\CartService::class)->count();
    }
}

if (! function_exists('product_image')) {
    function product_image(?Product $product, string $class = ''): string
    {
        $src = product_image_url($product?->image);

        return '<img src="'.e($src).'" alt="'.e($product?->name ?? '').'" class="'.e($class).'">';
    }
}

if (! function_exists('product_image_url')) {
    function product_image_url(?string $path): string
    {
        $path = $path ?: 'images/dummy-1.jpg';

        if (($base = env('SUPABASE_STORAGE_URL')) && ! str_starts_with($path, 'http')) {
            return rtrim($base, '/').'/'.ltrim($path, '/');
        }

        return asset($path);
    }
}
