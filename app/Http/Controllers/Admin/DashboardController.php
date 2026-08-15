<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $paidStatuses = ['confirmee', 'expediee', 'livree'];
        $startOfMonth = now()->startOfMonth();
        $sevenDaysAgo = now()->subDays(6)->startOfDay();

        $stats = [
            'products' => Product::count(),
            'active_products' => Product::where('active', true)->count(),
            'categories' => Category::count(),
            'orders' => Order::count(),
            'new_orders' => Order::where('status', 'nouvelle')->count(),
            'revenue' => Order::whereIn('status', $paidStatuses)->sum('total'),
            'orders_by_status' => Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')->toArray(),
            'orders_month' => Order::where('created_at', '>=', $startOfMonth)->count(),
            'revenue_month' => Order::whereIn('status', $paidStatuses)->where('created_at', '>=', $startOfMonth)->sum('total'),
        ];

        $latestOrders = Order::with('items')->latest()->take(8)->get();
        $lowStock = Product::where('stock', '<=', 3)->take(8)->get();

        $topProducts = OrderItem::query()
            ->whereHas('order', fn ($q) => $q->whereIn('status', $paidStatuses))
            ->selectRaw('product_name, SUM(quantity) as qty, SUM(price * quantity) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('qty')
            ->take(5)
            ->get();

        $salesByDay = collect(range(6, 0))->map(function ($i) use ($sevenDaysAgo, $paidStatuses) {
            $day = now()->subDays($i)->startOfDay();

            return [
                'date' => $day,
                'label' => $day->format('d/m'),
                'orders' => Order::where('created_at', '>=', $day)->where('created_at', '<', $day->copy()->addDay())->count(),
                'revenue' => (float) Order::whereIn('status', $paidStatuses)
                    ->where('created_at', '>=', $day)
                    ->where('created_at', '<', $day->copy()->addDay())
                    ->sum('total'),
            ];
        });

        return view('admin.dashboard', compact('stats', 'latestOrders', 'lowStock', 'topProducts', 'salesByDay'));
    }
}
