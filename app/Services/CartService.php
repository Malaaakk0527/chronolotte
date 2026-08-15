<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    public const SESSION_KEY = 'cart';

    public function all(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = $this->all();
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        session()->put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->all();
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }
        session()->put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $this->update($productId, 0);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum($this->all());
    }

    public function items(): Collection
    {
        $cart = $this->all();
        $ids = array_keys($cart);

        return Product::whereIn('id', $ids)->get()->map(function (Product $product) use ($cart) {
            return [
                'product' => $product,
                'quantity' => $cart[$product->id],
                'line_total' => $product->price * $cart[$product->id],
            ];
        })->values();
    }

    public function total(): float
    {
        return $this->items()->sum('line_total');
    }
}
