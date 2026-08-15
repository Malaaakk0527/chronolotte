<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function checkout()
    {
        $items = $this->cart->items();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        return view('checkout', [
            'items' => $items,
            'total' => $this->cart->total(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        $items = $this->cart->items();
        if ($items->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        $order = Order::create([
            'order_number' => 'BV-'.now()->format('ymd').'-'.Str::upper(Str::random(4)),
            'customer_name' => $data['customer_name'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'address' => $data['address'],
            'note' => $data['note'] ?? null,
            'total' => $this->cart->total(),
            'status' => 'nouvelle',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'price' => $item['product']->price,
                'quantity' => $item['quantity'],
            ]);

            $item['product']->decrement('stock', $item['quantity']);
        }

        $this->cart->clear();

        session()->flash('new_order_wa_link', whatsapp_link(wa_order_message($order), admin_whatsapp_number()));

        return redirect()->route('order.success', $order->order_number);
    }

    public function success(string $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        return view('order-success', compact('order'));
    }
}
