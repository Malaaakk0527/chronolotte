<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index()
    {
        return view('cart', [
            'items' => $this->cart->items(),
            'total' => $this->cart->total(),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $this->cart->add($data['product_id'], $data['quantity']);

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $this->cart->update($data['product_id'], $data['quantity']);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(Request $request)
    {
        $this->cart->remove((int) $request->input('product_id'));

        return back()->with('success', 'Produit retiré du panier.');
    }

    public function clear()
    {
        $this->cart->clear();

        return back()->with('success', 'Panier vidé.');
    }
}
