<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home(Request $request)
    {
        $query = Product::with('category')->where('active', true);

        $search = $request->input('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $minPrice = $request->input('min_price');
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }

        $maxPrice = $request->input('max_price');
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }

        $men = (clone $query)->where('gender', 'homme')->orderByDesc('id')->get();
        $women = (clone $query)->where('gender', 'femme')->orderByDesc('id')->get();
        $categories = Category::orderBy('name')->get();

        return view('home', compact('men', 'women', 'categories', 'search'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'images'])
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $related = Product::where('active', true)
            ->where('id', '!=', $product->id)
            ->where('gender', $product->gender)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('product', compact('product', 'related'));
    }
}
